<?php
include 'class/config.php';
include 'class/database.php';
include 'class/userAuth.php';
$config = new Config();
$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$auth->sessionStart();
if ($auth->loginCheck($conn) === FALSE) {
  header("Location: login.php");
}
$user_data = $_SESSION['user_data'];

$stp = $conn->prepare("SELECT al.*, c.name AS city_name FROM admin_login al LEFT JOIN cities c ON c.id=al.city WHERE al.id=?");
$stp->bind_param('i', $user_data['id']);
$stp->execute();
$resp = $stp->get_result();
$stp->close();
$rowp = $resp->fetch_assoc();
?>

<!-- inlude header -->
<?php include_once 'inc/header.php'; ?>
<!-- content wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>User Profile</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">User Profile</li>
        </ol>
    </section>
    <!-- main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="box box-info">
                    <div class="box-body">
                        <form id="profileUpdateForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <button type="submit" id="profileUpdateFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-wrapper">
                                        <div class="row">                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name" value="<?= $rowp['name']; ?>" class="form-control" placeholder="Name" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" name="email" value="<?= $rowp['email']; ?>" class="form-control" placeholder="Email" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="mobile_no">Mobile No</label>
                                                    <input type="text" id="mobile_no" name="mobile_no" value="<?= $rowp['mobile_no']; ?>" class="form-control" placeholder="Mobile No" autocomplete="off">
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="course_name">Course Name</label>
                                                    <input type="text" id="course_name" name="course_name" value="<?= $rowp['course_name']; ?>" class="form-control" placeholder="Course Name" autocomplete="off">
                                                </div>
                                            </div>                                                                                               
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="branch_name">Branch Name</label>
                                                    <input type="text" id="branch_name" name="branch_name" value="<?= $rowp['branch_name']; ?>" class="form-control" placeholder="Branch Name" autocomplete="off">
                                                </div>
                                            </div>                                                                                               
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="institute_name">Institute Name</label>
                                                    <input type="text" id="institute_name" name="institute_name" value="<?= $rowp['institute_name']; ?>" class="form-control" placeholder="Institute Name" autocomplete="off">
                                                </div>
                                            </div>                                                                                               
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <select name="city" id="city" class="form-control">
                                                        <option value="<?= $rowp['city']; ?>"><?= $rowp['city_name']; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="blog_website">Blog / Website URL</label>
                                                    <input type="text" id="blog_website" name="blog_website" value="<?= $rowp['blog_website']; ?>" class="form-control" placeholder="Blog / Website URL" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="avatar">Profile</label>
                                                    <input type="file" name="avatar" id="avatar" accept=".jpeg, .jpg, .png">
                                                    <p class="help-block">250X250 pixel with 3MB</p>
                                                </div>
                                            </div>
                                        </div>                                                
                                    </div>
                                </div>
                                <?php if($rowp['profile_pic'] !== null) : ?>
                                <div class="col-md-2">
                                    <img class="profile-user-img img-responsive img-circle" src="about-us/<?php echo $rowp['profile_pic']; ?>" alt="User profile picture">
                                    <h3 class="profile-username text-center"><?= $rowp['name']; ?></h3>
                                    <p class="text-muted text-center"><?= $rowp['course_name'] . ' ('. $rowp['branch_name'] .')'; ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content wrapper -->
<?php define('CUSTOM_JS', 'js/profile.js'); ?>
<?php include_once 'inc/footer.php'; ?>
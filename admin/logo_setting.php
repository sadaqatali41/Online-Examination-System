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

$stp = $conn->prepare("SELECT es.* FROM logo_settings es WHERE 1=1");
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
        <h1>Logo Settings</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Logo Settings</li>
        </ol>
    </section>
    <!-- main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="box box-info">
                    <div class="box-body">
                        <form id="logoSettingForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <button type="submit" id="logoSettingFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                </div>
                            </div>
                            <div class="row no-pad">
                                <div class="col-md-8">
                                    <div class="form-wrapper">                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="uni_logo">University Logo</label>
                                                    <input type="file" id="uni_logo" name="uni_logo" accept=".jpg, .png, ,jpeg">
                                                    <p class="help-block">670X145 pixel with 3MB</p>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <?php if($rowp['university_logo'] !== null) : ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <img src="logos/<?= $rowp['university_logo']; ?>" alt="University Logo" srcset="">
                                                </div>
                                            </div>                                            
                                        </div>
                                        <?php endif; ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exam_controller">Exam Controller</label>
                                                    <input type="file" name="exam_controller" id="exam_controller" accept=".jpg, .jpeg, .png">
                                                    <p class="help-block">300X300 pixel with 3MB</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($rowp['exam_controller_logo'] !== null) : ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <img src="logos/<?= $rowp['exam_controller_logo']; ?>" style="height: 13rem;" alt="University Logo" srcset="">
                                                </div>
                                            </div>                                            
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content wrapper -->
<?php define('CUSTOM_JS', 'js/logo_setting.js'); ?>
<?php include_once 'inc/footer.php'; ?>
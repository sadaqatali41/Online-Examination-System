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
?>

<!-- inlude header -->
<?php include_once 'inc/header.php'; ?>
<!-- content wrapper -->
<div class="content-wrapper">
    <?php 
    if(filter_has_var(INPUT_GET, 'act') && filter_input(INPUT_GET, 'act', FILTER_SANITIZE_STRING) !== null) {
        $act = filter_input(INPUT_GET, 'act', FILTER_SANITIZE_STRING);
    } else {
        $act = '';
    }
    switch ($act) {
        default:
    ?>
            <section class="content-header">
                <h1>Course Category List</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Course Category List</li>
                </ol>
            </section>
            <!-- main conter -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <div class="box-title"></div>
                            </div>
                            <div style="padding: 10px;">
                                <table id="example" class="display table table-responsive table-striped table-bordered table-condensed" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Status</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php
            break;
        case 'edit':
            if(filter_has_var(INPUT_GET, 'id')) {
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                $stmt = $conn->prepare("SELECT cc.* FROM course_category cc WHERE cc.id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                } else {
                    header('Location: course_category.php');
                }
            } else {
                header('Location: course_category.php');
            }
        ?>
            <section class="content-header">
                <h1>Edit Course Category</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Edit Course Category</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="courseCategoryEditForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="course_category.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="courseCategoryEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="cc_name">Category Name</label>
                                                            <input type="text" name="cc_name" value="<?= $row['cc_name']; ?>" class="form-control" autocomplete="off" readonly>
                                                            <input type="hidden" name="cc_id" value="<?= $row['id']; ?>">
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="cc_status">Category Status</label>
                                                            <select name="cc_status" class="form-control">
                                                                <option value="A" <?php if($row['cc_status']=='A'){echo 'selected';} ?>>Active</option>
                                                                <option value="I" <?php if($row['cc_status']=='I'){echo 'selected';} ?>>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    <?php  break; } ?>
</div>
<!-- /.content wrapper -->
<?php define('CUSTOM_JS', 'js/course_category.js'); ?>
<?php include_once 'inc/footer.php'; ?>
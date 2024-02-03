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
    <section class="content-header">
        <h1>Change Password</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Change Password</li>
        </ol>
    </section>
    <!-- main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4 col-xs-12 col-sm-12">
                <div class="box box-info">
                    <div class="box-body">
                        <form id="changePasswordForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" id="changePasswordFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-wrapper">
                                        <div class="row">                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="old_password">Old Password</label>
                                                    <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Old Password" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="new_password">New Password</label>
                                                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password" autocomplete="off">
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
</div>
<!-- /.content wrapper -->
<?php define('CUSTOM_JS', 'js/change_password.js'); ?>
<?php include_once 'inc/footer.php'; ?>
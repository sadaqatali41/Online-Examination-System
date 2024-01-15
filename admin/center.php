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
                <h1>Center List</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Center List</li>
                </ol>
            </section>
            <!-- main conter -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <div class="box-title">
                                    <a href="center.php?act=add" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</a>                                    
                                </div>
                            </div>
                            <div style="padding: 10px;">
                                <table id="example" class="display table table-responsive table-striped table-bordered table-condensed" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>City</th>
                                            <th>Address</th>
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
        case 'add':
        ?>
            <section class="content-header">
                <h1>Add Center</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Add Center</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="centerAddForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="center.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="centerAddFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="table-wrapper" style="max-width: 100%; height: 350px; padding:0px; margin: 0px; overflow-y: scroll; overflow-x:scroll; -webkit-overflow-scrolling: touch;">
                                                    <table class="fixed-header table-striped table-bordered table-condensed" id="centerTbl" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30px;"><button type="button" class="btn btn-info btn-xs" id="addNewRow"><i class="fa fa-plus"></i></button></th>
                                                                <th style="width: 180px;">Center Name</th>
                                                                <th style="width: 110px;">Center Code</th>
                                                                <th style="width: 180px;">Center City</th>
                                                                <th>Address</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><button type="button" class="btn btn-xs btn-danger removeRow"><i class="fa fa-minus"></i></button></td>
                                                                <td><input type="text" name="center_name[]" class="form-control center_name" autocomplete="off"></td>
                                                                <td><input type="text" name="center_code[]" class="form-control center_code" autocomplete="off"></td>
                                                                <td>
                                                                    <select name="center_city[]" class="form-control center_city"></select>
                                                                </td>
                                                                <td><input type="text" name="address[]" class="form-control address" autocomplete="off"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                            
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
<?php define('CUSTOM_JS', 'js/center.js'); ?>
<?php include_once 'inc/footer.php'; ?>
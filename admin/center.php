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
                                <div class="box-search">
                                    <a href="center.php?act=add" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Add</a>
                                    <form class="form-inline" style="display: inline;">
                                        <div class="form-group">
                                            <select id="center_city" class="form-control center_city"></select>
                                        </div>
                                        <div class="form-group">
                                            <select name="center_status" id="center_status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="A">Active</option>
                                                <option value="I">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-info btn-xs" id="search">Search</button>
                                        <a href="center.php" class="btn btn-default btn-xs">Reset</a>
                                    </form>                                    
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
                                            <th>Total Students</th>
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
        <?php
            break;
        case 'edit':
            if(filter_has_var(INPUT_GET, 'id')) {
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                $stmt = $conn->prepare("SELECT c.*, ct.name FROM centers c INNER JOIN cities ct ON ct.id=c.center_city WHERE c.id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                } else {
                    header('Location: center.php');
                }
            } else {
                header('Location: center.php');
            }
        ?>
            <section class="content-header">
                <h1>Edit Center</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Edit Center</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="centerEditForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="center.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="centerEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="center_name">Center Name</label>
                                                            <input type="text" name="center_name" value="<?= $row['center_name']; ?>" class="form-control" autocomplete="off">
                                                            <input type="hidden" name="center_id" value="<?= $row['id']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="center_code">Center Code</label>
                                                            <input type="text" name="center_code" value="<?= $row['center_code']; ?>" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="center_city">Center City</label>
                                                            <select name="center_city" class="form-control center_city">
                                                                <option value="<?= $row['center_city']; ?>"><?= $row['name']; ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="center_status">Center Status</label>
                                                            <select name="center_status" class="form-control">
                                                                <option value="A" <?php if($row['center_status']=='A'){echo 'selected';} ?>>Active</option>
                                                                <option value="I" <?php if($row['center_status']=='I'){echo 'selected';} ?>>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="address">Address</label>
                                                            <input type="text" name="address" value="<?= $row['center_address']; ?>" class="form-control" autocomplete="off">
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
<?php define('CUSTOM_JS', 'js/center.js'); ?>
<?php include_once 'inc/footer.php'; ?>
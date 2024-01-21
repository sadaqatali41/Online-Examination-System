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
                <h1>Course List</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Course List</li>
                </ol>
            </section>
            <!-- main conter -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <div class="box-search">
                                    <a href="course.php?act=add" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Add</a>
                                    <form class="form-inline" style="display: inline;">
                                        <div class="form-group">
                                            <select id="cc_id" class="form-control cc_id"></select>
                                        </div>
                                        <div class="form-group">
                                            <select name="course_status" id="course_status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="A">Active</option>
                                                <option value="I">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-info btn-xs" id="search">Search</button>
                                        <a href="course.php" class="btn btn-default btn-xs">Reset</a>
                                    </form>                                    
                                </div>
                            </div>
                            <div style="padding: 10px;">
                                <table id="example" class="display table table-responsive table-striped table-bordered table-condensed" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Category</th>
                                            <th>Course Name</th>
                                            <th>Course Code</th>
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
                <h1>Add Course</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Add Course</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="courseAddForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="course.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="courseAddFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="table-wrapper" style="max-width: 100%; height: 350px; padding:0px; margin: 0px; overflow-y: scroll; overflow-x:scroll; -webkit-overflow-scrolling: touch;">
                                                    <table class="fixed-header table-striped table-bordered table-condensed" id="inputsTbl" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30px;"><button type="button" class="btn btn-info btn-xs" id="addNewRow"><i class="fa fa-plus"></i></button></th>
                                                                <th style="width: 180px;">Course Category</th>
                                                                <th style="width: 180px;">Course Name</th>
                                                                <th style="width: 110px;">Course Code</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><button type="button" class="btn btn-xs btn-danger removeRow"><i class="fa fa-minus"></i></button></td>
                                                                <td>
                                                                    <select name="cc_id[]" class="form-control cc_id"></select>
                                                                </td>
                                                                <td><input type="text" name="course_name[]" class="form-control course_name" autocomplete="off"></td>
                                                                <td><input type="text" name="course_code[]" class="form-control course_code" autocomplete="off"></td>                                                                
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
                $stmt = $conn->prepare("SELECT c.*, cc.cc_name FROM courses c INNER JOIN course_category cc ON cc.id=c.cc_id WHERE c.id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                } else {
                    header('Location: course.php');
                }
            } else {
                header('Location: course.php');
            }
        ?>
            <section class="content-header">
                <h1>Edit Course</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Edit Course</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="courseEditForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="course.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="courseEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cc_id">Course Category</label>
                                                            <select name="cc_id" class="form-control cc_id">
                                                                <option value="<?= $row['cc_id']; ?>"><?= $row['cc_name']; ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="course_name">Course Name</label>
                                                            <input type="text" name="course_name" value="<?= $row['course_name']; ?>" class="form-control" autocomplete="off">
                                                            <input type="hidden" name="course_id" value="<?= $row['id']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="course_code">Course Code</label>
                                                            <input type="text" name="course_code" value="<?= $row['course_code']; ?>" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="course_status">Course Status</label>
                                                            <select name="course_status" class="form-control">
                                                                <option value="A" <?php if($row['course_status']=='A'){echo 'selected';} ?>>Active</option>
                                                                <option value="I" <?php if($row['course_status']=='I'){echo 'selected';} ?>>Inactive</option>
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
<?php define('CUSTOM_JS', 'js/course.js'); ?>
<?php include_once 'inc/footer.php'; ?>
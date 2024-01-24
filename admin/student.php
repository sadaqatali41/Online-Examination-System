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
                <h1>Student List</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Student List</li>
                </ol>
            </section>
            <!-- main conter -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <div class="box-search">
                                    <a href="student.php?act=add" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Add</a>
                                    <form class="form-inline" style="display: inline;">
                                        <div class="form-group">
                                            <select name="course_id" id="course_id" class="form-control"></select>
                                        </div>
                                        <div class="form-group">
                                            <select name="ec_status" id="ec_status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="A">Active</option>
                                                <option value="I">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-info btn-xs" id="search">Search</button>
                                        <a href="student.php" class="btn btn-default btn-xs">Reset</a>
                                    </form>
                                </div>
                            </div>
                            <div style="padding: 10px;">
                                <table id="example" class="display table table-responsive table-striped table-bordered table-condensed" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Name</th>
                                            <th>Student</th>                            
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
                <h1>Add Student</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Add Student</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="studentAddForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="student.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="studentAddFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="fname">First Name</label>
                                                            <input type="text" name="fname" id="fname" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="lname">Last Name</label>
                                                            <input type="text" name="lname" id="lname" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" name="email" id="email" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <input type="password" name="password" id="password" class="form-control" placeholder="atleast 8 chars" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="mobile">Mobile No</label>
                                                            <input type="text" name="mobile" id="mobile" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="country">Country</label>
                                                            <select name="country" id="country" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="state">State</label>
                                                            <select name="state" id="state" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="city">City</label>
                                                            <select name="city" id="city" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="pin">Pin No</label>
                                                            <input type="text" name="pin" id="pin" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="dob">DOB</label>
                                                            <input type="text" name="dob" id="dob" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <label for="address">Address</label>
                                                            <input type="text" name="address" id="address" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="course_id">Course</label>
                                                            <select name="course_id" id="course_id" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="center_id">Center</label>
                                                            <select name="center_id" id="center_id" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="gender">Gender</label>
                                                            <select name="gender" id="gender" class="form-control">
                                                                <option value="">Choose Gender</option>
                                                                <option value="M">Male</option>
                                                                <option value="F">Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
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
        <?php
            break;
        case 'edit':
            if(filter_has_var(INPUT_GET, 'id')) {
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                $stmt = $conn->prepare("SELECT ec.*, cc.course_name FROM eligibility_criteria ec INNER JOIN courses cc ON cc.id=ec.course_id WHERE ec.id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                } else {
                    header('Location: student.php');
                }
            } else {
                header('Location: student.php');
            }
        ?>
            <section class="content-header">
                <h1>Edit Student</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Edit Student</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="ecEditForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="student.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="ecEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="course_id">Course Name</label>
                                                            <select name="course_id" id="course_id" class="form-control">
                                                                <option value="<?= $row['course_id']; ?>"><?= $row['course_name']; ?></option>
                                                            </select>
                                                            <input type="hidden" name="ec_id" value="<?= $row['id']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="eligibility_criteria">Student</label>
                                                            <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control" rows="7"><?= $row['eligibility_criteria']; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="row">                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="ec_status">EC. Status</label>
                                                            <select name="ec_status" class="form-control">
                                                                <option value="A" <?php if($row['ec_status']=='A'){echo 'selected';} ?>>Active</option>
                                                                <option value="I" <?php if($row['ec_status']=='I'){echo 'selected';} ?>>Inactive</option>
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
<?php define('CUSTOM_JS', 'js/student.js'); ?>
<?php include_once 'inc/footer.php'; ?>
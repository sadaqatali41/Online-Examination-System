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
                <h1>Exam Schedule List</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Exam Schedule List</li>
                </ol>
            </section>
            <!-- main conter -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <div class="box-search">
                                    <a href="exam_schedule.php?act=add" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Add</a>
                                    <form class="form-inline" style="display: inline;">
                                        <div class="form-group">
                                            <select name="course_id" id="course_id" class="form-control"></select>
                                        </div>
                                        <div class="form-group">
                                            <select name="es_status" id="es_status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="A">Active</option>
                                                <option value="I">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-info btn-xs" id="search">Search</button>
                                        <a href="exam_schedule.php" class="btn btn-default btn-xs">Reset</a>
                                    </form>
                                </div>
                            </div>
                            <div style="padding: 10px;">
                                <table id="example" class="display table table-responsive table-striped table-bordered table-condensed" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Name</th>
                                            <th>Year</th>
                                            <th>Last Date</th>
                                            <th>Exam Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Duration(HRS)</th>
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
                <h1>Add Exam Schedule</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Add Exam Schedule</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-8 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="examScheduleAddForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="exam_schedule.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="examScheduleAddFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="course_id">Course Name</label>
                                                            <select name="course_id" id="course_id" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="regis_last_date">Last Registration Date</label>
                                                            <input type="text" id="regis_last_date" name="regis_last_date" class="form-control" placeholder="YYYY-MM-DD HH:II:SS" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exam_date">Exam Date</label>
                                                            <input type="text" id="exam_date" name="exam_date" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="start_time">Exam Start Time</label>
                                                            <input type="text" id="start_time" name="start_time" class="form-control" placeholder="HH:II:SS" autocomplete="off">
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="end_time">Exam End Time</label>
                                                            <input type="text" id="end_time" name="end_time" class="form-control" placeholder="HH:II:SS" autocomplete="off">
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
                $for_year = date('Y');
                $stmt = $conn->prepare("SELECT es.*, cc.course_name FROM exam_schedule es INNER JOIN courses cc ON cc.id=es.course_id WHERE es.id=? AND es.for_year=?");
                $stmt->bind_param("ii", $id, $for_year);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                } else {
                    header('Location: exam_schedule.php');
                }
            } else {
                header('Location: exam_schedule.php');
            }
        ?>
            <section class="content-header">
                <h1>Edit Exam Schedule</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Edit Exam Schedule</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="examScheduleEditForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="exam_schedule.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="examScheduleEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
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
                                                            <input type="hidden" name="es_id" value="<?= $row['id']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="regis_last_date">Last Registration Date</label>
                                                            <input type="text" id="regis_last_date" name="regis_last_date" value="<?= $row['regis_last_date']; ?>" class="form-control" placeholder="YYYY-MM-DD HH:II:SS" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exam_date">Exam Date</label>
                                                            <input type="text" id="exam_date" name="exam_date" value="<?= $row['exam_date']; ?>" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="start_time">Exam Start Time</label>
                                                            <input type="text" id="start_time" name="start_time" value="<?= $row['start_time']; ?>" class="form-control" placeholder="HH:II:SS" autocomplete="off">
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="end_time">Exam End Time</label>
                                                            <input type="text" id="end_time" name="end_time" value="<?= $row['end_time']; ?>" class="form-control" placeholder="HH:II:SS" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="es_status">ES. Status</label>
                                                            <select name="es_status" class="form-control">
                                                                <option value="A" <?php if($row['es_status']=='A'){echo 'selected';} ?>>Active</option>
                                                                <option value="I" <?php if($row['es_status']=='I'){echo 'selected';} ?>>Inactive</option>
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
<?php define('CUSTOM_JS', 'js/exam_schedule.js'); ?>
<?php include_once 'inc/footer.php'; ?>
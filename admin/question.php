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
                <h1>Question List</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Question List</li>
                </ol>
            </section>
            <!-- main conter -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <div class="box-search">
                                    <a href="question.php?act=add" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Add</a>
                                    <form class="form-inline" style="display: inline;">
                                        <div class="form-group">
                                            <select name="course_id" id="course_id" class="form-control"></select>
                                        </div>
                                        <div class="form-group">
                                            <select name="question_status" id="question_status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="A">Active</option>
                                                <option value="I">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-info btn-xs" id="search">Search</button>
                                        <a href="question.php" class="btn btn-default btn-xs">Reset</a>
                                    </form>
                                </div>
                            </div>
                            <div style="padding: 10px;">
                                <table id="example" class="display table table-responsive table-striped table-bordered table-condensed" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Name</th>
                                            <th>Question Name</th>
                                            <th>Option A</th>
                                            <th>Option B</th>
                                            <th>Option C</th>
                                            <th>Option D</th>
                                            <th>Correct Option</th>
                                            <th>Marks</th>
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
                <h1>Add Question</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Add Question</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="questionAddForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="question.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="questionAddFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
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
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="question_name">Question</label>
                                                            <textarea name="question_name" id="question_name" class="form-control" rows="7"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionA">Option A</label>
                                                            <input type="text" name="optionA" id="optionA" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionB">Option B</label>
                                                            <input type="text" name="optionB" id="optionB" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionC">Option C</label>
                                                            <input type="text" name="optionC" id="optionC" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionD">Option D</label>
                                                            <input type="text" name="optionD" id="optionD" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="correct_option">Correct Option</label>
                                                            <select name="correct_option" id="correct_option" class="form-control">
                                                                <option value="">--Select Correct Option--</option>
                                                                <option value="optionA">Option A</option>
                                                                <option value="optionB">Option B</option>
                                                                <option value="optionC">Option C</option>
                                                                <option value="optionD">Option D</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="marks">Marks</label>
                                                            <input type="text" name="marks" id="marks" class="form-control" value="1" autocomplete="off">
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
                $stmt = $conn->prepare("SELECT q.*, cc.course_name FROM questions q INNER JOIN courses cc ON cc.id=q.course_id WHERE q.id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                } else {
                    header('Location: question.php');
                }
            } else {
                header('Location: question.php');
            }
        ?>
            <section class="content-header">
                <h1>Edit Question</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Forms</a></li>
                    <li class="active">Edit Question</li>
                </ol>
            </section>
            <!-- main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="questionEditForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="question.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="questionEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
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
                                                            <input type="hidden" name="question_id" value="<?= $row['id']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="question_name">Question</label>
                                                            <textarea name="question_name" id="question_name" class="form-control" rows="7"><?= $row['question_name']; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionA">Option A</label>
                                                            <input type="text" name="optionA" id="optionA" value="<?= $row['optionA']; ?>" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionB">Option B</label>
                                                            <input type="text" name="optionB" id="optionB" value="<?= $row['optionB']; ?>" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionC">Option C</label>
                                                            <input type="text" name="optionC" id="optionC" value="<?= $row['optionC']; ?>" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="optionD">Option D</label>
                                                            <input type="text" name="optionD" id="optionD" value="<?= $row['optionD']; ?>" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="correct_option">Correct Option</label>
                                                            <select name="correct_option" id="correct_option" class="form-control">
                                                                <option value="">--Select Correct Option--</option>
                                                                <option value="optionA" <?php if($row['correct_option']=='optionA'){echo 'selected';} ?>>Option A</option>
                                                                <option value="optionB" <?php if($row['correct_option']=='optionB'){echo 'selected';} ?>>Option B</option>
                                                                <option value="optionC" <?php if($row['correct_option']=='optionC'){echo 'selected';} ?>>Option C</option>
                                                                <option value="optionD" <?php if($row['correct_option']=='optionD'){echo 'selected';} ?>>Option D</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="marks">Marks</label>
                                                            <input type="text" name="marks" id="marks" class="form-control" value="<?= $row['marks']; ?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="question_status">Question Status</label>
                                                            <select name="question_status" class="form-control">
                                                                <option value="A" <?php if($row['question_status']=='A'){echo 'selected';} ?>>Active</option>
                                                                <option value="I" <?php if($row['question_status']=='I'){echo 'selected';} ?>>Inactive</option>
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
<?php define('CUSTOM_JS', 'js/question.js'); ?>
<?php include_once 'inc/footer.php'; ?>
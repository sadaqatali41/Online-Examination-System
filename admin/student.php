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
<?php define('SC', 'sidebar-collapse'); ?>
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
                                            <select name="center_id" id="center_id" class="form-control"></select>
                                        </div>
                                        <div class="form-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Mobile No</th>
                                            <th>Gender</th>
                                            <th>Course Name</th>
                                            <th>Center Name</th>
                                            <th>Primary Address</th>
                                            <th>Full Address</th>
                                            <th>Avatar</th>
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
            <!-- PDF modal -->
            <div class="modal fade" id="studentPdfModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="studentModalTitle">Student PDF</h4>
                        </div>
                        <div class="modal-body">
                            <iframe id="pdfIframe" style="height: 500px; width: 100%;"></iframe>
                        </div>                        
                    </div>
                </div>
            </div>
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
                                <form id="studentAddForm" enctype="multipart/form-data">
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
                                                            <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="lname">Last Name</label>
                                                            <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <input type="password" name="password" id="password" class="form-control" placeholder="atleast 8 chars" autocomplete="off">
                                                            <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="mobile">Mobile No</label>
                                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile WhatsApp" autocomplete="off">
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
                                                            <input type="text" name="pin" id="pin" class="form-control" placeholder="Pin Code" autocomplete="off">
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
                                                            <input type="text" name="address" id="address" class="form-control" placeholder="Address" autocomplete="off">
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
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="avatar">Avatar</label>
                                                            <input type="file" name="avatar" id="avatar" accept=".jpeg, .jpg, .png">
                                                            <p class="help-block">300X300 pixel with 3MB</p>
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
                $stmt = $conn->prepare("SELECT st.id, st.fname, st.lname, st.email, st.password, st.phone, st.gender, st.course_id, cs.course_name, cc.cc_code, 
                st.center_id, ce.center_name, st.city, st.pin, st.state, st.country, c.name AS city_nm, s.name AS state_nm, ct.name AS country_nm, 
                st.pin, st.address, st.avatar, st.status, st.dob 
                FROM students st 
                LEFT JOIN courses cs ON cs.id=st.course_id 
                LEFT JOIN centers ce ON ce.id=st.center_id 
                LEFT JOIN cities c ON c.id=st.city 
                LEFT JOIN states s ON s.id=st.state 
                LEFT JOIN countries ct ON ct.id=st.country 
                LEFT JOIN course_category cc ON cc.id=cs.cc_id
                WHERE st.id=?");
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

            #high school data
            $sths = $conn->prepare("SELECT hs.* FROM highschool hs WHERE hs.student_id=?");
            $sths->bind_param("i", $id);
            $sths->execute();
            $reshs = $sths->get_result();
            $sths->close();
            $rowhs = $reshs->fetch_assoc();

            #intermediate data
            $stim = $conn->prepare("SELECT im.* FROM intermediate im WHERE im.student_id=?");
            $stim->bind_param("i", $id);
            $stim->execute();
            $resim = $stim->get_result();
            $stim->close();
            $rowim = $resim->fetch_assoc();

            #intermediate data
            $stgd = $conn->prepare("SELECT gd.* FROM graduation gd WHERE gd.student_id=?");
            $stgd->bind_param("i", $id);
            $stgd->execute();
            $resgd = $stgd->get_result();
            $stgd->close();
            $rowgd = $resgd->fetch_assoc();
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
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <form id="studentEditForm" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="student.php" class="btn btn-sm btn-info"><i class="fa fa-list"></i> View</a>
                                            <button type="submit" id="studentEditFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-wrapper">
                                                <ul class="nav nav-tabs" style="border-bottom: none;">
                                                    <li class="active">
                                                        <a href="#student_info" data-toggle="tab" aria-expanded="true">Student Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#high_school" data-toggle="tab" aria-expanded="false">High School</a>
                                                    </li>
                                                    <li>
                                                        <a href="#intermediate" data-toggle="tab" aria-expanded="false">Intermediate</a>
                                                    </li>
                                                    <?php if($row['cc_code'] === 'PG') : ?>
                                                    <li>
                                                        <a href="#graduation" data-toggle="tab" aria-expanded="false">Graduation</a>
                                                    </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="student_info">
                                                    <div class="form-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="fname">First Name</label>
                                                                    <input type="text" name="fname" id="fname" value="<?= $row['fname']; ?>" class="form-control" placeholder="First Name" autocomplete="off">
                                                                    <input type="hidden" name="student_id" id="student_id" value="<?= $row['id']; ?>">
                                                                    <input type="hidden" name="cc_code" id="cc_code" value="<?= $row['cc_code']; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="lname">Last Name</label>
                                                                    <input type="text" name="lname" id="lname" value="<?= $row['lname']; ?>" class="form-control" placeholder="Last Name" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="email" name="email" id="email" value="<?= $row['email']; ?>" class="form-control" placeholder="Email" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="password">Password</label>
                                                                    <input type="password" name="password" id="password" value="<?= $row['password']; ?>" class="form-control" placeholder="atleast 8 chars" autocomplete="off">
                                                                    <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="mobile">Mobile No</label>
                                                                    <input type="text" name="mobile" id="mobile" value="<?= $row['phone']; ?>" class="form-control" placeholder="Mobile WhatsApp" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="country">Country</label>
                                                                    <select name="country" id="country" class="form-control">
                                                                        <option value="<?= $row['country']; ?>"><?= $row['country_nm']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="state">State</label>
                                                                    <select name="state" id="state" class="form-control">
                                                                        <option value="<?= $row['state']; ?>"><?= $row['state_nm']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="city">City</label>
                                                                    <select name="city" id="city" class="form-control">
                                                                        <option value="<?= $row['city']; ?>"><?= $row['city_nm']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="pin">Pin No</label>
                                                                    <input type="text" name="pin" id="pin" value="<?= $row['pin']; ?>" class="form-control" placeholder="Pin Code" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="dob">DOB</label>
                                                                    <input type="text" name="dob" id="dob" value="<?= $row['dob']; ?>" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="form-group">
                                                                    <label for="address">Address</label>
                                                                    <input type="text" name="address" id="address" value="<?= $row['address']; ?>" class="form-control" placeholder="Address" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="course_id">Course</label>
                                                                    <select name="course_id" id="course_id" class="form-control">
                                                                        <option value="<?= $row['course_id']; ?>"><?= $row['course_name']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="center_id">Center</label>
                                                                    <select name="center_id" id="center_id" class="form-control">
                                                                        <option value="<?= $row['center_id']; ?>"><?= $row['center_name']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="gender">Gender</label>
                                                                    <select name="gender" id="gender" class="form-control">
                                                                        <option value="">Choose Gender</option>
                                                                        <option value="M" <?php if($row['gender']=='M'){echo 'selected';} ?>>Male</option>
                                                                        <option value="F" <?php if($row['gender']=='F'){echo 'selected';} ?>>Female</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="status">Status</label>
                                                                    <select name="status" id="status" class="form-control">
                                                                        <option value="1" <?php if($row['status']=='1'){echo 'selected';} ?>>Active</option>
                                                                        <option value="0" <?php if($row['status']=='0'){echo 'selected';} ?>>Inactive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="avatar">Avatar</label>
                                                                    <input type="file" name="avatar" id="avatar" accept=".jpeg, .jpg, .png">
                                                                    <p class="help-block">300X300 pixel with 3MB</p>
                                                                </div>
                                                            </div>
                                                            <?php if($row['avatar'] !== null) : ?>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <img class="img-lg img-circle" src="../students/<?php echo $row['id'] . '/' . $row['avatar']; ?>" alt="Profile" srcset="">
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="high_school">
                                                    <div class="form-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="school_name">School Name</label>
                                                                    <input type="text" name="school_name" id="school_name" value="<?= ($rowhs['school_name'] ?? ''); ?>" class="form-control" placeholder="School Name with address" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="board_name">Board Name</label>
                                                                    <input type="text" name="board_name" id="board_name" value="<?= ($rowhs['board_name'] ?? ''); ?>" class="form-control" placeholder="Board Name" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="roll_no">Roll No</label>
                                                                    <input type="text" name="roll_no" id="roll_no" value="<?= ($rowhs['roll_no'] ?? ''); ?>" class="form-control" placeholder="Roll No" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="yop">Year of Passing</label>
                                                                    <input type="text" name="yop" id="yop" value="<?= ($rowhs['yop'] ?? ''); ?>" class="form-control" placeholder="Year of Passing" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="percent">Passing %</label>
                                                                    <input type="text" name="percent" id="percent" value="<?= ($rowhs['percent'] ?? ''); ?>" class="form-control" placeholder="Passing %" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="intermediate">
                                                    <div class="form-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="college_name">College Name</label>
                                                                    <input type="text" name="college_name" id="college_name" value="<?= ($rowim['college_name'] ?? ''); ?>" class="form-control" placeholder="College Name with address" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="im_board_name">Board Name</label>
                                                                    <input type="text" name="im_board_name" id="im_board_name" value="<?= ($rowim['board_name'] ?? ''); ?>" class="form-control" placeholder="Board Name" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="im_roll_no">Roll No</label>
                                                                    <input type="text" name="im_roll_no" id="im_roll_no" value="<?= ($rowim['roll_no'] ?? ''); ?>" class="form-control" placeholder="Roll No" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="im_yop">Year of Passing</label>
                                                                    <input type="text" name="im_yop" id="im_yop" value="<?= ($rowim['yop'] ?? ''); ?>" class="form-control" placeholder="Year of Passing" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="im_percent">Passing %</label>
                                                                    <input type="text" name="im_percent" id="im_percent" value="<?= ($rowim['percent'] ?? ''); ?>" class="form-control" placeholder="Passing %" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="graduation">
                                                    <div class="form-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="institute_name">Institute Name</label>
                                                                    <input type="text" name="institute_name" id="institute_name" value="<?= ($rowgd['institute_name'] ?? ''); ?>" class="form-control" placeholder="Institute Name with address" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="course_name">Course Name</label>
                                                                    <input type="text" name="course_name" id="course_name" value="<?= ($rowgd['course_name'] ?? ''); ?>" class="form-control" placeholder="Course Name" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="branch_name">Branch Name</label>
                                                                    <input type="text" name="branch_name" id="branch_name" value="<?= ($rowgd['branch_name'] ?? ''); ?>" class="form-control" placeholder="Branch Name" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="enroll_no">Enroll No</label>
                                                                    <input type="text" name="enroll_no" id="enroll_no" value="<?= ($rowgd['enroll_no'] ?? ''); ?>" class="form-control" placeholder="Enroll No" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="gd_yop">Year of Passing</label>
                                                                    <input type="text" name="gd_yop" id="gd_yop" value="<?= ($rowgd['yop'] ?? ''); ?>" class="form-control" placeholder="Year of Passing" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="aggregate_percent">Passing %</label>
                                                                    <input type="text" name="aggregate_percent" id="aggregate_percent" value="<?= ($rowgd['aggregate_percent'] ?? ''); ?>" class="form-control" placeholder="Passing %" autocomplete="off">
                                                                </div>
                                                            </div>
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
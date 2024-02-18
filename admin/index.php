<?php
include 'class/config.php';
include 'class/database.php';
include 'class/userAuth.php';
include 'class/dashboard.php';
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
    <h1>Dashboard</h1>
    <ol class="breadcrumb" style="background:var(--colorLightest)">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- main content -->
  <section class="content dashboard-content">
    <div class="row">
      <div class="col-sm-12">
        <div class="row">
          <!-- one -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-map-marker"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Centers</span>
                <span class="info-box-number"><?= Dashboard::totalCenters($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="center" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- two -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-shield"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Course Category</span>
                <span class="info-box-number"><?= Dashboard::totalCourseCategories($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="course_category" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- three -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Courses</span>
                <span class="info-box-number"><?= Dashboard::totalCourses($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="course" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- four -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-question-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Questions</span>
                <span class="info-box-number"><?= Dashboard::totalQuestions($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="question" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- one -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-anchor"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Eligibility Criteria</span>
                <span class="info-box-number"><?= Dashboard::totalEligibilityCriteria($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="eligibility_criteria" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- two -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Exam Schedule</span>
                <span class="info-box-number"><?= Dashboard::totalExamSchedule($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="exam_schedule" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- three -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Students</span>
                <span class="info-box-number"><?= Dashboard::totalStudents($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="student" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- four -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-user-plus"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number"><?= Dashboard::totalUsers($conn); ?></span>
              </div>
              <div class="info-button">
                <a href="about_us" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- /.content wrapper -->
<?php include_once 'inc/footer.php'; ?>
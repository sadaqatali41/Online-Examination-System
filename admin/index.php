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
              <span class="info-box-icon bg-aqua"><i class="fa fa-shield"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Soon</span>
                <span class="info-box-number">0</span>
              </div>
              <div class="info-button">
                <button class="btn btn-danger btn-xs">Take Action</button>
              </div>
            </div>
          </div>
          <!-- two -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-shield"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Soon</span>
                <span class="info-box-number">0</span>
              </div>
              <div class="info-button">
                <button class="btn btn-danger">Take Action</button>
              </div>
            </div>
          </div>
          <!-- three -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-check-square-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Soon</span>
                <span class="info-box-number">0</span>
              </div>
              <div class="info-button">
                <button class="btn btn-danger">Take Action</button>
              </div>
            </div>
          </div>
          <!-- four -->
          <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-clock-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Soon</span>
                <span class="info-box-number">0</span>
              </div>
              <div class="info-button">
                <button class="btn btn-danger btn-xs">Take Action</button>
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
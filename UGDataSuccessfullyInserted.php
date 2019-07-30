<?php
session_start();
include 'header.php';
if(!isset($_SESSION['successUG']))
{
	header('location:http://localhost/OES_BS/student-sign-out');
}

?>
<!DOCTYPE html>
<html>
<body>
	<div class="container-fluid">
		<nav class="navbar navbar-inverse">
			<div class="navbar-header">
				<button class="navbar-toggle" data-toggle="collapse" data-target="#Profiles" type="button">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<p class="navbar-text" style="color: cyan;font-weight: bold;">Student Profiles</p>
			</div>
			<div class="collapse navbar-collapse" id="Profiles">
				<ul class="nav navbar-nav">
					<li class="active"><a href="ug-dashboard">
						<span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="print-admit-card"><span class="glyphicon glyphicon-print"></span> Download Admit Card</a></li>
					<li><a href="final-result">Check Your Result</a></li>
					<li><a href="student-ug-info">Check Your Details</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<p class="navbar-text" style="color: pink;font-weight: bold;font-style: italic;">Hello.. 
						<?php echo ucwords("$_SESSION[fname] $_SESSION[lname]");  ?></p>
					<li><a href="student-sign-out"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a></li>
				</ul>
			</div>
		</nav>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-offset-3 col-sm-6">
				<h1 class="bg-success text-center text-muted">Welcome To Dashboard</h1>
				<h2 class="text-center text-primary bg-primary">Hi, <?php echo ucwords("$_SESSION[fname] $_SESSION[lname]");  ?></h2>
			</div>
		</div>
	</div><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
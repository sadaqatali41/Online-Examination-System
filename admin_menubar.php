<?php
session_start();
include 'header.php';
?>
<!DOCTYPE html>
<html>
<body>
<div class="container-fluid">
		<nav class="navbar navbar-inverse">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#Admin">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<p class="navbar-text" style="color: cyan;font-style: italic;font-weight: bold;">Admin Profile</p>
			</div>
			<div class="collapse navbar-collapse" id="Admin">
				<ul class="nav navbar-nav">
					<li><a href="admin-dashboard"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus-sign">
						</span> Adding Form <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="add-course">Add Course</a></li>
							<li><a href="add-center">Add Center</a></li>
							<li><a href="add-question">Add Question</a></li>
							<li><a href="upload-question">Upload Questions</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-camera"></span> Viewing Form <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="view-course">View Courses</a></li>
							<li><a href="view-center">View Centers</a></li>
							<li><a href="view-questions">View Questions</a></li>
							<li><a href="student-registrations">Student Registrations</a></li>
							<li><a href="attended-exam">Exam Given</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-search"></span> Searching <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="exam-results">Selected/Waiting</a></li>
							<li><a href="search-by-courses">Search Students</a></li>
							<li><a href="search-by-tables">Search Tables</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<p class="navbar-text" style="color: red;font-weight: bold;font-style: italic;">
						Hello..<?php echo " ".$_SESSION['adminname']; ?></p>
					<li><a href="log-out"><span class="glyphicon glyphicon-log-out"></span> Sign-Out</a></li>
				</ul>
			</div>
		</nav>
	</div>
</body>
</html>
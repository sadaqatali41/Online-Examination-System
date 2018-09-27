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
					<li><a href="welcomadmin.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus-sign">
						</span> Adding Form <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="addcourse.php">Add Course</a></li>
							<li><a href="addcity.php">Add Center</a></li>
							<li><a href="addquestion.php">Add Question</a></li>
							<li><a href="upload_question.php">Upload Questions</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-camera"></span> Viewing Form <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="ViewCourse.php">View Courses</a></li>
							<li><a href="ViewCenter.php">View Centers</a></li>
							<li><a href="ViewQuestions.php">View Questions</a></li>
							<li><a href="viewrecords.php">View Records</a></li>
							<li><a href="examgiven.php">Exam Given</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-search"></span> Searching <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="Select_Waiting.php">Selected/Waiting</a></li>
							<li><a href="search_by_course.php">Search Students</a></li>
							<li><a href="search_by_table.php">Search Tables</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<p class="navbar-text" style="color: red;font-weight: bold;font-style: italic;">
						Hello..<?php echo " ".$_SESSION['adminname']; ?></p>
					<li><a href="signout.php"><span class="glyphicon glyphicon-log-out"></span> Sign-Out</a></li>
				</ul>
			</div>
		</nav>
	</div>
</body>
</html>
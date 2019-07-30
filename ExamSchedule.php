<?php
include 'header.php';
include 'menubar.php';
$con=mysqli_connect('localhost','root','','online_examination_system');
$query="select * from examschedule";
$result=mysqli_query($con,$query);
$row=mysqli_num_rows($result);

?>
<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/ExamSchedule.css">-->
	<style type="text/css">
		div#ExamSchedule
		{
			width: 70%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="ExamSchedule">
<table class="table table-bordered">
	<thead>
		<tr class="danger">
			<th colspan="6" class="text-center text-primary" style="font-size: 18px;font-style: italic;">Examination Informations</th>
		</tr>
	</thead>
	<tbody>
		<tr class="text-success success">
			<th>Course Id</th>
			<th>Course Name</th>
			<th>Registration Last Dates</th>
			<th>Exam Dates</th>
			<th>Exam Timings</th>
			<th>Exam Durations</th>
		</tr>
			<?php
				while ($rec=mysqli_fetch_array($result)) 
				{
					
			?>
			<tr class="btech">
				<th><?php echo $rec['course_id']; ?></th>
				<th><?php echo $rec['course_name']; ?></th>
				<th><?php echo $rec['regis_last_date']; ?></th>
				<th><?php echo $rec['exam_date']; ?></th>
				<th><?php echo $rec['exam_time']; ?></th>
				<th><?php echo $rec['exam_duration']; ?></th>
			</tr>	
			<?php

				}
			?>
	</tbody>
	

</table>
</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php
	include_once 'config.php';

	if(isset($_GET['course_id']) && isset($_GET['course_status']))
	{
		$course_id 		= $_GET['course_id'];
		$course_status 	= $_GET['course_status'];
		if($course_status == 0)
			$course_status = 1;
		else
			$course_status = 0;

		$sql = "UPDATE course SET course_status = '$course_status' WHERE course_id = '$course_id'";
		mysqli_query($con, $sql);
		if(mysqli_affected_rows($con) > 0 )
			setcookie('error',"<h4 class='alert alert-success text-center'>Course status changed successfully.</h4>",time()+3);
		else
			setcookie('error',"<h4 class='alert alert-danger text-center'>Course status not changed successfully.</h4>",time()+3);

		header('Location:view-course');
	}
	elseif(isset($_GET['center_id']) && isset($_GET['center_status']))
	{
		$center_id 		= $_GET['center_id'];
		$center_status 	= $_GET['center_status'];
		if($center_status == 0)
			$center_status = 1;
		else
			$center_status = 0;

		$sql = "UPDATE center SET center_status = '$center_status' WHERE center_id = '$center_id'";
		mysqli_query($con, $sql);
		if(mysqli_affected_rows($con) > 0 )
			setcookie('error',"<h4 class='alert alert-success text-center'>Center status changed successfully.</h4>",time()+3);
		else
			setcookie('error',"<h4 class='alert alert-danger text-center'>Center status not changed successfully.</h4>",time()+3);
		
		header('Location:view-center');
	}

 ?>
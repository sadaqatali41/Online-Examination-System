<?php
session_start();
$qid=$_REQUEST['id'];
$ans=$_GET['ans'];
$stuid=$_SESSION['studentid'];
$con=mysqli_connect("localhost","root","","online_examination_system");
mysqli_query($con,"update exam set chooseoption='$ans' where Ques_id=$qid and stuid='$stuid'");
if(mysqli_affected_rows($con)>0)
{
	echo "<p>Records Updated Successfully</p>";
}
else
{
	echo "<p>Sorry! Unable to updated the records.</p>";
	echo mysqli_error($con);
}

?>
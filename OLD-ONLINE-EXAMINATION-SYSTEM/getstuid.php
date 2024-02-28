<?php
$id=$_POST['stuid'];
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select stuid from students where stuid='$id'";
$result=mysqli_query($con,$query);
$num=mysqli_num_rows($result);
mysqli_close($con);
if($num==1) 
{
	echo "Student Id Already Exists!";
}
else
	echo $id;

?>

 
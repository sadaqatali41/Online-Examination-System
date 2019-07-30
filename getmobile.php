<?php
$mobile=$_POST['number'];
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select phone from students where phone='$mobile'";
$result=mysqli_query($con,$query);
$num=mysqli_num_rows($result);
mysqli_close($con);
if($num==1) 
{
	echo "Mobile Number Already Exists";
}
else
{
	echo $mobile;
}
 
?>
<?php
$email=$_POST['email'];
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select email from students where email='$email'";
$result=mysqli_query($con,$query);
$num=mysqli_num_rows($result);
mysqli_close($con);
if($num==1) 
{
	echo "This Email Id is Already Exists";
}
else
	echo $email;
 
?>
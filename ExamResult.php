<?php
include 'header.php';
include 'menubar.php';
session_start();
if(!isset($_SESSION['gender']))
{
	header('location:http://localhost/OES_BS/login.php');
}

$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select course_id,correct_option FROM questions WHERE course_id=$_SESSION[c_id]";
$result=mysqli_query($con,$query);
$row=mysqli_num_rows($result);
$i=1;
$correct=0;
$incorrect=0;
$unAttemp=0;

while($i<=$row) 
{
	if(isset($_POST['ques'.$i])) 
	{
		$ans=mysqli_fetch_array($result);
		if($_POST['ques'.$i]==$ans['correct_option'])
		{
			$correct++;
		}
		else
		{
			$incorrect++;
		}
	}
	else
	{
		$unAttemp++;
	}
	$i++;
}
$percentage=($correct*100)/$row;
if($percentage<50)
{
	$status="Waiting";
}
else
{
	$status="Selected";
}

$query1="insert into results (student_id,fname,lname,phone,email,gender,course,dob,center,no_of_questions,marks,percentage,
status) values($_SESSION[studentid],'$_SESSION[fname]','$_SESSION[lname]','$_SESSION[phone]','$_SESSION[email]',
'$_SESSION[gender]','$_SESSION[course]','$_SESSION[dob]','$_SESSION[center]',$row,$correct,$percentage,'$status')";
$execute=mysqli_query($con,$query1);
mysqli_close($con);
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/welcometoexam.css">-->
<style type="text/css">
div#ExamResult
{
	width: 60%;
	margin: auto;
}
table{
	background-color: cyan;
}
th.result{
	font-size: 20px;
	color: red;
	font-family: Arial;
	font-style: italic;
	background-color: linen;
}
</style>

</head>
<body>
<div class="container">
	<div id="ExamResult">
		<p class="text-center" style="font-size: 20px;font-weight: bold;color: red;">
			Hello.. <?php echo ucwords("$_SESSION[fname] $_SESSION[lname]");  ?></p>
	<table class="table table-bordered">
		<thead>
			<tr class="success">
				<th colspan="4" class="result text-center text-primary">Student Result</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>First Name:</th>
				<td><?php echo ucfirst("$_SESSION[fname]");  ?></td>
				<th>Last Name:</th>
				<td><?php echo ucfirst("$_SESSION[lname]");  ?></td>
			</tr>
			<tr>
				<th>Course Name:</th>
				<td><?php echo $_SESSION['course'];  ?></td>
				<th>Gender:</th>
				<td><?php echo $_SESSION['gender'];  ?></td>
			</tr>
			<tr>
				<th>Attemped Question(s):</th>
				<th><?php echo $correct+$incorrect;  ?></th>
				<th>Unattemped Question(s):</th>
				<th><?php echo $unAttemp;  ?></th>
			</tr>
			<tr>
				<th>Correct Answer(s):</th>
				<th><?php echo $correct; ?></th>
				<th>Incorrect Answer:</th>
				<th><?php echo $incorrect; ?></th>
			</tr>
			<tr>
				<th colspan="2">Total Marks:</th>
				<th colspan="2"><?php echo $correct; ?></th>
			</tr>
		</tbody>
	</table>
</div>
</div>

</body>
</html>
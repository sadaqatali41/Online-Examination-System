<?php 
session_start();
include 'header.php';
if(!isset($_SESSION['admitcard']))
{
	header('location:http://localhost/OES_BS/student-course-login');
	exit(0);
}

require ('vendor/autoload.php');
$barcode = new Com\Tecnick\Barcode\Barcode();
$targetPath = "qr-code/";

$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select * from results where student_id=".$_SESSION['studentid'];
$result=mysqli_query($con,$query);
$row=mysqli_num_rows($result);
if($row==1)
{
	$rec=mysqli_fetch_array($result);
	// QR generation code
$data = '';
$data .= "Regis No.: ".substr($rec['phone'].$_SESSION['studentid'],5,10)."\n";
$data .= "Name: ".ucfirst($_SESSION['fname'])." ".ucfirst($_SESSION['lname'])."\n";
$data .= "DOB(yyyy-mm-dd): ".$rec['dob']."\n";
$data .= "Mobile Number: ".$rec['phone']."\n";
$data .= "Email Address: ".$rec['email']."\n";
$data .= "Course: ".$_SESSION['course'].", Center Name: ".$_SESSION['center']."\n";
$data .= "Exam Status: ".$rec['status']."\n";
    
if (! is_dir($targetPath)) 
{
    mkdir($targetPath, 0777, true);
}
$bobj = $barcode->getBarcodeObj('QRCODE,H', $data, - 16, - 16, 'black', array(
        - 2,
        - 2,
        - 2,
        - 2
    ))->setBackgroundColor('#f0f0f0');
    
    $imageData = $bobj->getPngData();
    $timestamp = time();
    
    file_put_contents($targetPath . $timestamp . '.png', $imageData);
?>
<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/css" href="CSS/FinalResult.css">-->
	<style type="text/css">
		div#FinalResult{
	width: 750px;
	height: auto;
	margin: auto;
	padding: 5px;
}
	</style>
	<script type="text/javascript">
		function AdmitCard() 
		{
			
			document.getElementsByClassName('row')[0].style.display="none";
			document.getElementById('last_row').style.display="none";
			document.getElementsByTagName('footer')[0].style.display="none";
			document.getElementsByTagName('nav')[0].style.display="none";
			window.print();
		}
	</script>
</head>
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
					<?php if(isset($_SESSION['successPG'])): ?>
					<li><a href="pg-dashboard">
						<span class="glyphicon glyphicon-home"></span> Home</a></li>
					<?php else: ?>
					<li><a href="ug-dashboard">
						<span class="glyphicon glyphicon-home"></span> Home</a></li>
					<?php endif; ?>
					<li><a href="print-admit-card"><span class="glyphicon glyphicon-print"></span> Download Admit Card</a></li>
					<li><a href="final-result">Check Your Result</a></li>
					<?php if(isset($_SESSION['successPG'])): ?>
					<li><a href="student-pg-info">Check Your Details</a></li>
					<?php else: ?>
					<li><a href="student-ug-info">Check Your Details</a></li>
					<?php endif; ?>
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
		<div id="FinalResult">
	<table class="table table-bordered table-striped">
	
	<thead>
		<tr class="info">
			<th colspan="4" style="font-size: 18px;" class="text-center">
					ONLINE EXAMINATION SYSTEM
			</th>
		</tr>
		<tr>
			<td colspan="3">
				<img src="images/manuulogo.jpg" id="home" class="img-rounded" />
			</td>
			<!-- <th><span style="font-size: 18px;font-style: italic;">Serial No.</span> <?php echo substr($rec['phone'].$_SESSION['studentid'],5,10); ?></th> -->
			<td>
				<img src="<?php echo $targetPath . $timestamp ; ?>.png" width="150px"height="150px">
			</td>
		</tr>
		<tr class="warning">
			<th colspan="3" style="font-size: 18px;" class="text-center">Student Final Result</th>
			<th><span style="font-size: 18px;font-style: italic;">R.No.</span> <?php echo substr($rec['phone'].$_SESSION['studentid'],5,10); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>First Name :</th>
			<td><?php echo ucfirst("$rec[fname]");  ?></td>
			<th>Last Name :</th>
			<td><?php echo ucfirst("$rec[lname]");  ?></td>
		</tr>
		<tr>
			<th>Gender :</th>
			<td><?php echo $rec['gender'];  ?></td>
			<th>Course Name :</th>
			<td><?php echo $rec['course'];  ?></td>
		</tr>
		<tr>
			<th>Mobile Number :</th>
			<td><?php echo $rec['phone'];  ?></td>
			<th>Email Address :</th>
			<td><?php echo $rec['email'];  ?></td>
		</tr>
		<tr>
			<th>Date of Birth :</th>
			<td><?php echo $rec['dob'];  ?></td>
			<th>Exam Center :</th>
			<td><?php echo $rec['center'];  ?></td>
		</tr>
		<tr>
			<th>No. of Questions :</th>
			<th><?php echo $rec['no_of_questions'];  ?></th>
			<th>Total Marks :</th>
			<th><?php echo $rec['marks'];  ?></th>
		</tr>
		<tr>
			<th>Marks % :</th>
			<th><?php echo $rec['percentage']." %";  ?></th>
			<th>Exam Status :</th>
			<th><?php echo $rec['status'];  ?></th>
		</tr>
		<tr>
			<td colspan="4">
				<img src="images/result.jpg" style="height: 125px;width: 100%;border: 1px solid blue;" class="img-rounded">
			</td>
		</tr>
		<tr>
			<th colspan="4" style="text-align: left;">
				<p>1. Bring This Result During Counselling.</p>
				<p>2. You are not Permitted without this Result sheet.</p>
				<p>3. Come with 7 Passport size photo and Original Documents.</p>
				<p>4. Without Original Documents, you will not get the admission.</p>
			</th>
		</tr>
		<tr id="last_row">
			<th colspan="4" class="text-center">
				<button type="button" name="AdmitCard" onclick="AdmitCard()" id="admit" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Print</button>
			</th>
		</tr>
	</tbody>
</table>
</div>
</div>	
<?php

}
else	
{

?>
 
<div class="alert alert-danger">
	<h2>Sorry! Result not Found.</h2>
</div>
<?php
}
 include 'footer.php';
?>
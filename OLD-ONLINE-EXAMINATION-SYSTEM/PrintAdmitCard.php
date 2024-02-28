<?php
session_start();
include 'header.php';
if(!isset($_SESSION['admitcard']))
{
	header('location:http://localhost/OES_BS/student-course-login');
}
else
{

require ('vendor/autoload.php');
$barcode = new Com\Tecnick\Barcode\Barcode();
$targetPath = "qr-code/";

$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
			$q="select s.email,s.gender,s.phone,c.address,c.center_code,s.course,s.dob from students s,center c where s.stuid='$_SESSION[studentid]'
					 && c.name='$_SESSION[center]'";
$result=mysqli_query($con,$q);
$rec=mysqli_fetch_array($result);

$query="select exam_date,exam_time from examschedule where course_name='$_SESSION[course]'";
$rs=mysqli_query($con,$query);
mysqli_close($con);
$row=mysqli_fetch_array($rs);

// QR generation code
$data = '';
$data .= "Regis No.: ".substr($rec['phone'].$_SESSION['studentid'],5,10)."\n";
$data .= "Name: ".ucfirst($_SESSION['fname'])." ".ucfirst($_SESSION['lname'])."\n";
$data .= "DOB(yyyy-mm-dd): ".$rec['dob']."\n";
$data .= "Mobile Number: ".$rec['phone']."\n";
$data .= "Email Address: ".$rec['email']."\n";
$data .= "Course: ".$_SESSION['course'].", Center Name: ".$_SESSION['center']."\n";
$data .= "Exam Date: ".$row['exam_date'].", Exam Time: ".$row['exam_time']."\n";
    
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
	<!--<link rel="stylesheet" type="text/css" href="CSS/PrintAdmitCard.css">-->
	<style type="text/css">
		div.admit_card{
						width: 750px;
						height: auto;
						margin: auto;
						padding: 4px;
					}
					th > div#address{
						height: 150px;
						max-width: 200px;
						line-height: 20px;
					}
					td > div#photo{
						height: 150px;
						width: 165px;
						line-height: 150px;
						text-align: center;
					}
					td > div#sign{
						height: 150px;
						width: 150px;
						text-align: center;
						line-height: 150px;	
					}
					th#inclusion{
						text-align: left;
						line-height: 25px;
					}
					td#instructions > ol > li{
						line-height: 30px;
					}
	</style>
	<script type="text/javascript">
		function AdmitCard() 
		{
			
			document.getElementById('last_row').style.display="none";
			document.getElementsByClassName('row')[0].style.display="none";
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
			<div class="admit_card">
	<table class="table table-bordered table-striped">
	
	<thead>
		<tr class="info">
			<th colspan="4" style="font-size: 25px;" class="text-center">
					ONLINE EXAMINATION SYSTEM
			</th>
		</tr>
		<tr>
			<td colspan="3">
				<img src="images/manuulogo.jpg" class="img-rounded" />
			</td>
			<!-- <th><span style="font-size: 18px;font-style: italic;">Serial No.</span> <?php echo substr($rec['phone'].$_SESSION['studentid'],5,10); ?></th> -->
			<td>
				<img src="<?php echo $targetPath . $timestamp ; ?>.png" width="150px"height="150px">
			</td>
		</tr>
		<tr class="success">
			<th style="font-size: 18px;" class="text-center">Center Code: <?php echo $rec['center_code']; ?></th>
			<th colspan="2" style="font-size: 18px;" class="text-center">Student Admit Card</th>
			<th><span style="font-size: 18px;font-style: italic;">R.No.</span> <?php echo substr($rec['phone'].$_SESSION['studentid'],5,10); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>First Name :</th>
			<td><?php echo ucfirst("$_SESSION[fname]");  ?></td>
			<th>Last Name :</th>
			<td><?php echo ucfirst("$_SESSION[lname]");  ?></td>
		</tr>
		<tr>
			<th>Gender :</th>
			<td><?php echo $rec['gender'];  ?></td>
			<th>Course Name :</th>
			<td><?php echo $_SESSION['course'];  ?></td>
		</tr>
		<tr>
			<th>Mobile Number :</th>
			<td><?php echo $rec['phone'];  ?></td>
			<th>Email Address :</th>
			<td><?php echo $rec['email'];  ?></td>
		</tr>
		<tr>
			<th>Center Name :</th>
			<td><?php echo $_SESSION['center'];  ?></td>
			<th>Valid For :</th>
			<td>Field of Study as Under</td>
		</tr>
		<tr>
			<th>Exam Date :</th>
			<th><?php echo $row['exam_date'];  ?></th>
			<th>Exam Time :</th>
			<th><?php echo $row['exam_time'];  ?></th>
		</tr>
		<tr>
			<th style="max-width: 200px;">
				<div id="address">
					<span style="text-decoration: underline;color: green;">Allocated Exam Center:-</span><br><br>
					<?php  echo $rec['address'];  ?>
				</div>
			</th>
			<td>
				<div id="photo">Stick Your Photo</div>
			</td>
			<td>
				<div id="sign">Student Signature</div>
			</td>
			<td>
				<div id="exam_coordinator_sign"><img src="images/admitcard.jpg" style="height: 150px;width: 175px;border: 1px solid blue;" class="img-rounded"></div>
			</td>
		</tr>
		<tr>
			<th colspan="4" id="inclusion">
				Candidate having valid Admit Card of the allotted Examination Centre only is permitted to undertake the examination.<br>
				Note : Please bring alongwith you the following<br>
				(a) A recent passport size photograph for Exam.<br>
				(b) Origional Photo ID Proof (Aadhar Card, Driving License, Voter ID Card, Passport, Institution ID Card or Pan Card).
			</th>
		</tr>
		<tr class="danger">
			<th colspan="4" style="font-size: 18px;" class="text-center">INSTRUCTIONS TO THE CANDIDATE</th>
		</tr>
		<tr>
			<td colspan="4" id="instructions">
				<ol type="1">
					<li>Please report 15 minutes in advance before the commencement of the examination at the alloted Examination Centre.</li>
					<li>Candidates will not be permitted to enter the examination centre after 30 minutes of the commencement of the examination and will not be permitted to leave the Examination Hall before the end of the examination.
					</li>
					<li>Online Calculator is Available For Calculations.</li>
					<li>Communication devices like Cellphones are not allowed inside the examination hall.</li>
				</ol>
			</td>
		</tr>
		<tr id="last_row">
			<th class="text-center" colspan="4">
				<button type="button" name="AdmitCard" onclick="AdmitCard()" id="admit" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Print</button>
			</th>
		</tr>
	</tbody>
</table>
</div>
</div>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php

}
?>
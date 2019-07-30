<?php
session_start();
include 'header.php';
if(!isset($_SESSION['successPG']))
{
	header('location:http://localhost/OES_BS/student-sign-out');
	exit();
}

$con=mysqli_connect("localhost","root","","online_examination_system");
$res=mysqli_query($con,"select * from students where stuid='$_SESSION[studentid]'");
$res1=mysqli_query($con,"select * from highschool where student_id='$_SESSION[studentid]'");
$res2=mysqli_query($con,"select * from intermediate where student_id='$_SESSION[studentid]'");
$res3=mysqli_query($con,"select * from graduation where student_id='$_SESSION[studentid]'");
mysqli_close($con);
$rec=mysqli_fetch_array($res);
$rec1=mysqli_fetch_array($res1);
$rec2=mysqli_fetch_array($res2);
$rec3=mysqli_fetch_array($res3);

?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		div#info{
			width: 750px;
			margin: auto;
			padding: 4px;
		}
	</style>
	<script type="text/javascript">
		function AdmitCard() 
		{
			document.getElementsByClassName('row')[0].style.display="none";
			document.getElementById('last_row').style.display="none";
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
					<li class="active"><a href="pg-dashboard">
						<span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="print-admit-card"><span class="glyphicon glyphicon-print"></span> Download Admit Card</a></li>
					<li><a href="final-result">Check Your Result</a></li>
					<li><a href="student-pg-info">Check Your Details</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<p class="navbar-text" style="color: pink;font-weight: bold;font-style: italic;">Hello.. 
						<?php echo ucwords("$_SESSION[fname] $_SESSION[lname]");  ?></p>
					<li><a href="student-sign-out"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a></li>
				</ul>
			</div>
		</nav>
	</div>
<div id="info">
	<table class="table table-striped table-bordered">
		<tr class="success">
			<th colspan="6" style="font-size: 18px;text-shadow: 2px 2px pink;" class="text-center">
				ONLINE EXAMINATION SYSTEM</th>
		</tr>
		<tr>
			<td colspan="4">
				<img src="images/manuulogo.jpg" id="home" class="img-rounded" />
			</td>
			<th colspan="2"><span style="font-size: 18px;font-style: italic;">Serial No.</span> <?php echo substr($rec['phone'].$_SESSION['studentid'],5,10); ?></th>
		</tr>
		<tr>
			<th colspan="6" style="font-size: 20px;font-style: italic;" class="text-center text-primary">Personal Details</th>
		</tr>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Phone Number</th>
			<th>Gender</th>
			<th>Email Address</th>
			<th>Course Name</th>
		</tr>
		<tr>
			<td><?php echo ucfirst($rec['fname']); ?></td>
			<td><?php echo ucfirst($rec['lname']); ?></td>
			<td><?php echo $rec['phone']; ?></td>
			<td><?php echo $rec['gender']; ?></td>
			<td><?php echo $rec['email']; ?></td>
			<td><?php echo $rec['course']; ?></td>
		</tr>
		<tr>
			<th>Center Name</th>
			<th>Country Name</th>
			<th>State Name</th>
			<th>District Name</th>
			<th>Permanent Address</th>
			<th>D.O.B</th>
		</tr>
		<tr>
			<td><?php echo $rec['center']; ?></td>
			<td><?php echo $rec['country']; ?></td>
			<td><?php echo $rec['state']; ?></td>
			<td><?php echo $rec['district']; ?></td>
			<td><?php echo $rec['address']; ?></td>
			<td><?php echo $rec['dob']; ?></td>
		</tr>
		<tr class="warning">
			<th colspan="6" style="font-size: 20px;font-style: italic;" class="text-center text-success">Educational Qualifications</th>
		</tr>
		<tr>
			<th>Course Name</th>
			<th>Roll Number</th>
			<th>College Name/Institution Name</th>
			<th>Board Name/Branch</th>
			<th>Year of Passing</th>
			<th>Percentage</th>
		</tr>
		<tr>
			<td><?php echo "X"; ?></td>
			<td><?php echo $rec1['roll_no']; ?></td>
			<td><?php echo $rec1['college_name']; ?></td>
			<td><?php echo $rec1['borad_name']; ?></td>
			<td><?php echo $rec1['yop']; ?></td>
			<td><?php echo $rec1['percent']; ?></td>
		</tr>
		<tr>
			<td><?php echo "XII"; ?></td>
			<td><?php echo $rec2['roll_no']; ?></td>
			<td><?php echo $rec2['college_name']; ?></td>
			<td><?php echo $rec2['borad_name']; ?></td>
			<td><?php echo $rec2['yop']; ?></td>
			<td><?php echo $rec2['percent']; ?></td>
		</tr>
		<tr>
			<td><?php echo $rec3['course_name']; ?></td>
			<td><?php echo $rec3['enroll_no']; ?></td>
			<td><?php echo $rec3['institute_name']; ?></td>
			<td><?php echo $rec3['branch_name']; ?></td>
			<td><?php echo $rec3['yop']; ?></td>
			<td><?php echo $rec3['aggregate_percent']; ?></td>
		</tr>
		<tr id="last_row">
			<th colspan="6" class="text-center">
				<button type="button" name="AdmitCard" onclick="AdmitCard()" id="admit" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Print</button></th>
		</tr>
	</table>

</div>
<?php include 'footer.php'; ?>
</body>
</html>
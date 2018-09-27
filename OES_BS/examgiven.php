<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid']))
{
	header('location:http://localhost/OES_BS/adminlogin.php');
	exit(0);
}

$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select * from alreadyloggedin";
$result=mysqli_query($con,$query);
$num=mysqli_num_rows($result);
mysqli_close($con);

?>

<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/css" href="CSS/viewrecords.css">-->
</head>
<body>
	<div class="container-fluid">
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr class="success">
						<th colspan="14" class="text-center text-primary"><h2>Students Already Logged Records</h2></th>
					</tr>
					<tr class="danger text-success">
						<th>First Name</th>
						<th>Last Name</th>
						<th>Course</th>
						<th>Center</th>
						<th>Student Id</th>
						<th>Mobile Number</th>
						<th>Email Address</th>
						<th>Country</th>
						<th>State</th>
						<th>District</th>
						<th>Address</th>
						<th>Gender</th>
						<th>DOB</th>
						<th>Date & Time</th>
					</tr>
				</thead>
				<tbody>
					<?php
						for($i=1;$i<=$num;$i++)
						{ 
							$rows=mysqli_fetch_array($result);

					?>
					
						<tr>
							<td><?php echo $rows['fname'];  ?></td>
							<td><?php echo $rows['lname'];  ?></td>
							<td><?php echo $rows['course'];  ?></td>
							<td><?php echo $rows['center'];  ?></td>
							<td><?php echo $rows['stuid'];  ?></td>
							<td><?php echo $rows['phone'];  ?></td>
							<td><?php echo $rows['email'];  ?></td>
							<td><?php echo $rows['country'];  ?></td>
							<td><?php echo $rows['state'];  ?></td>
							<td><?php echo $rows['district'];  ?></td>
							<td><?php echo $rows['address'];  ?></td>
							<td><?php echo $rows['gender'];  ?></td>
							<td><?php echo $rows['dob'];  ?></td>
							<td><?php echo $rows['datetime'];  ?></td>
						</tr>


					<?php		

						}
					?>
					<tr>
						<th colspan="14">
							<a href="welcomadmin.php" class="btn btn-info btn-block">&#8810; Go Back</a>
						</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
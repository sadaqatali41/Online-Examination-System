<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid'])) 
{
	header('location:http://localhost/OES_BS/admin-login');
}
date_default_timezone_set('Asia/Kolkata');

$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select * from students";
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
		<div id="viewrecords" class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr class="success">
						<th colspan="15" class="text-center text-success"><h2>Students Records</h2></th>
					</tr>
					<tr class="info text-muted">
						<th>First Name</th>
						<th>Last Name</th>
						<th>Course</th>
						<th>Center</th>
						<th>Student Id</th>
						<th>Mobile Number</th>
						<th>Password</th>
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
							<td><?php echo $rows['password'];  ?></td>
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
						<th colspan="15">
							<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
						</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
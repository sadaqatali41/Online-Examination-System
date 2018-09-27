<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid'])) 
{
	header('location:http://localhost/OES_BS/adminlogin.php');
	exit();
}
$con=mysqli_connect("localhost","root","","online_examination_system");
$res=mysqli_query($con,"select * from results where status='Selected'");
$res1=mysqli_query($con,"select * from results where status='Waiting'");
mysqli_close($con);

?>

<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
	function AdmitCard() 
	{
		document.getElementById('admit').style.display="none";
		document.getElementsByClassName('row')[0].style.display="none";
		document.getElementById('goback').style.display="none";
		window.print();
	}
</script>
	<style type="text/css">
		div#records{
			width: 85%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="records" class="table-responsive">
			<?php
			if(mysqli_num_rows($res)>0)
			{

				?>
					<table class="table table-bordered">
						<tr class="info">
							<th colspan="10" class="text-center text-success" style="font-size: 18px;">Selected Students List</th>
						</tr>
						<tr class="success text-warning">
							<th>Student ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Phone Numner</th>
							<th>Email ID</th>
							<th>Gender</th>
							<th>Course</th>
							<th>DOB</th>
							<th>Center</th>
							<th>Status</th>
						</tr>
						<?php
							while($row=mysqli_fetch_array($res)) 
							{
									?>
								<tr>
									<td><?php echo $row['student_id']; ?></td>
									<td><?php echo ucfirst($row['fname']); ?></td>
									<td><?php echo ucfirst($row['lname']); ?></td>
									<td><?php echo $row['phone']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['gender']; ?></td>
									<td><?php echo $row['course']; ?></td>
									<td><?php echo $row['dob']; ?></td>
									<td><?php echo $row['center']; ?></td>
									<td><?php echo $row['status']; ?></td>
								</tr>
								<?php
							}

							?>
					</table>

				<?php
			}
			else
			{
				echo "<p class='alert alert-warning text-center'><strong>Sorry!.. No Records Found for Selected Students.
				</strong></p>";
			}

			if(mysqli_num_rows($res1)>0)
			{
				?>
			<table class="table table-bordered">
				<tr class="success">
						<th colspan="10" class="text-center text-primary" style="font-size: 18px;">Waiting Students List</th>
				</tr>
				<tr class="danger text-muted">
						<th>Student ID</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Phone Numner</th>
						<th>Email ID</th>
						<th>Gender</th>
						<th>Course</th>
						<th>DOB</th>
						<th>Center</th>
						<th>Status</th>
				</tr>
					<?php

						while($rec=mysqli_fetch_array($res1))
						{
					?>
				<tr>
						<td><?php echo $rec['student_id']; ?></td>
						<td><?php echo ucfirst($rec['fname']); ?></td>
						<td><?php echo ucfirst($rec['lname']); ?></td>
						<td><?php echo $rec['phone']; ?></td>
						<td><?php echo $rec['email']; ?></td>
						<td><?php echo $rec['gender']; ?></td>
						<td><?php echo $rec['course']; ?></td>
						<td><?php echo $rec['dob']; ?></td>
						<td><?php echo $rec['center']; ?></td>
						<td><?php echo $rec['status']; ?></td>
				</tr>

				<?php
						}

				?>

				<?php
			}
			else
			{
			?>
				<tr>
					<th colspan="10">
						<?php echo "<p class='alert alert-warning text-center'><strong>Sorry!.. No Records Found for Waited Students.</strong></p>";?>
								
					</th>
				</tr>
				<?php
			}

			?>	
			</table>
		</div>
		<div class="row">
				<div class="col-sm-5 col-sm-offset-1">
					<a href="welcomadmin.php" id="goback" class="btn btn-info btn-block">&#8810; Go Back</a>
				</div>
				<div class="col-sm-5 col-sm-offset+1">
					<button type="button" onclick="AdmitCard()" id="admit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-print"></span> Print</button>
				</div>
			</div>
	</div><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
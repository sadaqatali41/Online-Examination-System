<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid']))
{
	header('location:http://localhost/OES_BS/admin-login');
}

$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="SELECT * FROM course";
$result=mysqli_query($con,$query);	//Associative Array 
$num=mysqli_num_rows($result);
mysqli_close($con);

?>

<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/css" href="CSS/viewrecords.css">-->
	<style type="text/css">
		div#veiwcourse
		{
			width: 70%;
			margin: auto;
		}
		th,td{
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="veiwcourse" class="table-responsive">
			<?php if(isset($_COOKIE['error']))
				echo $_COOKIE['error'];
			?>
		<table class="table table-bordered">
			<thead>
				<tr class="success">
					<th colspan="5"><h2 class="text-center text-primary">Courses</h2></th>
				</tr>
				<tr class="active text-success">
					<th>Serial No.</th>
					<th>Course Name</th>
					<th>Created Datetime</th>
					<?php if($_SESSION['role'] == 'admin'): ?>
					<th>Course Status</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php
					$i=1;
					$counter = 1;
					while($i<=$num)
					{ 
						$rows=mysqli_fetch_array($result);

				?>
				
					<tr>
						<td><?php echo $counter++;  ?></td>
						<td><?php echo $rows['name'];  ?></td>
						<td><?php echo $rows['datetime'];  ?></td>
						<?php if($_SESSION['role'] == 'admin'): ?>
							<?php if($rows['course_status'] == 0): ?>
								<td>
									<a href="admin_actions.php?course_id=<?php echo $rows['course_id']?>&course_status=<?php echo $rows['course_status'] ?>" class="btn btn-danger btn-sm" onclick="return change()">Inactive</a>
								</td>
							<?php else: ?>
								<td>
									<a href="admin_actions.php?course_id=<?php echo $rows['course_id']?>&course_status=<?php echo $rows['course_status']?>" class="btn btn-success btn-sm" onclick="return change()">Active</a>
								</td>
							<?php endif; ?>
						<?php endif; ?>
					</tr>


				<?php
					$i++;		

					}
				?>
				<tr>
					<th colspan="5">
						<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
					</th>
				</tr>
			</tbody>
		</table>
		</div>
	</div><br><br><br><br>
	<?php include 'footer.php'; ?>
	<script type="text/javascript">
		function change()
		{
			if(confirm('Do you want to change the status of the course?'))
				return true;
			else
				return false;
		}
	</script>
</body>
</html>
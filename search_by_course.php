<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid']))
{
	header('location:http://localhost/OES_BS/admin-login');
	exit(0);
}
$con=mysqli_connect("localhost","root","","online_examination_system");
$result=mysqli_query($con,"select name from course");
$res=mysqli_query($con,"select name from center");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		div#search{
			width: 50%;
			margin: auto;
		}
		th,td
		{
			text-align: center;
		}
	</style>
	<script type="text/javascript">
		function validateRefid()
		{
			var c=document.getElementById('reg_id').value;
			if(c=="")
			{
				alert('Please Enter a Valid Registered ID !');
				document.getElementById('reg_id').focus();
				return false;
			}
		}
	</script>
</head>
<body>
	<div id="search">
		<form class="form-horizontal" action="" method="post">
			<div class="text-center text-primary bg-success">
				<h4>Search Registered Students By the Courses, Centers and Registered ID</h4>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4">Search Students By:</label>
				<div class="col-sm-6">
					<select name="searchcourse" id="course" class="form-control">
							<option value="">--Select Course--</option>
							<?php
								if(mysqli_num_rows($result)>0)
								{
									while($row=mysqli_fetch_array($result)) 
									{
										?>
										<option <?php if(isset($_POST['searchcourse'])){if($row['name']==$_POST['searchcourse']) echo "selected";} ?> value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?>
											</option>
										<?php
									}
								}
								else
								{
									echo "<p class='alert alert-danger text-center'><strong>Sorry! No Course Found.</strong></p>";
								}

							?>
						</select>
				</div>
				<div class="col-sm-2">
					<button type="submit" name="search" class="btn btn-success">
						<span class="glyphicon glyphicon-search"></span> Search
					</button>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4">Search Students By:</label>
				<div class="col-sm-6">
					<select name="center" id="center" class="form-control">
							<option value="">--Select Center--</option>
							<?php 
								if(mysqli_num_rows($res)>0)
								{
									while($reco=mysqli_fetch_array($res))
									{
										?>
											<option <?php if(isset($_POST['center'])){if($reco['name']==$_POST['center']) echo "selected";} ?> value="<?php echo $reco['name']; ?>"><?php echo $reco['name']; ?>
												
											</option>
										<?php
									}
								}
								else
								{
									echo "<p class='alert alert-danger text-center'><strong>Sorry! No Center Found.</strong></p>";
								}
							?>
							
						</select>
				</div>
				<div class="col-sm-2">
					<button type="submit" name="searchcenter" class="btn btn-success">
						<span class="glyphicon glyphicon-search"></span> Search
					</button>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4">Search Students By:</label>
				<div class="col-sm-6">
					<input type="number" name="reg_id" id="reg_id" placeholder="Registered ID" class="form-control">
				</div>
				<div class="col-sm-2">
					<button type="submit" name="search_by_id" onclick="return validateRefid()" class="btn btn-success">
						<span class="glyphicon glyphicon-search"></span> Search
					</button>
				</div>
			</div>
		</form>
		<?php
			if(isset($_POST['search']))
			{
				$course=$_POST['searchcourse'];
				$res=mysqli_query($con,"select fname,lname,course,phone,email,gender from students where course='$course'");
				if(mysqli_num_rows($res)>0)
				{
					?>
					<div class="table-responsive">
						<table class="table table-bordered">
						<tr class="success text-primary">
							<th>First Name</th>
							<th>Last Name</th>
							<th><span style="color: red;font-size: 18px;">Course</span></th>
							<th>Phone Number</th>
							<th>Email Address</th>
							<th>Gender</th>
						</tr>
						<?php
							while($rec=mysqli_fetch_array($res))
							{
								?>
								<tr>
									<td><?php echo ucfirst($rec['fname']); ?></td>
									<td><?php echo ucfirst($rec['lname']); ?></td>
									<td style="color: blue;font-weight: bold;"><?php echo $rec['course']; ?></td>
									<td><?php echo $rec['phone']; ?></td>
									<td><?php echo $rec['email']; ?></td>
									<td><?php echo $rec['gender']; ?></td>
								</tr>
								<?php
							}

						?>
						<tr>
							<th colspan="6">
								<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
							</th>
						</tr>
					</table>
					</div>
					<?php
				}
				else
				{
					echo "<p class='alert alert-danger text-center'><strong>Sorry! No Records Found.</strong></p>";
				}
			}

		?>
		<?php
			if(isset($_POST['searchcenter']))
			{
				$center=$_POST['center'];
				$res1=mysqli_query($con,"select fname,lname,center,phone,email,gender from students where center='$center'");
				if(mysqli_num_rows($res1)>0)
				{
					?>
					<div class="table-responsive">
						<table class="table table-bordered">
						<tr class="info text-success">
							<th>First Name</th>
							<th>Last Name</th>
							<th><span style="color: red;font-size: 18px;">Center</span></th>
							<th>Phone Number</th>
							<th>Email Address</th>
							<th>Gender</th>
						</tr>
						<?php
							while($rec1=mysqli_fetch_array($res1))
							{
								?>
								<tr>
									<td><?php echo ucfirst($rec1['fname']); ?></td>
									<td><?php echo ucfirst($rec1['lname']); ?></td>
									<td style="color: blue;font-weight: bold;"><?php echo $rec1['center']; ?></td>
									<td><?php echo $rec1['phone']; ?></td>
									<td><?php echo $rec1['email']; ?></td>
									<td><?php echo $rec1['gender']; ?></td>
								</tr>
								<?php
							}

						?>
						<tr>
							<th colspan="6">
								<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
							</th>
						</tr>
						</table>
					</div>
					<?php
				}
				else
				{
					echo "<p class='alert alert-danger text-center'><strong>Sorry! No Records Found.</strong></p>";
				}
			}
		?>
		<?php
			if(isset($_POST['search_by_id']))
			{
				$reg_id=$_POST['reg_id'];
				$resid=mysqli_query($con,"select * from students where reg_id=$reg_id");
				echo mysqli_error($con);
				if(mysqli_num_rows($resid)>0)
				{
					$resrec=mysqli_fetch_array($resid);
					?>
						<div class="table-responsive">
							<table class="table table-bordered">
							<tr class="info text-warning">
								<th>First Name</th>
								<th>Last Name</th>
								<th>Course</th>
								<th>Phone Number</th>
								<th>Email Address</th>
								<th>Gender</th>
							</tr>
							<tr>
								<td><?php echo ucfirst($resrec['fname']); ?></td>
								<td><?php echo ucfirst($resrec['lname']); ?></td>
								<td><?php echo $resrec['course']; ?></td>
								<td><?php echo $resrec['phone']; ?></td>
								<td><?php echo $resrec['email']; ?></td>
								<td><?php echo $resrec['gender']; ?></td>
							</tr>
							<tr>
								<th style="color: blue;font-weight: bold;">Registered ID</th>
								<th style="color: blue;font-weight: bold;">Student ID</th>
								<th>Center</th>
								<th>Country</th>
								<th>State</th>
								<th>District</th>
							</tr>
							<tr>
								<th style="color: black;"><?php echo $resrec['reg_id']; ?></th>
								<th style="color: black;"><?php echo $resrec['stuid']; ?></th>
								<td><?php echo $resrec['center']; ?></td>
								<td style="text-align: center;"><?php echo $resrec['country']; ?></td>
								<td style="text-align: center;"><?php echo $resrec['state']; ?></td>
								<td><?php echo $resrec['district']; ?></td>
							</tr>
							<tr>
								<th colspan="2">DOB</th>
								<th colspan="2">Status</th>
								<th colspan="2">Registration Date & Time</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center;"><?php echo $resrec['dob']; ?></td>
								<td colspan="2" style="text-align: center;"><?php echo $resrec['status']; ?></td>
								<td colspan="2" style="text-align: center;"><?php echo $resrec['datetime']; ?></td>
							</tr>
							<tr>
								<th colspan="6">
									<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
								</th>
							</tr>
							</table>
						</div>
					<?php
				}
				else
				{
					echo "<p class='alert alert-danger text-center'><strong>Sorry! No Records Found.</strong></p>";
				}
			}

		?>
	</div><br><br><br><br><br><br><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
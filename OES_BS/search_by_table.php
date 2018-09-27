<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid']))
{
	header('location:http://localhost/OES_BS/adminlogin.php');
	exit();
}
$con=mysqli_connect("localhost","root","","online_examination_system");
$result=mysqli_query($con,"show tables from online_examination_system");

?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		div#table{
			max-width: 50%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="table">
		<form class="form-horizontal" method="post" action="" onsubmit="return ByTable()">
				<h4 class="text-center text-success">Search All Records By Table</h4>
				<div class="form-group">
					<label class="control-label col-sm-4">Search Records By:</label>
					<div class="col-sm-5">
						<select name="table" id="table" class="form-control">
							<option value="">--Select Table--</option>
							<?php
								if(mysqli_num_rows($result)>0)
								{
									while($row=mysqli_fetch_row($result))
									{
										?>
										<option <?php if(isset($_POST['table'])){if($_POST['table']==$row[0]) echo "selected";} ?> value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
										<?php
									}
								}
								else
								{
									echo "<p class='alert alert-warning text-center'><strong>Sorry! No Tables Found.</strong></p>";
								}

							?>
						</select>
					</div>
					<div class="col-sm-3">
						<button type="submit" name="submit" class="btn btn-success btn-block">
							<span class="glyphicon glyphicon-search"></span> Search
						</button>
					</div>
				</div>
		</form>
		</div>

		<div id="table_records" class="table-responsive">
		<?php
			if(isset($_POST['submit']))
			{
				if($_POST['table']=="")
				{
					echo "<p class='alert alert-warning text-center'><strong>Please Select a Table.</strong></p>";
				}
				else
				{
					$stable=addslashes(strip_tags(trim($_POST['table'])));
					$res=mysqli_query($con,"select * from $stable");
					if(mysqli_num_rows($res)>0)
					{
						$nc=mysqli_num_fields($res);
						?>
							<table class="table table-bordered">
								<tr class="success text-success">
									<?php
										while($col=mysqli_fetch_field($res))
										{
											echo "<th>".ucfirst($col->name)."</th>";
										}

									?>
								</tr>
								<?php
									while($row1=mysqli_fetch_row($res))
									{
										?>
										<tr>
											<?php
												for($i=0;$i<$nc;$i++)
												{
													echo "<td>".$row1[$i]."</td>";
												}
											?>
										</tr>
										<?php
									}
								?>
								<tr>
									<th colspan="<?php echo $nc; ?>">
										<a href="welcomadmin.php" class="btn btn-info btn-block">&#8810; Go Back</a>
									</th>
								</tr>
							</table>

						<?php
					}
					else
					{
						echo "<p class='alert alert-warning text-center'><strong>Sorry! No Records Found in Selected Table.
						</strong></p>";
					}
				}
			}
		?>
	</div>
	</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
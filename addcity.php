<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid'])) 
{
	header('location:http://localhost/OES_BS/admin-login');
	exit(0);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Adding Center</title>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/addcity.css">-->
	<script type="text/javascript" src="JavaScript/addcity.js"></script>
	<style type="text/css">
		div#addcity
		{
			width: 50%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="addcity">

		<?php
			if(isset($_POST['submit']))
			{
				$center=$_POST['city'];
				$address=$_POST['caddress'];
				$ccode=substr(str_shuffle(123456789), 5,3);
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				$query="insert into center(name,center_code,value,address,datetime) values('$center',$ccode,'$center','$address',NOW())";
				$result=mysqli_query($con,$query);
				echo mysqli_error($con);
				if(mysqli_affected_rows($con)>0)
				{
					echo "<p class='alert alert-success text-center'>$result. Center is Inserted Successfully.</p>";
							header('refresh:3;url=add-center');
				}
				else
				{
					echo "<p class='alert alert-warning text-center'>Sorry! Center is not Inserted.</p>";
				}
				mysqli_close($con);
			}
		?>

	 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return AddCity()">
	 	<fieldset>
	 					<legend class="text-center text-primary"><h2>Add Center</h2></legend>
	 				<div class="form-group">
	 					<label>Enter Center Name :</label>
	 					<input type="text" name="city" id="city" placeholder="Enter Center Name" class="form-control">
	 				</div>
	 				<div class="form-group">
	 					<label>Center Address :</label>
	 					<textarea rows="8" placeholder="Enter Center Details in MIN. 10C" id="caddress" name="caddress" class="form-control"></textarea>
	 				</div>
					<div class="row">
						<div class="col-md-6">
							<input type="submit" name="submit" value="Submit" class="btn btn-success btn-block">
						</div>
						<div class="col-md-6">
							<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
						</div>
					</div>
	 	</fieldset>
	 </form>
</div>
	</div><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
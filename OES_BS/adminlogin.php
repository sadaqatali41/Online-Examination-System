<?php
session_start();
include 'header.php';
include 'menubar.php';
if (isset($_SESSION['adminid'])) 
{
	header('location:http://localhost/OES_BS/welcomadmin.php');
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/CSS" href="CSS/adminlogin.css">
	<script type="text/javascript" src="JavaScript/adminlogin.js"></script>
	<style type="text/css">
		div#loginpage
		{
			width: 50%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="loginpage">

		<?php
			if(isset($_POST['submit']))
			{
				$id=FilterData($_POST['adminid']);
				$pass=FilterData($_POST['pass']);
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				$query="select * from adminlogin where id='$id' AND password='$pass'";
				$result=mysqli_query($con,$query);
				$num=mysqli_num_rows($result);
				$code=$_SESSION['code'];
				mysqli_close($con);
				if ($num==1) 
				{
					if($code==$_POST['code'])
					{
						$_SESSION['adminid']=$id;
						header('location:http://localhost/OES_BS/welcomadmin.php');
					}
					else
					{
						echo "<p class='alert alert-warning text-center'>Capcha is Mismatched.</p>";
					}
					
				}
				else
				{
					echo "<p class='alert alert-danger text-center'>Login Id or Password May be Incorrect.</p>";
							//header('refresh:3;url=adminlogin.php');
				}
			}
		
		?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return AdminValidation()">

		<fieldset>
					
					<legend class="text-center text-info"><h2>Admin Login Form</h2></legend>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" name="adminid" id="adminid" placeholder="Enter Admin Id" class="form-control" />
					</div>
					<br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="Password" name="pass" id="pass" placeholder="Enter Admin Password" class="form-control" />
					</div>
					<br>
					<div class="row">
							<div class="col-sm-3">
								<img src="image.php">
							</div>
							<div class="col-sm-9">
								<input type="number" name="code" id="code" placeholder="Enter Capcha Here" class="form-control">
							</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-6">
							<input type="submit" name="submit" value="Login" class="btn btn-success btn-block" />
						</div>
						<div class="col-sm-6">
							<input type="reset" name="reset" value="Clear All" class="btn btn-danger btn-block" />
						</div>
					</div><br><br><br><br><br>
		</fieldset>
	</form>
	<?php
		function FilterData($data)
		{
			$data=trim($data);
			$data=addslashes($data);
			$data=strip_tags($data);
			return $data;
		}

	?>
	</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
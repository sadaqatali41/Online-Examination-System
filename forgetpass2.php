<?php
session_start();
include 'header.php';
if(!isset($_SESSION['FORGET'])) 
{
	header('location:http://localhost/OES_BS/student-course-login');
	exit(0);
}

?>

<!DOCTYPE html>
<html>
<head>
	
	<script type="text/javascript" src="JavaScript/forgetpassword2.js"></script>
	<style type="text/css">
		div.forget1{
			margin: auto;
			width: 55%;
		}
	</style>
</head>
<body>
	<div class="container">
			<div class="forget1">

		<?php
		if(isset($_COOKIE['error']))
			echo $_COOKIE['error'];
		
			if(isset($_POST['submit']))
			{
				$password=$_POST['password'];
				$password1=$_POST['password1'];
				$code=$_SESSION['code'];
				
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				if($code==$_POST['code'])
				{
					$query1="UPDATE students SET password='$password' WHERE email='".$_SESSION['forgetpass']."'";

					$res=mysqli_query($con,$query1);
					if(mysqli_affected_rows($con)>0)
					{
						setcookie('error',"<p class='alert alert-success text-center'><strong>Your Password has been Changed Successfully.</strong></p>",time()+3);
						header('Location:student-sign-out');
					}
					else
					{
						setcookie('error',"<p class='alert alert-danger text-center'><strong>Sorry! We are Unable To Processing, Try Again Later.</strong></p>",time()+3);
						header('Location:forget-password-two');
					}
					mysqli_close($con);
				}
				else
				{
					setcookie('error',"<p class='alert alert-warning text-center'><strong>Capcha is Mismatched.</strong></p>",time()+3);
					header('Location:forget-password-two');
				}					
			}
		
		?>
		
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return PasswordValidation2()">
		<fieldset>
			
					
					<legend class="text-center"><h2>Forget Password</h2></legend>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3 alert alert-info">
									Your Email is :
								</div>
								<div class="col-sm-6 alert alert-success">
									<strong><?php echo $_SESSION['forgetpass']; ?></strong>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Enter Password</label>
							<input type="password" name="password" id="password" placeholder="Enter Password" 
							class="form-control" />
						</div>
						<div class="form-group">
							<label>Confirm Password</label>
							<input type="password" name="password1" id="password1" placeholder="Confirm Password" class="form-control" />
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<img src="image.php">
								</div>
								<div class="col-sm-9">
									<input type="number" name="code" id="code" placeholder="Enter Capcha Here" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<input type="submit" name="submit" value="Submit" class="btn btn-success btn-block" />
							</div>
							<div class="col-sm-6">
								<input type="reset" name="reset" value="Clear" class="btn btn-danger btn-block" />
							</div>
						</div>
</fieldset>
</form>
</div>
	</div><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>			
</html>
<?php
include 'header.php';
include 'menubar.php';
?>

<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="JavaScript/forgetpassword1.js"></script>
	<style type="text/css">
		div.forget{
			margin: auto;
			width: 55%;
		}
	</style>
</head>
<body>
<div class="container">
	<div class="forget">

			<?php
				session_start();
				if(isset($_POST['submit']))
				{
					$email=$_POST['email'];
					$con=mysqli_connect('localhost','root');
					mysqli_select_db($con,'online_examination_system');
					$query="select * from students where email='$email'";
					$result=mysqli_query($con,$query);
					$num=mysqli_num_rows($result);
					$row=mysqli_fetch_assoc($result);
					$code=$_SESSION['code'];
					mysqli_close($con);
					if ($num==1) 
					{
						if($code==$_POST['code'])
						{
							if($row['status']==1)
							{
								
								$to=$email;
								$subject="Forget Password, Online Examination System, MANUU";
								$otp=substr(str_shuffle(time()),3,5);
								$message="Hello, ".$email."<br>
										 Your OTP is: <b><i>".$otp."</i></b><br>
										 Thanks.<br>
										 Online Examination System, MANUU";
								$headers='From: <sadaqatali890@gmail.com>' . "\r\n" .
											'MIME-Version: 1.0' . "\r\n" .
											'Content-type: text/html; charset=utf-8';
								if(mail($to, $subject, $message, $headers))
								{
									$_SESSION['forgetpass']=$email;
									$_SESSION['OTP']=$otp;
									header('location:http://localhost/OES_BS/emailotp.php');
								}
								else
								{
									echo "<p class='alert alert-warning text-center'><strong>Email sent Failed</strong></p>";
								}
								
							}
							else
							{
								echo "<p class='alert alert-warning text-center'><strong>First, Activate Your Account From Your
										Email Link.</strong></p>";
							}
						}
						else
						{
							echo "<p class='alert alert-warning text-center'><strong>Capcha is Mismatched.</strong></p>";
						}
					}
					else
					{
						echo "<p class='alert alert-danger text-center'><strong>Sorry! Email Id is Incorrect.</strong></p>";
					}
				}
			?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return PasswordValidation1()">
		<fieldset>
					
					<legend><h2 class="text-center">Forget Password</h2></legend>
					<div class="form-group">
						<label>Enter Registered Email ID :</label>
						<input type="email" name="email" id="email" placeholder="Enter Your Email Id" class="form-control" />
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
							<input type="submit" name="submit" value="Next"  class="btn btn-primary btn-block" />
</fieldset>
</form>
</div>
</div><br><br><br><br><br><br><br>
<?php include 'footer.php'; ?>
</body>			
</html>
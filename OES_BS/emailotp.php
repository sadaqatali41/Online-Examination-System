<?php
session_start();

include('header.php');
if(!isset($_SESSION['forgetpass']))
{
	header('location:http://localhost/OES_BS/StudentCourseLogin.php');
	exit(0);
}

?>

<html>

	<head>
		<title>Forget Password OTP</title>
		<script>
			function OTPValidation()
			{
				var otp=document.getElementById("otp").value;
				if(otp=="" || otp==null)
				{
					alert("Enter OTP That is Send on Your Gmail !");
					document.getElementById("otp").focus();
					return false;
				}
				else if(otp.length<5 || otp.length>5)
				{
					alert("Enter Exact 5 Digits OTP !");
					document.getElementById("otp").focus();
					return false;
				}
			}
		
		</script>
		<style>
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
			
				if(isset($_POST['submit']))
				{
					$otp=$_POST['otp'];
					if($otp==$_SESSION['OTP'])
					{
						$_SESSION['FORGET']="FORGET";
						header('location:http://localhost/OES_BS/forgetpass2.php');
						
					}
					else
					{
						echo "<p class='alert alert-danger text-center'><strong>Sorry! OTP is Incorrect.</strong></p>";
					}
				}
			?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return OTPValidation()">
		<fieldset>
					
					<legend class="text-center"><h2>One Time Password</h2></legend>
						
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 alert alert-success text-center">
							OTP is send on this Email Address
						</div>
						<div class="col-sm-6 alert alert-info text-center">
							<b><?php  echo $_SESSION['forgetpass']; ?></b>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Enter OTP :</label>
					<input type="number" name="otp" id="otp" placeholder="Enter OTP" class="form-control" />
				</div>
					
							<input type="submit" name="submit" value="Next" class="btn btn-primary btn-block" />
</fieldset>
</form>
</div>
	</div><br><br><br><br><br>
	<?php include 'footer.php'; ?>
	</body>

</html>



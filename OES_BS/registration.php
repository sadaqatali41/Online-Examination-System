<?php
include 'header.php';
include 'menubar.php';
session_start();
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select name, value from course order by name asc";
$query1="select name, value from center order by name asc";
$result1=mysqli_query($con,$query);
$result2=mysqli_query($con,$query1);	//Accociative Array represented by Result 
$num=mysqli_num_rows($result1);
$num1=mysqli_num_rows($result2);
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="eng">
<head>
		<script type="text/javascript" src="JavaScript/regisvalidation.js"></script>
		<script type="text/javascript" src="JavaScript/StudentIdValidation.js"></script>
		<script type="text/javascript" src="JavaScript/MobileValidation.js"></script>
		<script type="text/javascript" src="JavaScript/EmailValidation.js"></script>
		<script type="text/javascript">
	function CallPassword1()
	{
		var x=document.getElementById("password1");
		if(x.type==="password")
		{
			x.type="text";
		}
		else
		{
			x.type="password";
		}
	}
	function CallPassword2()
	{
		var x=document.getElementById("password2");
		if(x.type==="password")
		{
			x.type="text";
		}
		else
		{
			x.type="password";
		}
	}
</script>
<style type="text/css">
	.container
	{
		width: 50%;
		margin: auto;
	}
</style>
	</head>
	<body>
		<div class="container">
			<?php 
		
			date_default_timezone_set('Asia/Kolkata');
			
			if(isset($_POST['submit']))
			{
				$code=$_SESSION['code'];
				if($code==FilterData($_POST['code']))
				{

					$fname=strtolower(FilterData($_POST['fname']));
					$lname=strtolower(FilterData($_POST['lname']));
					$course=FilterData($_POST['course']);
					$center=FilterData($_POST['center']);
					$phone=FilterData(strtolower($_POST['number']));
					$password1=FilterData($_POST['password1']);
					$password2=FilterData($_POST['password2']);
					$email=strtolower(FilterData($_POST['email']));
					$cont=strtolower(FilterData($_POST['cont']));
					$state=strtolower(FilterData($_POST['state']));
					$dist=strtolower(FilterData($_POST['dist']));
					$address=strtolower(FilterData($_POST['address']));
					$gender=FilterData($_POST['gender']);
					$dob=FilterData($_POST['birthday']);
					$stuid=substr(str_shuffle(time().$phone),6,10);
					$reg_id=substr($phone.$stuid,5,10);
					$con=mysqli_connect('localhost','root');
					mysqli_select_db($con,'online_examination_system');
					$query="insert into students (reg_id,fname,lname,course,center,stuid,phone,password,email,country,state,
						district,address,gender,dob,datetime) values ('$reg_id','$fname','$lname','$course','$center',$stuid,
						$phone,'$password1','$email','$cont','$state','$dist','$address','$gender','$dob',NOW())";
					$result=mysqli_query($con,$query);
					if(mysqli_affected_rows($con)>0)
					{
						$to       = $email;
						$subject  = 'Online Examination System, MANUU';
						$message  = "<b>Hi, ".ucwords("$fname $lname")."</b><br><br>
									 <b>THANKS FOR YOUR REGISTRATION</b><br>
									 Your Account has been Created Successfully<br><br>
									 For the Course:<b>".$course."</b><br><br>
									 Your Account Details are:-<br><br>
									 Your Login Id:<b>".$stuid."</b><br><br>
									 Your Password is:<b>".$password1."</b><br><br>
									 To get Access with Our Website, Please Activate Your Account to Login.<br><br>
									 <a href='http://localhost/OES_BS/activate.php?key=".$email."'>
									 Activate Account</a>";
						$headers  = 'From: ONLINE EXAMINATION SYSTEM <sadaqatali890@gmail.com>' . "\r\n" .
									'MIME-Version: 1.0' . "\r\n" .
									'Content-type: text/html; charset=utf-8';
						if(mail($to, $subject, $message, $headers))
						{
							echo "<p class='alert alert-success text-center'><strong>Your Account is Created
									Successfully, Check Your Email For Login ID and Password and Activate Your Account<br>
									THANKS.</strong></p>";
						}	
						else
						{
							echo "<p class='alert alert-danger text-center'>
									<strong>Email Send Failed, Contact to Admin at sadaqatali890@gmail.com</strong>
							</p>";
						}
					}
					else
					{
						echo "<p class='alert alert-warning text-center'><strong>You have Already Registered with
								this Email Id ".$email." and Mobile Number ".$phone." Refresh the Page.</strong></p>";
					}
					mysqli_close($con);
				}
				else
				{
					echo "<p class='alert alert-warning text-center'><strong>Capcha Mismatched, Try Again.</strong></p>";
				}
			}
		
		?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return RegistrationValidation()">
<fieldset>
<legend align="center"><h2>Sign-Up Form</h2></legend>

			<div class="input-group">
				<span class="input-group-addon">
					<i class="glyphicon glyphicon-user"></i></span>
				<input type="text" name="fname" id="fname" placeholder="First Name" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input type="text" name="lname" id="lname" placeholder="Last Name" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
				<select name="course" id="course" class="form-control">
					<option value="">Select Your Course</option>
					<?php

					for($i=1;$i<=$num;$i++)
					{
						$row=mysqli_fetch_array($result1);
					?>

					<option value="<?php echo $row['value']; ?>"><?php echo $row['name']; ?></option>

					<?php
					}

					?>
				</select>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
				<select name="center" id="center" class="form-control">
					<option value="">Select Your Center</option>

					<?php

					for($i=1;$i<=$num1;$i++)
					{
						$row=mysqli_fetch_array($result2);
					?>

					<option value="<?php echo $row['value']; ?>"><?php echo $row['name']; ?></option>

					<?php
					}

					?>

				</select>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
				<input type="text" name="number" id="mobile" placeholder="789394****" 
				onfocusout="fetchmobile(this.value)" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" name="password1" id="password1" placeholder="Password" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" name="password2" id="password2" placeholder="Confirm Password" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				<input type="email" name="email" id="email" placeholder="Example@something.com" 
				onfocusout="fetchemail(this.value)" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
				<input type="text" name="cont" id="cont" placeholder="Enter Your Country" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i>State</i></span>
				<input type="text" name="state" id="state" placeholder="Enter Your State" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i>Dist</i></span>
				<input type="text" name="dist" id="dist" placeholder="Enter Your District" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
				<input type="text" name="address" id="address" placeholder="Your Address With Pin Code." class="form-control">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				&nbsp;<label class="radio-inline"><input type="radio" name="gender" value="Male">Male</label>
				<label class="radio-inline"><input type="radio" name="gender" value="Female">Female</label>
				<label class="radio-inline"><input type="radio" name="gender" value="Others">Others</label>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-gift"></i></span>
				<input type="date" name="birthday" class="form-control">
			</div>
			<br>
			<div class="input-group">
				<div class="row">
					<div class="col-sm-6">
						<img src="image.php">
					</div>
					<div class="col-sm-6">
						<input type="number" name="code" id="code" placeholder="Enter Capcha" class="form-control">
					</div>
				</div>
			</div>
			<br>
			<div class="input-group">
				<div class="input-group-btn">
					<div class="row">
						<div class="col-sm-6">
							<button type="submit" class="btn btn-success btn-block" name="submit">
								<span class="glyphicon glyphicon-send"></span> Submit
							</button>
						</div>
						<div class="col-sm-6">
							<button type="reset" class="btn btn-danger btn-block">
								<span class="glyphicon glyphicon-trash"></span> Reset
							</button>
						</div>
					</div>	
				</div>
			</div>
			<br>
			<div class="text-center">
				<h4 class="text-success">Already Registered Students <a href="StudentCourseLogin.php">Login Here.</a></h4>
			</div>
</fieldset>
</form>
		</div>
		<?php
		function FilterData($data)
		{
			$data=trim($data);
			$data=addslashes($data);
			$data=strip_tags($data);
			return $data;
		}

	?>
	<?php include 'footer.php'; ?>
	</body>
</html>
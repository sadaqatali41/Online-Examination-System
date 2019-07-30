<?php
session_start();
include 'header.php';
include 'menubar.php';
if (isset($_SESSION['welcomeToExam'])) 
{
	header('location:http://localhost/OES_BS/welcome-to-exam');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="JavaScript/studentlogin.js"></script>
	<style type="text/css">
		div.loginpage
		{
			margin: auto;
			width: 55%;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="loginpage">
			<?php
			if(isset($_COOKIE['error']))
				echo $_COOKIE['error'];

			if(isset($_POST['submit']))
			{
				date_default_timezone_set('Asia/Kolkata');
				$studentid=FilterData($_POST['stuid']);
				$password=FilterData($_POST['pass']);
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				$query="SELECT * FROM students s,intermediate i WHERE s.stuid='$studentid' AND s.password='$password' AND 
						i.student_id='$studentid'";
				$result=mysqli_query($con,$query);
				$num=mysqli_num_rows($result);
				$row=mysqli_fetch_array($result);
				$code=$_SESSION['code'];
				if($num==1)
				{
					if($code==$_POST['code'])
					{
						
						if($row['status']==1)
						{
							
							$query1="SELECT * FROM alreadyloggedin WHERE stuid='$studentid'";
							$result1=mysqli_query($con,$query1);
							$num1=mysqli_num_rows($result1);

							if ($num!=$num1) 
							{
								$query2="INSERT INTO alreadyloggedin (SELECT fname,lname,course,center,stuid,phone,email,
								country,state,district,address,gender,dob,datetime
								FROM students WHERE stuid='$studentid')";
								$result2=mysqli_query($con,$query2);	
								mysqli_close($con);	
																		
								$_SESSION['studentid']=$studentid;
								$_SESSION['fname']=$row['fname'];
								$_SESSION['lname']=$row['lname'];
								$_SESSION['course']=$row['course'];
								$_SESSION['gender']=$row['gender'];
								$_SESSION['center']=$row['center'];
								$_SESSION['dob']=$row['dob'];
								$_SESSION['phone']=$row['phone'];
								$_SESSION['email']=$row['email'];
							
								$_SESSION['welcomeToExam']='welcomeToExam';
								header('location:http://localhost/OES_BS/welcome-to-exam');
							}
							else
							{	
								setcookie('error',"<p class='alert alert-warning text-center'><strong>You have Already Attend Exam, Now You have to Wait for the Result.</strong><a href='student-course-login'>Click Here</a></p>",time()+3);
								header('Location:exam-login');
							}
						}
						else
						{
							setcookie('error',"<p class='alert alert-warning text-center'><strong>First, Activate Your Account From Your Email Link.</strong></p>",time()+3);
							header('Location:exam-login');
						}
					}
					else
					{
						setcookie('error',"<p class='alert alert-warning text-center'><strong>Capcha is Mismatched.</strong></p>",time()+3);
						header('Location:exam-login');
					}
				}
				else
				{
					setcookie('error',"<p class='alert alert-danger text-center'><strong>Enter Your Qualification Details. 
						<a href='student-course-login'>Click Here.</a> or Login ID or Password may be Incorrect.</strong></p>",time()+3);
					header('Location:exam-login');
				}
			}
		
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" onsubmit="return StudentValidation()">

		<fieldset>
		
		<h2 class="text-center bg-success"><i>Exam Login Form</i></h2>
		<div class="alert alert-warning">
			<strong class="text-success">Note:-</strong> Please, Read The Exam Instruction Before Going to Login,
					otherwise You will be Responsible For Any Mistake, and you will see only questions not any kind of instructions regarding exammination.
					<a href="exam-instruction" class="alert-link">Instruction is Here.</a>
		</div>
		<div class="form-group">
			<label for="id">Enter Student Id</label>
			<input type="text" name="stuid" id="stuid" placeholder="Enter Student Id" class="form-control" />
		</div>
		<div class="form-group">
			<label for="pwd">Enter Password</label>
			<input type="Password" name="pass" id="pass" placeholder="Enter Password" class="form-control" />
		</div>
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
		</div>
		<div class="text-center alert-warning">
			<h4>New Students Register here. <a href="sign-up" class="alert-link">Register Here.</a></h4>
		</div>
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
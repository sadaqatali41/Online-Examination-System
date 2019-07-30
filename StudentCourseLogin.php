<?php
session_start();
ob_start();
include 'header.php';
include 'menubar.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="JavaScript/studentlogin.js"></script>
	<style type="text/css">
		div.courselogin{
			margin: auto;
			width: 55%;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="courselogin">
			<?php
			if(isset($_COOKIE['error']))
				echo $_COOKIE['error'];

			if(isset($_POST['submit'])) 
			{
				$studentid=FilterData($_POST['stuid']);
				$password=FilterData($_POST['pass']);
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				$query="SELECT fname,lname,course,status,center,stuid,password FROM students WHERE stuid='$studentid' 
						AND password='$password'";
				$result=mysqli_query($con,$query);
				$num=mysqli_num_rows($result);
				$row=mysqli_fetch_array($result);

				$query1="SELECT s.stuid,h.student_id,i.student_id FROM students s,highschool h,intermediate i WHERE
				s.stuid='$studentid' AND h.student_id='$studentid' AND i.student_id='$studentid'";
				$result1=mysqli_query($con,$query1);
				echo mysqli_error($con);
				$num1=mysqli_num_rows($result1);

				$query2="SELECT c_c_id FROM course WHERE name='".$row['course']."'";
				$result2=mysqli_query($con,$query2);
				echo mysqli_error($con);
				$rec=mysqli_fetch_array($result2);
				$code=$_SESSION['code'];
				
				mysqli_close($con);
				if ($num==1)
				{
					if($code==$_POST['code'])
					{
						if($row['status']==1)
						{
							$_SESSION['fname']=$row['fname'];
							$_SESSION['lname']=$row['lname'];
							$_SESSION['course']=$row['course'];
							$_SESSION['center']=$row['center'];
							$_SESSION['studentid']=$studentid;

							if($rec['c_c_id']==1)
							{
								if($num1==1)
								{
									$_SESSION['successUG']='successUG';
									$_SESSION['admitcard']='AdmitCard';
									header('location:http://localhost/OES_BS/ug-dashboard');
								}
								else
								{
									$_SESSION['ug']='ug';
									header('location:http://localhost/OES_BS/ug-registration');
								}
							}
							else
							{
								if($num1==1)
								{
									$_SESSION['successPG']='successPG';
									$_SESSION['admitcard']='AdmitCard';
									header('location:http://localhost/OES_BS/pg-dashboard');
								}
								else
								{
									$_SESSION['pg']='pg';
									header('location:http://localhost/OES_BS/pg-registration');
								}
							}
						}
						else
						{
							setcookie('error',"<p class='alert alert-warning text-center'><strong>First, Activate Your Account From Your Email Link.</strong></p>",time()+3);
							header('Location:student-course-login');
						}
					}
					else
					{
						setcookie('error',"<p class='alert alert-warning text-center'><strong>Capcha is Mismatched.</strong></p>",time()+3);
						header('Location:student-course-login');
					}
				} // first if closes
				else
				{
					setcookie('error',"<p class='alert alert-danger text-center'><strong>Login Id or Password May be Incorrect.</strong></p>",time()+3);
					header('Location:student-course-login');
				}
			}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return StudentValidation()">

		<fieldset>
		
		<h2 class="text-center alert-success"><i>Qualification Login Form</i></h2>
		<div class="form-group">
			<label for="id">Enter Student Id</label>
			<input type="text" name="stuid" id="stuid" placeholder="Enter Student Id" class="form-control" />
		</div>
		<div class="form-group">
			<label for="pwd">Enter Password</label>
			<input type="Password" name="pass" id="pass" placeholder="Enter Password" class="form-control" />
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
				<input type="submit" name="submit" value="Login" class="btn btn-primary btn-block" />
			</div>
			<div class="col-sm-6">
				<input type="reset" name="reset" value="Clear All" class="btn btn-danger btn-block" />
			</div>
		</div>
		<br>
		<div class="alert alert-info">
			<h4 class="text-center"><a href="forget-password-one" class="alert-link" name="forgetpass1">Forget Password???.</a></h4>
		</div>
		<div class="alert alert-success">
			<h4 class="text-center">New Students Register here. <a href="sign-up" class="alert-link">Register Here.</a></h4>
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
<?php
session_start();
include 'header.php';
include 'menubar.php';
?>
<!DOCTYPE html>
<html>
<head>
<!--<link rel="stylesheet" type="text/CSS" href="CSS/home.css">-->
<script type="text/javascript" src="JavaScript/studentlogin.js"></script>
<style type="text/css">
div.middle{
	max-height: 450px;
	max-width: 500px;
	overflow-y: scroll;
}
	div.left p > a{
		color: blue;
		font-style: italic;
		text-decoration: none;
	}
	div.left p > a:hover{
		color: red;
		font-style: italic;
		text-decoration: underline;
	}
	div.left p > a:active{
		color: green;
		font-style: italic;
	}
</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div><h3 class="text-center bg-primary">EXAMINATION NEWS</h3>
				<p>1. <a href="Examschedule.php">Examination Informations</a></p>
				<p>2. <a href="Eligibility_Criteria.php">Eligibility Criteria for Examination</a></p>
				<p>3. <a href="StudentCourseLogin.php">Download Admit Card</a></p>
				<p>4. <a href="StudentCourseLogin.php">Download Results</a></p>
				<!-- <p>4. <a href="#">Exam Time Modifications</a></p> -->
				<!-- <p>5. <a href="#">Exam Date Modification</a></p> -->
				<p>5. <a href="#">Other Informations</a></p>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="middle">
<h3 class="text-center text-info">EXAMINATION  INSTRSTRUCTIONS</h3>
<h4 class="text-center text-success">Please Read These Instructions Carefully:-</h4>
	<p><span style="color: red;font-size: 18px;font-weight: bold;">Note:-</span> A candidate who breaches any of the Examination Regulations will be liable to disciplinary action including (but not limited to) suspension or expulsion from the University.</p>
<dl>
	<dt style="font-weight: bold;font-size: 20px;">Timings:-</dt>
	<dd>
		<ul type="1">
			<li>Examinations will be conducted during the allocated times shown in the examination timetable.</li>
			<li>The examination hall will be open for admission <span style="font-weight: bold;">15 minutes</span> before the time scheduled for the commencement of the examination.</li>
			<li><span style="font-weight: bold;">Don't Login until the invigilator said to login.</span></li>
			<li>You will not be admitted for the examination after <span style="font-weight: bold;">ONE HOUR</span> of the commencement of the examination.</li>
		</ul>
	</dd>
	<dt style="font-weight: bold;font-size: 20px;">Personal Belongings:-</dt>
	<dd>
		<ul type="1">
			<li> All your personal belongings (such as bags, pouches, ear/headphones, laptops etc.) 
            must be placed at the designated area outside the examination hall. Please do not bring any 
            valuable belongings  except the essential materials required for the examinations.</li>
            <li>The University will not be responsible for the loss or damage of any belongings in or outside the 
            examination hall.</li>
		</ul>
	</dd>
	<dt style="font-weight: bold;font-size: 20px;">Items not Permitted in the Examination Hall:-</dt>
	<dd>
		<ul type="1">
			<li>Any unauthorised materials, such as books, paper, documents, pictures and electronic devices with communication and/or storage capabilities such as tablet PC, laptop, smart watch, portable audio/video/gaming devices etc. are not to be brought into the examination hall.</li>
			<li><span style="font-weight: bold;">Handphones brought into the examination hall must be switched off at ALL times.</span> If your handphone is found to be switched on in the examination hall, the handphone will be confiscated and retained for investigation of possible violation of regulations.</li>
			<li>No food or drink, other than water, is to be brought into the examination hall.</li>
			<li>Photography is NOT allowed in the examination hall at ALL times.</li>
		</ul>
	</dd>
	<dt style="font-weight: bold;font-size: 20px;">During Examination:-</dt>
	<dd>
		<ul type="1">
			<li>You are not allowed to communicate by word of mouth or otherwise with other candidates (this includes the time when answer scripts are being collected).</li>
			<li>Please raise your hand if you wish to communicate with an invigilator.</li>
			<li>Unless granted permission by an invigilator, you are not allowed to leave your seat.</li>
			<li>Don't use Mobile Phones For Calculations.</li>
			<li>We will Provide On-line Calculator for Calculations.</li>
			<li>Once you have entered the examination hall, you will not be allowed to leave the hall until one hour
				after the examination has commenced. </li>
            <li>Use Pens or Pencil for Rough work.</li>
            <li>Don't do any other activity during examinations.</li>
            <li>After Login(seeing questions), don't Submit before completion of exam.</li>
            <li>During Examination, don't Close the <span style="font-weight: bold;">Browser Window.</span></li>
			<li>During Examination, don't <span style="font-weight: bold;color:red;">Refresh</span>
				the <span style="font-weight: bold;">Browser Window.</span></li>
		</ul>
	</dd>
	<dt style="font-weight: bold;font-size: 20px;">At the End of the Examination:-</dt>
	<dd>
		<ul type="1">
			<li>You are to stay in the examination hall until the Chief Invigilator has given the permission to leave. Do <span style="font-weight: bold;">NOT</span> talk until you are outside of the examination hall.</li>
			<li>Once dismissed, you should leave the examination hall quickly and quietly. Remember to take your <span style="font-weight: bold;">personal belongings</span>
			 with you. </li>
			 <li>Wait for Results....</li>
		</ul>
	</dd>
</dl>
</div>
			</div>
			<div class="col-sm-4">
				<div class="right">
	<div id="loginpage">

		<?php

			if(isset($_POST['submit'])) 
			{
				$studentid=FilterData($_POST['stuid']);
				$password=FilterData($_POST['pass']);
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				$query="select fname,lname,course,status,center,stuid,password from students where stuid='$studentid' 
						AND password='$password'";
				$result=mysqli_query($con,$query);
				$num=mysqli_num_rows($result);
				$row=mysqli_fetch_array($result);

				$query1="select s.stuid,h.student_id,i.student_id from students s,highschool h,intermediate i where
				s.stuid='$studentid' AND h.student_id='$studentid' AND i.student_id='$studentid'";
				$result1=mysqli_query($con,$query1);
				echo mysqli_error($con);
				$num1=mysqli_num_rows($result1);

				$query2="select c_c_id from course where name='$row[course]'";
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
									header('location:http://localhost/OES_BS/UGDataSuccessfullyInserted.php');
								}
								else
								{
									$_SESSION['ug']='ug';
									header('location:http://localhost/OES_BS/UGForm.php');
								}
							}
							else
							{
								if($num1==1)
								{
									$_SESSION['successPG']='successPG';
									$_SESSION['admitcard']='AdmitCard';
									header('location:http://localhost/OES_BS/PGDataSuccessfullyInserted.php');
								}
								else
								{
									$_SESSION['pg']='pg';
									header('location:http://localhost/OES_BS/PGForm.php');
								}
							}
						}
						else
						{
							echo "<p class='alert alert-warning text-center'>First, Activate Your Account From Your
									Email Link.</p>";
						}
					}
					else
					{
						echo "<p class='text-center alert alert-danger'>Capcha is Mismatched.</p>";
					}
				} // first if closes
				else
				{
					echo "<p class='text-center alert alert-danger'>Login Id or Password May be Incorrect.</p>";
							//header('refresh:3;url=StudentCourseLogin.php');
				}
			}
		?>
		
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return StudentValidation()">

		<fieldset>
					<legend class="text-center"><h2>Qualification Login Form</h2></legend>
						
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input type="text" name="stuid" id="stuid" placeholder="Enter Student Id" class="form-control" />
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input type="Password" name="pass" id="pass" placeholder="Enter Password" class="form-control" />
				</div>
				<br>
				<div class="input-group">
					<div class="row">
						<div class="col-sm-6">
							<img src="image.php">
						</div>
						<div class="col-sm-6">
							<input type="number" name="code" id="code" placeholder="Enter Capcha Here" class="form-control">
						</div>
					</div>
				</div>
				<br>
				<div class="row">
						<div class="col-sm-6">
							<button type="submit" name="submit" class="btn btn-success btn-block">Login</button>
						</div>
						<div class="col-sm-6">
							<button type="reset" class="btn btn-danger btn-block">Clear</button>
						</div>
				</div>
				<br>
				<div class="input-group">
					<p class="text-center"><a href="forgetpass1.php" name="forgetpass1">Forget Password???.</a></p>
					<p class="text-center">New Students Register here. <a href="registration.php" class="reg">Register Here.</a>
					</p>
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
			</div>

		</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
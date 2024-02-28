<?php
session_start();
include 'header.php';
if(!isset($_SESSION['pg']))
{
	header('location:http://localhost/OES_BS/student-sign-out');
	exit(0);
}

?>

<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/PGForm.css">-->
	<script type="text/javascript" src="JavaScript/PGForm.js"></script>
	<style type="text/css">
		span{
			float: left;
			margin-left: 0px;
			margin-top: .5%;
			padding: .5%;
			font-size: 20px;
			font-weight: bold;
			font-style: italic;
			color: black;
			background-color: linen;
			border: 2px dotted red;
			border-radius: 20px;
		}
		div#pgform
		{
			width: 70%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
				<div class="col-sm-6">
					<span>Hello.. <?php echo ucwords("$_SESSION[fname] $_SESSION[lname]"); ?></span>
				</div>
				<div class="col-sm-6 text-right">
					<a href="student-sign-out" class="btn btn-primary">Sign Out</a>
				</div>
		</div>
			<div id="pgform">
		<?php
		if(isset($_COOKIE['error']))
			echo $_COOKIE['error'];
		
			if(isset($_POST['submit']))
			{
				$code=$_SESSION['code'];
				if($code==FilterData($_POST['code']))
				{
					// High School Details
					date_default_timezone_set('Asia/Kolkata');

					$rollno=strtolower(FilterData($_POST['rollno']));
					$insname=strtolower(FilterData($_POST['insname']));
					$board=strtolower(FilterData($_POST['board']));
					$yop=FilterData($_POST['yop']);
					$percent=FilterData($_POST['percent']);

					// Intermediate Details

					$rollnoInter=strtolower(FilterData($_POST['rollnoInter']));
					$insnameInter=strtolower(FilterData($_POST['insnameInter']));
					$boardInter=strtolower(FilterData($_POST['boardInter']));
					$yopInter=FilterData($_POST['yopInter']);
					$percentInter=FilterData($_POST['percentInter']);

					// Graduation Details

					$Cname=strtolower(FilterData($_POST['Cname']));
					$enroll=strtolower(FilterData($_POST['enroll']));
					$Bname=strtolower(FilterData($_POST['Bname']));
					$insG=strtolower(FilterData($_POST['insG']));
					$yopUg=FilterData($_POST['yopUg']);
					$percentUg=FilterData($_POST['percentUg']);

					$con=mysqli_connect('localhost','root');
					mysqli_select_db($con,'online_examination_system');
					$q1="INSERT INTO highschool (student_id,fname,lname,roll_no,college_name,borad_name,yop,percent,datetime) 
					VALUES($_SESSION[studentid],'$_SESSION[fname]','$_SESSION[lname]',$rollno,'$insname','$board',$yop,$percent
					,NOW())";

					$q2="INSERT INTO  intermediate (student_id,fname,lname,roll_no,college_name,borad_name,yop,percent,datetime) 
						VALUES($_SESSION[studentid],'$_SESSION[fname]','$_SESSION[lname]',$rollnoInter,'$insnameInter','$boardInter',
						$yopInter,$percentInter,NOW())";

					$q3="INSERT INTO graduation(student_id,fname,lname,course_name,enroll_no,branch_name,institute_name,yop,aggregate_percent,datetime)
						VALUES($_SESSION[studentid],'$_SESSION[fname]','$_SESSION[lname]','$Cname','$enroll','$Bname','$insG',$yopUg,
						$percentUg,NOW())";

					$rs1=mysqli_query($con,$q1);
					$rs2=mysqli_query($con,$q2);
					$rs3=mysqli_query($con,$q3);
					if(mysqli_affected_rows($con)>0)
					{
						setcookie('error',"<p class='alert alert-success text-center'><strong>$rs1 .Your Record for Post Graduation is Inserted!<br>Now Login Again.</strong></p>",time()+4);
						header('Location:student-sign-out');
					}
					else
					{
						setcookie('error',"<p class='alert alert-warning text-center'><strong>Sorry! Your Records is not Inserted,
							Please Try Again Later.</strong></p>",time()+3);
						header('Location:pg-registration');
					}
					mysqli_close($con);
				}
				else
				{
					setcookie('error',"<p class='alert alert-warning text-center'><strong>Capcha Mismatched, Try Again.</strong></p>",time()+3);
					header('Location:pg-registration');
				}
			}
		
		?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return PGFormValidation()">
		<fieldset>
			<div class="table-responsive">
				<table class="table">
				<thead>
					<tr>
					<legend class="text-center"><h2>Education Qualifications</h2></legend>	
				</tr>
				<tr>
					<th colspan="4" style="background-color: red; font-style: italic;color: linen; font-size: 20px; border-radius: 10px;" class="text-center">High School Details</th>
				</tr>
				</thead>
				<tbody>
					<tr>
						<th>Course Name</th>
						<td><input type="text" name="sss" value="High School" readonly class="form-control"></td>
						<th>Roll Number</th>
						<td><input type="number" name="rollno" id="rollno" placeholder="H.S. Roll Number" class="form-control">
						</td>
					</tr>
					<tr>
						<th>Institution Name</th>
				<td>
					<textarea name="insname" id="insname" placeholder="Enter Institution Name With Address" rows="5" class="form-control"></textarea>
				</td>
						<th>Board Name</th>
						<td><input type="text" name="board" id="board" placeholder="Enter Board Name" class="form-control"></td>
					</tr>
					<tr>
						<th>Year Of Passing</th>
						<td><input type="number" name="yop" id="yop" placeholder="Enter Year Of Passing" class="form-control">
						</td>
						<th>Passing %</th>
						<td>
						<input type="number" name="percent" id="percent" placeholder="Enter Your Percent" class="form-control">
						</td>
					</tr>
					<tr>
						<th colspan="4" style="background-color: black; font-style: italic;color: yellow; font-size: 20px; border-radius: 10px;" class="text-center">Intermediate Details</th>
					</tr>
					<tr>
						<th>Course Name</th>
						<td><input type="text" name="sss" value="InterMediate" readonly class="form-control"></td>
						<th>Roll Number</th>
						<td><input type="number" name="rollnoInter" id="rollnoInter" placeholder="10+2 Roll Number" class="form-control"></td>
					</tr>
					<tr>
						<th>Institution Name</th>
						<td><textarea name="insnameInter" id="insnameInter" placeholder="Enter Institution Name With Address" class="form-control" rows="5"></textarea></td>
						<th>Board Name</th>
						<td><input type="text" name="boardInter" id="boardInter" placeholder="Enter Board Name" class="form-control"></td>
					</tr>
					<tr>
						<th>Year Of Passing</th>
						<td><input type="number" name="yopInter" id="yopInter" placeholder="Enter Year Of Passing" class="form-control"></td>
						<th>Passing %</th>
						<td><input type="number" name="percentInter" id="percentInter" placeholder="Enter Your Percent" class="form-control"></td>
					</tr>
					<tr>
						<th colspan="4" style="background-color: purple; font-style: italic;color: cyan; font-size: 20px; border-radius: 10px;" class="text-center">Graduation Details</th>
					</tr>
					<tr>
						<th>Course Name</th>
						<td><input type="text" name="Cname" id="Cname" placeholder="Previous Course Name" class="form-control">
						</td>
						<th>Enrollment Number</th>
						<td><input type="text" name="enroll" id="enroll" placeholder="Enter Your E.Number" class="form-control">
						</td>
					</tr>
					<tr>
						<th>Specialization</th>
						<td><input type="text" name="Bname" id="Bname" placeholder="Enter Your Branch" class="form-control"></td>
						<th>Institution Name</th>
						<td><textarea name="insG" id="insG" placeholder="Enter Institution Name With Address" class="form-control" rows="5"></textarea></td>
					</tr>
					<tr>
						<th>Year Of Passing</th>
						<td><input type="number" name="yopUg" id="yopUg" placeholder="Enter Year Of Passing" class="form-control"></td>
						<th>Aggregate %</th>
						<td><input type="number" name="percentUg" id="percentUg" placeholder="Your Aggregate Percent" class="form-control"></td>
					</tr>
					<tr>
						<td></td>
						<td><img src="image.php"></td>
						<td><input type="number" name="code" id="code" placeholder="Enter Capcha" class="form-control"></td>
						<td></td>
					</tr>
					<tr>
					<th colspan="4" class="text-center">
						<input type="submit" name="submit" value="Submit" class="btn btn-success" />
						<input type="reset" name="reset" value="Clear All" class="btn btn-danger" />
					</th>
					</tr>
				</tbody>
			</table>
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

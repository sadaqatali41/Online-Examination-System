<?php
include 'admin_menubar.php';
// if (!isset($_SESSION['adminid'])) 
// {
// 	header('location:http://localhost/OES_BS/admin-login');
// 	exit(0);
// }
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query="select * from course_category order by cc_name asc";
$result=mysqli_query($con,$query);
mysqli_close($con);
$row=mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/addcourse.css">-->
	<script type="text/javascript" src="JavaScript/addcourse.js"></script>
	<style type="text/css">
		div#addcourse
		{
			width: 55%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="addcourse">

		<?php
			if (isset($_POST['submit']))
			{
				$cc=$_POST['course_catagory'];
				$course=$_POST['course'];
				$con=mysqli_connect('localhost','root');
				mysqli_select_db($con,'online_examination_system');
				$query="insert into course(c_c_id,name,value,datetime) values($cc,'$course','$course',NOW())";
				$result1=mysqli_query($con,$query);
				echo mysqli_error($con);
				if(mysqli_affected_rows($con)>0)
				{
					echo "<p class='alert alert-success text-center'>$result1. Course is Inserted Successfully.</p>";
							header('refresh:3;url=add-course');
				}
				else
				{
					echo "<p class='alert alert-warning text-center'>Sorry! Course is not Inserted.</p>";
				}
				mysqli_close($con);
			}
		
		?>
		
	 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return AddCourse()">
	 	<fieldset>
	 					<legend class="text-center text-muted"><h2>Add Course</h2></legend>
	 				<div class="form-group">
	 					<label>Select Course Category :</label>
	 					<select name="course_catagory" id="course_catagory" class="form-control">
	 						<option value="" >Select Course Category</option>
	 						<?php
	 						$i=1;
	 						while($i<=$row) 
	 						{
	 							$rec=mysqli_fetch_array($result);
	 						?>
	 							<option value="<?php echo $rec['category_id']; ?>"><?php echo $rec['cname']; ?></option>
 							<?php

 								$i++;
 							}

 							?>
	 						
	 					</select>
	 				</div>
	 				<div class="form-group">
	 					<label>Enter Course Name :</label>
	 					<input type="text" name="course" id="course" placeholder="Enter Course Name" class="form-control">
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
	</div><br><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
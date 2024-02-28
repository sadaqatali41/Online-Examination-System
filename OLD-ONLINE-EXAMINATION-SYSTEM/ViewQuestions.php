<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid']))
{
	header('location:http://localhost/OES_BS/admin-login');
}

$con = mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$query = "SELECT questions.*, course.name AS course_name FROM questions INNER JOIN course ON course.course_id = questions.course_id";

if(isset($_POST['serach_submit']))
{
	$course_id = $_POST['course_id'];
	if($course_id != 0)
		$query .= " WHERE questions.course_id = '$course_id'";
}

$result = mysqli_query($con,$query);	//Associative Array 
$num = mysqli_num_rows($result);

$sql = "SELECT * FROM course ORDER BY name ASC";
$result1 = mysqli_query($con, $sql);
mysqli_close($con);

?>

<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/css" href="CSS/viewrecords.css">-->
	<style type="text/css">
		th,td
		{
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div id="viewquestion" class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th colspan="9">
							<form class="form-inline" action="view-questions" method="post">
							  <div class="form-group">
							    <label for="course_id">Select Course:</label>
							    <select class="form-control" name="course_id">
							    	<option value="0" selected>All Courses</option>
							    <?php while($row = mysqli_fetch_object($result1)): ?>
							    	<option value="<?php echo $row->course_id ?>" <?php if(isset($_POST['course_id']) && $_POST['course_id']==$row->course_id)echo "selected";?>><?php echo $row->name ?></option>
							    <?php endwhile; ?>
							    </select>
							  </div>
							  <input type="submit" name="serach_submit" value="Filter" class="btn btn-info">
							</form>
						</th>
					</tr>
					<tr class="info">
						<th colspan="9" class="text-center text-success"><h2>All Questions <span style="color: red;">[<?php echo $num; ?>]</span></h2></th>
					</tr>
					<tr class="warning text-primary">
						<th>Serial No.</th>
						<th>Course Name</th>
						<th>Question Name</th>
						<th>Option A</th>
						<th>Option B</th>
						<th>Option C</th>
						<th>Option D</th>
						<th>Correct Option</th>
						<th>Created Datetime</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i=1;
						$counter = 1;
						while($i<=$num)
						{ 
							$rows=mysqli_fetch_array($result);

					?>
					
						<tr>
							<td><?php echo $counter++;  ?></td>
							<td><?php echo $rows['course_name'];  ?></td>
							<td><?php echo $rows['name'];  ?></td>
							<td><?php echo $rows['optionA'];  ?></td>
							<td><?php echo $rows['optionB'];  ?></td>
							<td><?php echo $rows['optionC'];  ?></td>
							<td><?php echo $rows['optionD'];  ?></td>
							<td><?php echo $rows['correct_option'];  ?></td>
							<td><?php echo $rows['datetime'];  ?></td>
						</tr>


					<?php
						$i++;		

						}
					?>
					<tr>
						<th colspan="9">
							<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
						</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
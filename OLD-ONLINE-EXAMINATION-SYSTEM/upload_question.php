<?php
include 'admin_menubar.php';
if (!isset($_SESSION['adminid']))
{
	header('location:http://localhost/OES_BS/admin-login');
	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		div#question{
			width: 50%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="question">
<?php
	if(isset($_COOKIE['error']))
		echo $_COOKIE['error'];

	if(isset($_POST['submit']))
	{
		if(is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$filename=$_FILES['file']['name'];
			$ext=substr($filename,strpos($filename,"."));
			if($ext==".csv")
			{
				$con=mysqli_connect("localhost","root","","online_examination_system");
				$file1=fopen("CSV/".$filename,"r");
				while(($fdata=fgetcsv($file1, 10000, ",")) !== false)
				{
					mysqli_query($con,"INSERT INTO questions (course_id,name,optionA,optionB,optionC,optionD,correct_option,datetime) VALUES('$fdata[0]','$fdata[1]',
						'$fdata[2]','$fdata[3]','$fdata[4]','$fdata[5]','$fdata[6]',NOW())");
					echo mysqli_error($con);
				}
				if(mysqli_affected_rows($con)>0)
				{
					setcookie('error',"<p class='alert alert-success text-center'><strong>Questions are Inserted Successfully.</strong></p>",time()+3);
					header('Location:upload-question');
				}
				else
				{
					setcookie('error',"<p class='alert alert-danger text-center'><strong>Sorry! Unable to process.</strong></p>",time()+3);
					header('Location:upload-question');
				}
			}
			else
			{
				setcookie('error',"<p class='alert alert-warning text-center'><strong>File Format is not Matched.</strong></p>",time()+3);
				header('Location:upload-question');
			}
		}
		else
		{
			setcookie('error',"<p class='alert alert-warning text-center'><strong>Please Upload only CSV files.</strong></p>",time()+3);
			header('Location:upload-question');
		}
		
	}
?>
	<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
				<h2 class="text-center text-warning">Upload CSV Questions File</h2>
				<div class="form-group">
					<label class="control-label col-sm-4">Upload Questions:</label>
					<div class="col-sm-8">
						<input type="file" name="file" class="form-control" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<div class="row">
							<div class="col-sm-6">
								<input type="submit" name="submit" value="Import Questions" class="btn btn-primary btn-block">
							</div>
							<div class="col-sm-6">
								<a href="admin-dashboard" class="btn btn-info btn-block">&#8810; Go Back</a>
							</div>
						</div>
					</div>
				</div>
	</form>
</div>
	</div><br><br><br><br><br><br><br><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
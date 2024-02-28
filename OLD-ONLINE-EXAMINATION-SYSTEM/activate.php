<?php
include('header.php');
?>
<html>

	<head>
		<style>
			div#activate
			{
				width:50%;
				margin:auto;
				background-color:linen;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div id="activate">
			
		<?php

			if(isset($_GET['key']))
			{
				$key=FilterData($_GET['key']);
				$con=mysqli_connect('localhost','root','','online_examination_system');
				$res=mysqli_query($con,"select email,status from students where email='$key'");
				if(mysqli_num_rows($res)==1)
				{
					$row=mysqli_fetch_assoc($res);
					if($row['status']==0)
					{
						mysqli_query($con,"update students set status=1 where email='$key'");
						if(mysqli_affected_rows($con)==1)
						{
							echo "<p class='alert alert-success text-center'><strong>Your Account is Activated Successfully
									. Please <a href='StudentCourseLogin.php'>Login Here.</a></strong></p>";
						}
					}
					else
					{
						echo "<p class='alert alert-warning text-center'><strong>Your Account is Already Activated. Try to 
								<a href='StudentCourseLogin.php'>Login Here.</a></strong></p>";
					}
					
				}
				else
				{
					echo "<p class='alert alert-danger text-center'><strong>Sorry! We are Unable to Process Your Request.</strong>
					</p>";
				}
			}
			else
			{
				header('location:http://localhost/OES_BS/registration.php');
				exit(0);
			}

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
	</body>

</html>
<?php
	include 'header.php';
	include 'menubar.php';
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		div#Eligibility{
			width: 50%;
			margin: auto;
			background-color: #EFDECD;
			margin-top: 10px;
			border-radius: 10px; 
		}
	.heading > th{
		background-color: #FF7E00;
		font-size: 20px;
		font-style: italic;
		text-align: center;
	}
	table{
		border-collapse: collapse;
	}
	td{
		text-align: justify;
		padding: 5px;
	}

	</style>
</head>
<body>
	<div id="Eligibility">
		<table cellspacing="8" cellpadding="8" align="center" border="2">
			<tr class="heading">
				<th>S.No</th>
				<th>Course Name</th>
				<th>Eligibility Criteria</th>
			</tr>
			<tr>
				<td>1</td>
				<td>B.TECH</td>
				<td>10+2 with Physics, Chemistry and Mathematics or Physics, Chemistry, Mathematics and Biology subjects with 45% marks in aggregate.
				</td>
			</tr>
			<tr>
				<td>2</td>
				<td>MCA</td>
				<td>Bachelor's Degree with 45% marks in aggregate and Mathematics 
					as one of the subjects 
					at 10+2 or graduate level.</td>
			</tr>
			<tr>
				<td>3</td>
				<td>M-TECH</td>
				<td>Bachelor of Technology degree in Computer Science/Information Technology/Electronics & 
					Communication Engineering or MCA or M.Sc. in Computer Science/IT/ Electronics recognized 
					by the University with not less than 55% marks in aggregate or its equivalent CGPA.
				</td>
			</tr>
		</table>
	</div><br><br><br><br><br><br>
	<?php include 'footer.php'; ?>
</body>
</html>
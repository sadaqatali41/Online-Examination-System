<?php
include 'header.php';
include 'menubar.php';
?>
<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/aboutus.css">-->
	<style type="text/css">
		img.Photos
		{
			width: 100%;
			height: inherit;
			float: right;
			border: 1px dotted white;
			border-radius: 5px;
		}
		div#main
		{
			width: 75%;
			margin: auto;
		}
	</style>
</head>
<body>
	<div class="container">
		<div id="main">
	<table class="table table-bordered table-hover">
		<thead>
			<tr class="info">
				<th colspan="9" class="text-center text-primary" style="font-size: 18px;">About Us</th>
			</tr>
		</thead>
		<tbody>
			<tr class="warning text-muted">
				<th style="float: right;" class="detail">Team Photos</th>
				<th class="detail">Name</th>
				<th class="detail">Phone</th>
				<th class="detail">City</th>
				<th class="detail">Course</th>
				<th class="detail">Strem</th>
				<th class="detail">Institute</th>
				<th class="detail">Email</th>
				<th class="detail">Website/Blogger</th>
			</tr>
			<tr>
				<th><a href="./images/sadaqat.jpg" target="_blank"><img class="Photos" src="./images/sadaqat.jpg"></a></th>
				<th class="sadaqat">Sadaqat Ali</th>
				<th class="sadaqat">7893941364</th>
				<th class="sadaqat">Hyderabad</th>
				<th class="sadaqat">B-Tech</th>
				<th class="sadaqat">CS</th>
				<th class="sadaqat"><a href="http://www.manuu.ac.in/Eng-Php/index-english.php">MANUU</a></th>
				<th class="sadaqat">sadaqatali890@gmail.com</th>
				<th class="sadaqat"><a href="https://sadaqatali0786.blogspot.in/">Jeans Adda</a></th>
			</tr>
			<tr>
				<th><a href="./images/shadab.jpg" target="_blank"><img class="Photos" src="./images/shadab.jpg"></a></th>
				<th class="shadab">Shadab Alam</th>
				<th class="shadab">9044981807</th>
				<th class="shadab">Hyderabad</th>
				<th class="shadab">B-Tech</th>
				<th class="shadab">CS</th>
				<th class="shadab"><a href="http://www.manuu.ac.in/Eng-Php/index-english.php">MANUU</a></th>
				<th class="shadab">shadab.tmt@gmail.com</th>
				<th class="shadab"><a href="#">Not Avail.</a></th>
			</tr>
			<tr>
				<th><a href="./images/saadat.jpg" target="_blank"><img class="Photos" src="./images/saadat.jpg"></a></th>
				<th class="saadat">Saadat Karim</th>
				<th class="saadat">8142683282</th>
				<th class="saadat">Hyderabad</th>
				<th class="saadat">B-Tech</th>
				<th class="saadat">CS</th>
				<th class="saadat"><a href="http://www.manuu.ac.in/Eng-Php/index-english.php">MANUU</a></th>
				<th class="saadat">saadatk740@gmail.com</th>
				<th class="saadat"><a href="https://saadatkarim.blogspot.in/">Shoes House</a></th>
			</tr>
		</tbody>
	</table>
</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
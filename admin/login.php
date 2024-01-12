<?php
include 'class/database.php';
include 'class/userAuth.php';

$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$auth->sessionStart();
if($auth->loginCheck($conn) === true) {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Online Examination System</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Login CSS -->
    <link rel="stylesheet" href="dist/css/login.css">
</head>
<body>
	<div class="container">
		<div class="login-container">
			<h2>Admin Login</h2>
			<form id="loginForm">
				<div class="form-group">
					<label for="email">Email:</label>
					<input type="email" class="form-control" name="email" id="email" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="password">Password:</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Password">
				</div>
				<button type="submit" id="loginFormBtn" class="btn btn-primary">Login</button>
			</form>
		</div>
	</div>
	<!-- jQuery 3 -->
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Login JS -->
	<script src="js/login.js"></script>
</body>
</html>

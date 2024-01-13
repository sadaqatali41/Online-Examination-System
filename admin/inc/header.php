<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin - Online Examination System</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/AdminLTE.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="../dist/css/skins/skin-black-light.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Datatable Buttons -->
    <link rel="stylesheet" href="../dist/css/buttons.dataTables.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.css">
	<!-- datepicker -->
	<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-black-light sidebar-mini">
    <div class="wrapper">
        <!-- include header -->
        <header class="main-header">
            <a href="<?php print PROJECT_ROOT . "admin/index";?>" class="logo">
                <span class="logo-mini"><b>OE</b>S</span>
                <!-- <span class="logo-lg">
                    <img src="<?php //echo PROJECT_ROOT; ?>img/header.png" alt="my logo">
                </span> -->
            </a>
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="<?php print PROJECT_ROOT . "admin/index";?>" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div style="display: inline-block;">
                    <h3 style="margin: 15px 0 0 8px">
                        <b>Welcome <?= $user_data['name'];?></b>
                    </h3>
                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">                        
                        <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-gears"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">
                                    <a href="<?php print PROJECT_ROOT; ?>admin/profile">
                                        <b><i class="fa fa-user"></i> Profile</b>
                                    </a>
                                </li>
                                <li class="header">
                                    <a href="<?php print PROJECT_ROOT; ?>admin/logout">
                                        <b><i class="fa fa-sign-out"></i> Logout</b>
                                    </a>
                                </li>
                                <li class="header">
                                    <a href="<?php print PROJECT_ROOT; ?>admin/change_password">
                                        <b><i class="fa fa-lock"></i> Change Password</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- include sidebar -->
        <?php include_once 'inc/sidebar.php' ?>
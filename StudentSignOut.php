<?php
session_start();
session_destroy();
header('location:http://localhost/OES_BS/student-course-login');
?>
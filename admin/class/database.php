<?php

class Database
{
    public function connectDB()
    {
        ob_start();
        $host = "localhost: 3306";
        $user = "root";
        $password = "";
        $database = "online_examination_system";
        $conn = new mysqli($host, $user, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        set_time_limit(0);
        date_default_timezone_set("Asia/Kolkata");

        return $conn;
    }
}

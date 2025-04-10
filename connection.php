<?php
    $database = 'ssps';
    $port = '3306';
    // $username = "sepric123";
    // $password = "SepricG@rci@123";
    $username = "root";
    $password = "";
    $hostname = "localhost";
    $dbhandle = mysqli_connect($hostname, $username, $password, $database, $port) or die("Unable to connect to MySQL");


    $selected = mysqli_select_db($dbhandle, $database) or die("Could not connect to database");


?>
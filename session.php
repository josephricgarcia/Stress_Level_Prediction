<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { 
    header("Location: LogIn.php");
    exit();
}
?>
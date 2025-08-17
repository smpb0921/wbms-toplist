<?php
$srv = $_SERVER['HTTP_HOST'];
if(!($srv=="localhost:8018" || $srv=="127.0.0.1:8018")){
    header("Location: http://$srv");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wbms-cbl";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

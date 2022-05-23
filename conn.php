<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hidroponik";

// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

date_default_timezone_set("Asia/Jakarta");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
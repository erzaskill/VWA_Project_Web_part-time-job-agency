<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ezjobsagency";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
//$conn = mysqli_connect($servername,$username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
<?php 

$servername = "localhost";
$username = "root";
$dbName = "";

// Create connection
$conn = new mysqli($servername, $username);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
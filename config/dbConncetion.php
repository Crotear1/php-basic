<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbName = "webshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
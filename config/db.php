<?php
$servername = "localhost";
$username = "webshop";
$password = "87duHvsp/MkubFvt";
$dbName = "webshop";

// Create connection
$db = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}
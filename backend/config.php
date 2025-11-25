<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "bus_booking";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set UTF-8 encoding
$conn->set_charset("utf8mb4");

// echo "✅ Connected successfully";
?>

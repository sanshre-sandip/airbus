<?php
$servername = "localhost";
$username = "sandip";
$password = "sandip";
$database = "bus_booking";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("---Connection-failed: " . $conn->connect_error);
}

// Optional: uncomment to check
// echo "✅ Database connected successfully";
?>

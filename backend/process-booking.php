<?php
session_start();
require_once 'config.php';

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Collect POST data safely
$user_id = $_SESSION['user_id'];
$bus_id = isset($_POST['bus_id']) ? intval($_POST['bus_id']) : 0;
$date = isset($_POST['date']) ? $_POST['date'] : '';
$selected_seats = isset($_POST['selected_seats']) ? trim($_POST['selected_seats']) : '';

if (!$bus_id || !$date || empty($selected_seats)) {
    header("Location: ../index.php");
    exit();
}

// Convert seat list to array
$seats = explode(",", $selected_seats);

// Fetch fare from buses table
$stmt = $conn->prepare("SELECT fare FROM buses WHERE id = ?");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

if (!$bus) {
    die("Invalid bus ID or missing fare column.");
}

$fare_per_seat = $bus['fare'];
$total_amount = $fare_per_seat * count($seats);

// Prepare insert statement for bookings
$insert = $conn->prepare("
    INSERT INTO bookings (user_id, bus_id, booking_date, seat_number, total_amount, status)
    VALUES (?, ?, ?, ?, ?, 'confirmed')
");

// Insert each booked seat
foreach ($seats as $seat) {
    $seat = trim($seat);
    if (!empty($seat)) {
        $insert->bind_param("iissd", $user_id, $bus_id, $date, $seat, $fare_per_seat);
        $insert->execute();
    }
}

// Close connections
$insert->close();
$stmt->close();
$conn->close();


header("Location: ../booking-success.php?bus_id=$bus_id&date=$date&seats=$selected_seats");
exit();
?>

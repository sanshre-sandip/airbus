<?php
header('Content-Type: application/json');
require_once 'config.php';

$bus_id = isset($_GET['bus_id']) ? (int)$_GET['bus_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : '';

if (!$bus_id || !$date) {
    echo json_encode(['error' => 'Invalid parameters']);
    exit();
}

// Get total seats for the bus
$total_sql = "SELECT total_seats FROM buses WHERE id = ?";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param("i", $bus_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$bus = $total_result->fetch_assoc();

if (!$bus) {
    echo json_encode(['error' => 'Bus not found']);
    exit();
}

$booked_sql = "SELECT COUNT(*) as booked FROM bookings 
               WHERE bus_id = ? AND booking_date = ? AND status != 'cancelled'";
$booked_stmt = $conn->prepare($booked_sql);
$booked_stmt->bind_param("is", $bus_id, $date);
$booked_stmt->execute();
$booked_result = $booked_stmt->get_result();
$booked = $booked_result->fetch_assoc();

$available = $bus['total_seats'] - $booked['booked'];

echo json_encode([
    'total' => $bus['total_seats'],
    'booked' => $booked['booked'],
    'available' => $available
]);
?>
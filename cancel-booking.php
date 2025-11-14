<?php
session_start();
require_once 'backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Only cancel booking if it belongs to the logged-in user and is not already cancelled
    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'confirmed'");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['msg'] = "Booking cancelled successfully.";
    } else {
        $_SESSION['msg'] = "Booking could not be cancelled.";
    }

    $stmt->close();
}

header("Location: profile.php");
exit();
?>

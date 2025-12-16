<?php
session_start();
require_once 'backend/config.php';

// Ensure user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];

    if ($photo['error'] === 0) {
        // Create uploads folder if not exists
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique file name
        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
        $new_name = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $target_path = $upload_dir . $new_name;

        // Check if folder is writable
        if (is_writable($upload_dir)) {
            if (move_uploaded_file($photo['tmp_name'], $target_path)) {
                // Save only filename to DB
                $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
                $stmt->bind_param("si", $new_name, $user_id);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<script>alert('❌ File move failed!');</script>";
            }
        } else {
            echo "<script>alert('❌ Upload folder not writable!');</script>";
        }
    } else {
        echo "<script>alert('❌ Upload error code: " . $photo['error'] . "');</script>";
    }

    header("Location: profile.php");
    exit();
}

// Fetch user info
$user_sql = "SELECT name, email, photo, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch bookings with routes
$bookings_sql = "
    SELECT 
        b.id, 
        bs.bus_name,
        r.from_location, 
        r.to_location, 
        r.departure_time, 
        r.arrival_time, 
        r.fare, 
        b.booking_date, 
        b.seat_number, 
        b.total_amount, 
        b.status 
    FROM bookings b
    JOIN buses bs ON b.bus_id = bs.id
    JOIN routes r ON bs.route_id = r.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
";
$stmt = $conn->prepare($bookings_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();
?>

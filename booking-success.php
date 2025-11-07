<?php
session_start();
require_once 'backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Passenger';
$bus_id = isset($_GET['bus_id']) ? (int)$_GET['bus_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : '';
$seats = isset($_GET['seats']) ? explode(',', $_GET['seats']) : [];

if (!$bus_id || !$date) {
    header("Location: index.php");
    exit();
}

// Fetch bus details
$sql = "SELECT b.*, r.from_location, r.to_location, r.departure_time, r.arrival_time, r.fare
        FROM buses b
        JOIN routes r ON b.route_id = r.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

$total_amount = count($seats) * $bus['fare'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg border-t-8 border-blue-700">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-blue-700 text-center mb-6">Bus Booking Ticket</h1>
            
            <div class="border-b border-gray-300 pb-4 mb-4">
                <p class="text-lg font-semibold">Passenger: <span class="font-normal"><?php echo htmlspecialchars($user_name); ?></span></p>
                <p class="text-lg font-semibold">Booking Date: <span class="font-normal"><?php echo date('d M Y', strtotime($date)); ?></span></p>
                <p class="text-lg font-semibold">Booking ID: <span class="font-normal text-gray-600">#<?php echo rand(100000, 999999); ?></span></p>
            </div>

            <div class="mb-4">
                <p class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($bus['bus_name']); ?></p>
                <div class="grid grid-cols-2 gap-2 text-gray-700">
                    <p><strong>Bus No:</strong> <?php echo htmlspecialchars($bus['bus_number']); ?></p>
                    <p><strong>Route:</strong> <?php echo htmlspecialchars($bus['from_location']); ?> → <?php echo htmlspecialchars($bus['to_location']); ?></p>
                    <p><strong>Departure:</strong> <?php echo date('h:i A', strtotime($bus['departure_time'])); ?></p>
                    <p><strong>Arrival:</strong> <?php echo date('h:i A', strtotime($bus['arrival_time'])); ?></p>
                </div>
            </div>

            <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                <p class="font-semibold text-gray-800 mb-1">Selected Seats:</p>
                <p class="text-blue-700 font-bold text-lg">
                    <?php echo implode(', ', array_map('htmlspecialchars', $seats)); ?>
                </p>
            </div>

            <div class="flex justify-between items-center border-t border-gray-300 pt-4">
                <p class="text-lg font-semibold text-gray-800">Total Fare</p>
                <p class="text-xl font-bold text-blue-700">Rs. <?php echo number_format($total_amount, 2); ?></p>
            </div>

            <div class="text-center mt-6">
                <a href="index.php" class="bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition">Back to Home</a>
            </div>
        </div>

        <div class="bg-gray-200 h-10 rounded-b-xl flex items-center justify-center text-gray-600 text-sm tracking-wider">
            Thank you for booking with BusBooking System
        </div>
    </div>

</body>
</html>

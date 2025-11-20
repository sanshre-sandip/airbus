<?php
session_start();
require_once 'backend/config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_name = $_SESSION['user_name'] ?? 'Passenger';
$bus_id = isset($_GET['bus_id']) ? (int) $_GET['bus_id'] : 0;
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

// ✅ Generate booking ID once per session (so it doesn’t change on refresh)
if (!isset($_SESSION['booking_id'])) {
  $_SESSION['booking_id'] = rand(100000, 999999);
}
$booking_id = $_SESSION['booking_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BusGo Ticket - Booking Details</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <canvas id="globalParticleCanvas"></canvas>

  <div class="glass-card rounded-2xl w-full max-w-2xl p-8 ticket-shadow border-t-4 border-purple-500 fade-in-up">
    <h1 class="text-3xl font-extrabold gradient-text text-center mb-8">🎫 BusGo Ticket Confirmation</h1>

    <!-- Passenger Info -->
    <div class="border-b border-white/10 pb-4 mb-6">
      <p class="text-lg font-semibold">👤 Passenger:
        <span class="font-normal text-gray-300"><?php echo htmlspecialchars($user_name); ?></span>
      </p>
      <p class="text-lg font-semibold">📅 Booking-Date:
        <span class="font-normal text-gray-300"><?php echo date('d M Y', strtotime($date)); ?></span>
      </p>
      <p class="text-lg font-semibold">🆔 Booking ID:
        <span class="font-normal text-purple-400">#<?php echo $booking_id; ?>
        </span>
      </p>
    </div>

    <!-- Bus Details -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-purple-400 mb-3"><?php echo htmlspecialchars($bus['bus_name']); ?></h2>
      <div class="grid grid-cols-2 gap-3 text-gray-300">
        <p><strong>🚌 Bus No:</strong> <?php echo htmlspecialchars($bus['bus_number']); ?></p>
        <p><strong>📍 Route:</strong> <?php echo htmlspecialchars($bus['from_location']); ?> →
          <?php echo htmlspecialchars($bus['to_location']); ?>
        </p>
        <p><strong>⏰ Departure:</strong> <?php echo date('h:i A', strtotime($bus['departure_time'])); ?></p>
        <p><strong>🕓 Arrival:</strong> <?php echo date('h:i A', strtotime($bus['arrival_time'])); ?></p>
      </div>
    </div>

    <!-- Selected Seats -->
    <div class="bg-white/10 p-4 rounded-xl border border-white/10 mb-6">
      <p class="font-semibold text-gray-200 mb-1">🎟️ Selected Seats:</p>
      <p class="text-purple-400 font-bold text-lg">
        <?php echo implode(', ', array_map('htmlspecialchars', $seats)); ?>
      </p>
    </div>

    <!-- Fare -->
    <div class="flex justify-between items-center border-t border-white/10 pt-4">
      <p class="text-lg font-semibold text-gray-200">Total Fare</p>
      <p class="text-2xl font-bold text-purple-400">Rs. <?php echo number_format($total_amount, 2); ?></p>
    </div>

    <!-- Buttons -->
    <div class="text-center mt-8">
      <a href="index.php"
        class="inline-block gradient-bg text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition transform hover:-translate-y-1">
        Back to Home
      </a>
    </div>

    <!-- Footer -->
    <div class="text-center mt-8 text-gray-400 text-sm border-t border-white/10 pt-3">
      Thank you for booking with <span class="gradient-text font-semibold">BusGo</span> 🚌<br>
      Have a safe and comfortable journey!
    </div>
  </div>

  <script src="assets/js/particles.js"></script>
</body>

</html>
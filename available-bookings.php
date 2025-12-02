<?php
session_start();
require_once 'backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all available buses and their routes
$sql = "
    SELECT 
        b.id AS bus_id, 
        b.bus_name, 
        b.bus_number, 
        b.total_seats, 
        r.from_location, 
        r.to_location, 
        r.departure_time, 
        r.arrival_time, 
        r.fare,
        (b.total_seats - COALESCE(SUM(bookings_count.count), 0)) AS available_seats
    FROM buses b
    JOIN routes r ON b.route_id = r.id
    LEFT JOIN (
        SELECT bus_id, COUNT(*) AS count 
        FROM bookings 
        WHERE status != 'cancelled'
        GROUP BY bus_id
    ) AS bookings_count ON b.id = bookings_count.bus_id
    GROUP BY b.id
    ORDER BY r.departure_time ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Bookings - BusGo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    * { font-family: 'Inter', sans-serif; }

    body {
      background: #0f0f1e;
      color: #fff;
    }

    .gradient-text {
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 15px rgba(118, 75, 162, 0.4);
    }

    .gradient-bg {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .nav-blur {
      background: rgba(15, 15, 30, 0.9);
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body>


<?php
include 'nav.php';
?>
  <!-- Main Section -->
  <section class="pt-28 pb-20 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-4xl font-bold text-center mb-12 gradient-text">Available Buses</h2>

      <?php if ($result && $result->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="glass-card rounded-2xl p-6">
              <h3 class="text-2xl font-bold mb-3 gradient-text">
                <?php echo htmlspecialchars($row['bus_name']); ?>
              </h3>
              <p class="text-gray-400 mb-1">🚌 Bus No: <span class="text-gray-200"><?php echo htmlspecialchars($row['bus_number']); ?></span></p>
              <p class="text-gray-400 mb-1">📍 From: <span class="text-gray-200"><?php echo htmlspecialchars($row['from_location']); ?></span></p>
              <p class="text-gray-400 mb-1">🎯 To: <span class="text-gray-200"><?php echo htmlspecialchars($row['to_location']); ?></span></p>
              <p class="text-gray-400 mb-1">⏰ Departure: <span class="text-gray-200"><?php echo date('h:i A', strtotime($row['departure_time'])); ?></span></p>
              <p class="text-gray-400 mb-1">🏁 Arrival: <span class="text-gray-200"><?php echo date('h:i A', strtotime($row['arrival_time'])); ?></span></p>
              <p class="text-gray-400 mb-1">💰 Fare: <span class="text-gray-200">Rs. <?php echo number_format($row['fare'], 2); ?></span></p>
              <p class="text-gray-400 mb-6">🪑 Available Seats: <span class="text-gray-200"><?php echo max($row['available_seats'], 0); ?></span></p>

              <!-- Book Now Button -->
              <a href="book.php?bus_id=<?php echo $row['bus_id']; ?>&date=<?php echo date('Y-m-d'); ?>&from=<?php echo urlencode($row['from_location']); ?>&to=<?php echo urlencode($row['to_location']); ?>"
                class="block text-center gradient-bg text-white font-semibold py-3 rounded-xl hover:shadow-lg hover:shadow-purple-500/40 transition">
                Book Now
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="text-center text-gray-400 mt-16">
          <p class="text-lg">No available buses found.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <?php
include 'footer.php';
?>

 
</body>
</html>

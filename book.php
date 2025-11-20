<?php
session_start();
require_once 'backend/config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$bus_id = isset($_GET['bus_id']) ? (int) $_GET['bus_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : '';

if (!$bus_id || !$date) {
  header("Location: index.php");
  exit();
}

// Get bus details
$sql = "SELECT b.*, r.from_location, r.to_location, r.departure_time, r.arrival_time, r.fare
        FROM buses b
        JOIN routes r ON b.route_id = r.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

if (!$bus) {
  header("Location: index.php");
  exit();
}

// Get booked seats
$booked_sql = "SELECT seat_number FROM bookings 
               WHERE bus_id = ? AND booking_date = ? AND status != 'cancelled'";
$booked_stmt = $conn->prepare($booked_sql);
$booked_stmt->bind_param("is", $bus_id, $date);
$booked_stmt->execute();
$booked_result = $booked_stmt->get_result();
$booked_seats = [];
while ($row = $booked_result->fetch_assoc()) {
  $booked_seats[] = $row['seat_number'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Seats - BusGo</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <canvas id="globalParticleCanvas"></canvas>

  <!-- Navbar -->
  <nav class="fixed top-0 left-0 w-full z-50 nav-blur border-b border-white/10">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <a href="index.php" class="text-2xl font-bold gradient-text">🚌 BusGo</a>
      <div class="flex items-center space-x-4 text-gray-200">
        <ul class="hidden md:flex space-x-8">
          <li><a href="index.php" class="hover:text-purple-400 transition">Home</a></li>
          <li><a href="available-bookings.php" class="hover:text-purple-400 transition">Booking Available</a></li>

        </ul>


        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="backend/logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition">Logout</a>
      </div>
    </div>
  </nav>

  <div class="max-w-6xl mx-auto pt-28 px-4 pb-16">

    <!-- Bus Info -->
    <div class="glass-card p-8 rounded-2xl mb-10 shadow-lg">
      <h2 class="text-3xl font-bold mb-6 gradient-text">Select Your Seats</h2>
      <div class="grid md:grid-cols-2 gap-8 text-gray-300">
        <div>
          <p class="text-lg font-semibold text-white"><?php echo htmlspecialchars($bus['bus_name']); ?></p>
          <p>Bus Number: <?php echo htmlspecialchars($bus['bus_number']); ?></p>
        </div>
        <div>
          <p><strong>From:</strong> <?php echo htmlspecialchars($bus['from_location']); ?></p>
          <p><strong>To:</strong> <?php echo htmlspecialchars($bus['to_location']); ?></p>
          <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($date)); ?></p>
          <p><strong>Departure:</strong> <?php echo date('h:i A', strtotime($bus['departure_time'])); ?></p>
        </div>
      </div>
    </div>

    <!-- Seat Layout -->
    <div class="glass-card p-8 rounded-2xl mb-10 shadow-lg text-center">
      <h3 class="text-2xl font-semibold mb-6 gradient-text">Choose Your Seats</h3>
      <div id="seatMap" class="inline-block">
        <?php
        $total_seats = $bus['total_seats'];
        $rows = ceil($total_seats / 4);
        for ($i = 0; $i < $rows; $i++) {
          echo '<div class="flex justify-center gap-8 mb-4">';
          for ($j = 0; $j < 4; $j++) {
            $seat_num = ($i * 4) + $j + 1;
            if ($seat_num <= $total_seats) {
              $seat_label = 'S' . str_pad($seat_num, 2, '0', STR_PAD_LEFT);
              $booked = in_array($seat_label, $booked_seats) ? ' booked' : '';
              echo "<div class='seat$booked' data-seat='$seat_label'>$seat_label</div>";
            }
          }
          echo '</div>';
        }
        ?>
      </div>
    </div>

    <!-- Booking Summary -->
    <div class="glass-card p-8 rounded-2xl shadow-lg">
      <h3 class="text-2xl font-bold mb-4 gradient-text">Booking Summary</h3>
      <div class="mb-6 text-gray-300">
        <p>Fare per seat: Rs. <span id="farePerSeat"><?php echo number_format($bus['fare'], 2); ?></span></p>
        <p>Selected seats: <span id="selectedSeatsText" class="text-white">None</span></p>
      </div>
      <form id="bookingForm" action="backend/process-booking.php" method="POST">
        <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
        <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="">
        <button type="submit" id="bookButton" disabled
          class="w-full py-3 rounded-xl font-semibold text-white gradient-bg hover:shadow-lg hover:shadow-purple-500/40 transition disabled:opacity-50 disabled:cursor-not-allowed">
          Confirm Booking
        </button>
      </form>
    </div>

  </div>



  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const seatMap = document.getElementById('seatMap');
      const selectedSeatsText = document.getElementById('selectedSeatsText');
      const selectedSeatsInput = document.getElementById('selectedSeatsInput');
      const totalAmount = document.getElementById('totalAmount');
      const bookButton = document.getElementById('bookButton');
      const farePerSeat = parseFloat(document.getElementById('farePerSeat').textContent);
      let selectedSeats = [];

      seatMap.addEventListener('click', e => {
        if (e.target.classList.contains('seat') && !e.target.classList.contains('booked')) {
          const seat = e.target.dataset.seat;
          e.target.classList.toggle('selected');

          if (selectedSeats.includes(seat)) {
            selectedSeats = selectedSeats.filter(s => s !== seat);
          } else {
            selectedSeats.push(seat);
          }

          selectedSeatsText.textContent = selectedSeats.length ? selectedSeats.join(', ') : 'None';
          totalAmount.textContent = (selectedSeats.length * farePerSeat).toFixed(2);
          selectedSeatsInput.value = selectedSeats.join(',');
          bookButton.disabled = selectedSeats.length === 0;
        }
      });

      document.getElementById('bookingForm').addEventListener('submit', e => {
        if (selectedSeats.length === 0) {
          e.preventDefault();
          alert('Please select at least one seat.');
        }
      });
    });
  </script>

  <script src="assets/js/particles.js"></script>
</body>

</html>
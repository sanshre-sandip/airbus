<?php
require_once 'backend/config.php';

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';

$buses = [];
if ($from && $to) {
  // Fetch buses that match route
  $stmt = $conn->prepare("
        SELECT 
            b.id AS bus_id, 
            b.bus_name, 
            r.from_location, 
            r.to_location, 
            r.departure_time, 
            r.arrival_time, 
            r.fare
        FROM buses b
        JOIN routes r ON b.route_id = r.id
        WHERE r.from_location LIKE ? AND r.to_location LIKE ?
        ORDER BY r.departure_time ASC
    ");
  $likeFrom = "%$from%";
  $likeTo = "%$to%";
  $stmt->bind_param("ss", $likeFrom, $likeTo);
  $stmt->execute();
  $result = $stmt->get_result();
  $buses = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Buses - BusGo</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <canvas id="globalParticleCanvas"></canvas>
  <?php
  session_start();
  include 'nav.php';
  ?>



  <!-- Header -->
  <section class="pt-32 pb-12 text-center">
    <h2 class="text-4xl font-extrabold mb-3 gradient-text">Available Buses</h2>
    <p class="text-gray-300 text-lg">
      From <span class="font-semibold text-white"><?= htmlspecialchars($from) ?></span>
      To <span class="font-semibold text-white"><?= htmlspecialchars($to) ?></span>
      on <span class="font-semibold text-white"><?= htmlspecialchars($date) ?></span>
    </p>
  </section>

  <!-- Results Section -->
  <section class="max-w-6xl mx-auto pb-20 px-6 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php if (!empty($buses)): ?>
      <?php foreach ($buses as $bus): ?>
        <div class="glass-card rounded-2xl p-6 shadow-lg hover:scale-105 hover:shadow-purple-500/20 transition">
          <h3 class="text-2xl font-bold text-purple-400 mb-2"><?= htmlspecialchars($bus['bus_name']) ?></h3>
          <p class="text-gray-300 mb-1">🕒 Departure: <?= date("g:i A", strtotime($bus['departure_time'])) ?></p>
          <p class="text-gray-300 mb-1">🕕 Arrival: <?= date("g:i A", strtotime($bus['arrival_time'])) ?></p>
          <p class="text-gray-300 mb-1">💺 Available Seats: <?= htmlspecialchars($bus['available_seats'] ?? '-') ?></p>
          <p class="text-gray-300 mb-4">💰 Fare: Rs. <?= htmlspecialchars($bus['fare']) ?></p>
          <a href="book.php?bus_id=<?= $bus['bus_id'] ?>&from=<?= urlencode($from) ?>&to=<?= urlencode($to) ?>&date=<?= urlencode($date) ?>"
            class="block text-center mt-4 gradient-bg py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-purple-500/40 transition">Book
            Now</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-span-3 text-center text-gray-400 text-lg py-12">
        No buses found for this route.
      </div>
    <?php endif; ?>
  </section>



  <script>
    // Navbar shadow on scroll
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('nav');
      if (window.scrollY > 50) nav.classList.add('shadow-lg');
      else nav.classList.remove('shadow-lg');
    });
  </script>
  <?php
  session_start();
  include 'footer.php';
  ?>

  <script src="assets/js/particles.js"></script>
</body>

</html>
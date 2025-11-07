<?php
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - Bus Booking</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Navbar -->
  <nav class="bg-blue-700 text-white p-4 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">BusBooking</h1>
      <ul class="flex space-x-6">
        <li><a href="index.php" class="hover:text-yellow-300">Home</a></li>
        <li><a href="login.php" class="hover:text-yellow-300">Login</a></li>
        <li><a href="register.php" class="hover:text-yellow-300">Register</a></li>
      </ul>
    </div>
  </nav>

  <!-- Header -->
  <section class="bg-blue-600 text-white py-10 text-center">
    <h2 class="text-3xl font-bold mb-2">Available Buses</h2>
    <p>From <span class="font-semibold"><?php echo htmlspecialchars($from); ?></span> 
       To <span class="font-semibold"><?php echo htmlspecialchars($to); ?></span> 
       on <span class="font-semibold"><?php echo htmlspecialchars($date); ?></span></p>
  </section>

  <!-- Results Section -->
  <section class="max-w-5xl mx-auto py-12 px-6">
    <div class="grid md:grid-cols-2 gap-6">

      <!-- Sample Bus 1 -->
      <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-blue-700 mb-2">Mountain Express</h3>
        <p class="text-gray-600 mb-1">Departure: 7:00 AM</p>
        <p class="text-gray-600 mb-1">Arrival: 2:00 PM</p>
        <p class="text-gray-600 mb-4">Fare: Rs. 1200</p>
        <a href="book.php?bus_id=1&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&date=<?php echo urlencode($date); ?>"
           class="inline-block bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800">Book Now</a>
      </div>

      <!-- Sample Bus 2 -->
      <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-blue-700 mb-2">Cityline Travels</h3>
        <p class="text-gray-600 mb-1">Departure: 9:00 AM</p>
        <p class="text-gray-600 mb-1">Arrival: 4:30 PM</p>
        <p class="text-gray-600 mb-4">Fare: Rs. 1000</p>
        <a href="book.php?bus_id=2&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&date=<?php echo urlencode($date); ?>"
           class="inline-block bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800">Book Now</a>
      </div>

      <!-- Sample Bus 3 -->
      <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-blue-700 mb-2">Highway Deluxe</h3>
        <p class="text-gray-600 mb-1">Departure: 6:30 AM</p>
        <p class="text-gray-600 mb-1">Arrival: 1:30 PM</p>
        <p class="text-gray-600 mb-4">Fare: Rs. 1500</p>
        <a href="book.php?bus_id=3&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&date=<?php echo urlencode($date); ?>"
           class="inline-block bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800">Book Now</a>
      </div>

    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-blue-800 text-white py-6 text-center">
    <p>&copy; <?php echo date("Y"); ?> BusBooking System. All rights reserved.</p>
  </footer>

</body>
</html>

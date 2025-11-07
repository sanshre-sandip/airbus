<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Booking System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Navbar -->
  <nav class="bg-blue-700 text-white p-4 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">BusBooking</h1>
      <ul class="flex space-x-6">
        <li><a href="index.php" class="hover:text-yellow-300">Home</a></li>
        <li><a href="#about" class="hover:text-yellow-300">About</a></li>
        <li><a href="login.php" class="hover:text-yellow-300">Login</a></li>
        <li><a href="register.php" class="hover:text-yellow-300">Register</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20">
    <div class="max-w-5xl mx-auto text-center px-6">
      <h2 class="text-4xl font-bold mb-4">Book Your Bus Tickets Easily</h2>
      <p class="text-lg mb-8">Find and reserve seats for your next journey with real-time availability and instant booking.</p>
      <a href="#search" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-md font-semibold hover:bg-yellow-300">Search Buses</a>
    </div>
  </section>

  <!-- Search Section -->
  <section id="search" class="py-16 bg-white">
    <div class="max-w-4xl mx-auto bg-gray-100 p-8 rounded-lg shadow-md">
      <h3 class="text-2xl font-semibold mb-6 text-center">Search for Buses</h3>
      <form action="search-results.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="from" placeholder="From" required class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" name="to" placeholder="To" required class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date" required class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-blue-700 text-white font-semibold p-3 rounded-md hover:bg-blue-800">Search</button>
      </form>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto text-center px-6">
      <h3 class="text-3xl font-bold mb-4 text-blue-700">About Our System</h3>
      <p class="text-gray-700 leading-relaxed mb-6">
        The Bus Booking System is a web platform that simplifies your travel planning by allowing passengers to book tickets online.
        It features real-time seat availability, instant booking confirmation, and route management.
      </p>
      <p class="text-gray-700">
        Our goal is to make traveling easier, faster, and more convenient for everyone.
      </p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-blue-800 text-white py-6">
    <div class="max-w-7xl mx-auto text-center">
      <p>&copy; <?php echo date("Y"); ?> BusBooking System. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>

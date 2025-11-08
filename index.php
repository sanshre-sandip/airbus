<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Booking System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/assets/css/styles.css">
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
<section 
  class="hero text-white flex items-center justify-center bg-cover bg-center bg-no-repeat min-h-[90vh]" 
  style="background-image: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1740&q=80');">

  <div class="bg-black/50 p-10 rounded-lg text-center">
    <h2 class="text-5xl font-bold mb-4">Book Your Bus Tickets Easily</h2>
    <p class="text-lg mb-8">Find and reserve seats for your next journey with real-time availability and instant booking.</p>
    <a href="#search" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-md font-semibold hover:bg-yellow-300 transition">
      Search Buses
    </a>
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


<footer class="bg-blue-900 text-gray-200 py-10 mt-20">
  <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">

    <!-- Logo & Description -->
    <div>
      <h2 class="text-2xl font-bold text-white mb-3">BusBooking</h2>
      <p class="text-sm leading-relaxed">
        Book your next journey with comfort and convenience.  
        Real-time bus schedules, instant booking, and safe travel — all in one place.
      </p>
    </div>

    <!-- Quick Links -->
    <div>
      <h3 class="text-lg font-semibold text-yellow-400 mb-3">Quick Links</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="index.php" class="hover:text-yellow-300">Home</a></li>
        <li><a href="#about" class="hover:text-yellow-300">About</a></li>
        <li><a href="#search" class="hover:text-yellow-300">Search Buses</a></li>
        <li><a href="contact.php" class="hover:text-yellow-300">Contact Us</a></li>
      </ul>
    </div>

    <!-- Customer Support -->
    <div>
      <h3 class="text-lg font-semibold text-yellow-400 mb-3">Customer Support</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="#" class="hover:text-yellow-300">FAQs</a></li>
        <li><a href="#" class="hover:text-yellow-300">Help Center</a></li>
        <li><a href="#" class="hover:text-yellow-300">Terms & Conditions</a></li>
        <li><a href="#" class="hover:text-yellow-300">Privacy Policy</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div>
      <h3 class="text-lg font-semibold text-yellow-400 mb-3">Contact Us</h3>
      <p class="text-sm mb-2">📍 Kathmandu, Nepal</p>
      <p class="text-sm mb-2">📞 +977-9800000000</p>
      <p class="text-sm mb-4">✉️ support@busbooking.com</p>

      <!-- Social Icons -->
      <div class="flex space-x-4 mt-4">
        <a href="#" class="hover:text-yellow-400"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-yellow-400"><i class="fab fa-twitter"></i></a>
        <a href="#" class="hover:text-yellow-400"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
  </div>

  <div class="border-t border-gray-600 mt-10 pt-4 text-center text-sm text-gray-400">
    &copy; <?php echo date("Y"); ?> BusBooking System. All rights reserved.
  </div>
</footer>


</body>
</html>

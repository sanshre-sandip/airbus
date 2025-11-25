<?php
// Start session (important for checking login)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login status
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BusGo - Smart Bus Ticketing System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

  <!-- Enhanced Navbar -->
  <nav class="fixed top-0 w-full z-50 nav-blur nav-animated-border">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <a href="index.php" class="text-2xl font-bold gradient-text tracking-tighter">🚌</a>

      <!-- Desktop Menu -->
      <ul class="hidden md:flex space-x-8 items-center">
        <li><a href="index.php#home" class="hover:text-purple-400 transition font-medium">Home</a></li>
        <li><a href="index.php#search" class="hover:text-purple-400 transition font-medium">Search</a></li>
        <li><a href="index.php#features" class="hover:text-purple-400 transition font-medium">Features</a></li>
        <li><a href="index.php#about" class="hover:text-purple-400 transition font-medium">About</a></li>
        <li><a href="available-bookings.php" class="hover:text-purple-400 transition font-medium">Bookings</a></li>
        <li><a href="profile.php" class="hover:text-purple-400 transition font-medium">Profile</a></li>
      </ul>

      <!-- Desktop Auth Buttons -->
      <div class="hidden md:flex space-x-4 items-center">
        <?php if ($is_logged_in): ?>
          <span class="text-sm text-gray-300">Hi, <?php echo htmlspecialchars($user_name); ?></span>
          <a href="logout.php"
            class="px-4 py-2 border border-white/20 rounded-full hover:bg-white/10 transition text-sm">Logout</a>
        <?php else: ?>
          <a href="login.php" class="px-4 py-2 hover:text-purple-400 transition font-medium">Login</a>
          <a href="register.php"
            class="px-6 py-2 gradient-bg rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition text-sm">Register</a>
        <?php endif; ?>
      </div>

      <!-- Mobile Menu Button -->
      <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>

    <!-- Mobile Menu Drawer -->
    <div id="mobile-menu"
      class="hidden md:hidden bg-[#0a0a0a]/95 backdrop-blur-xl border-b border-white/10 absolute w-full left-0 top-full">
      <ul class="flex flex-col p-6 space-y-4 text-center">
        <li><a href="index.php#home" class="block text-lg hover:text-purple-400 transition">Home</a></li>
        <li><a href="index.php#search" class="block text-lg hover:text-purple-400 transition">Search</a></li>
        <li><a href="index.php#features" class="block text-lg hover:text-purple-400 transition">Features</a></li>
        <li><a href="available-bookings.php" class="block text-lg hover:text-purple-400 transition">Bookings</a></li>
        <li><a href="profile.php" class="block text-lg hover:text-purple-400 transition">Profile</a></li>
        <li class="pt-4 border-t border-white/10">
          <?php if ($is_logged_in): ?>
            <span class="block text-gray-400 mb-2">Signed in as <?php echo htmlspecialchars($user_name); ?></span>
            <a href="logout.php"
              class="block px-4 py-2 border border-white/20 rounded-full mx-auto w-1/2 hover:bg-white/10 transition">Logout</a>
          <?php else: ?>
            <div class="flex flex-col gap-3">
              <a href="login.php" class="block text-lg hover:text-purple-400 transition">Login</a>
              <a href="register.php" class="block px-6 py-3 gradient-bg rounded-xl font-semibold">Register Now</a>
            </div>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </nav>

  <script>
    // Mobile Menu Toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>
</body>

</html>

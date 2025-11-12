!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BusGo - Smart Bus Ticketing System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
    
    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background: #0f0f1e;
      color: #fff;
    }

    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    .float-animation {
      animation: float 3s ease-in-out infinite;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in-up {
      animation: fadeInUp 1s ease;
    }

    .nav-blur {
      background: rgba(15, 15, 30, 0.9);
      backdrop-filter: blur(10px);
    }

    input[type="text"], input[type="date"] {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    input[type="text"]::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    input[type="text"]:focus, input[type="date"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
  </style>
</head>
<body>

  <!-- Enhanced Navbar -->
  <nav class="fixed top-0 w-full z-50 nav-blur border-b border-white/10">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <h1 class="text-2xl font-bold gradient-text">🚌 BusGo</h1>
      <ul class="hidden md:flex space-x-8">
        <li><a href="index.html#home" class="hover:text-purple-400 transition">Home</a></li>
        <li><a href="#search" class="hover:text-purple-400 transition">Search</a></li>
        <li><a href="#features" class="hover:text-purple-400 transition">Features</a></li>
        <li><a href="#about" class="hover:text-purple-400 transition">About</a></li>
        <li><a href="available-bookings.php" class="hover:text-purple-400 transition">Booking Available</a></li>
        <li><a href="profile.php" class="hover:text-purple-400 transition">Profile</a></li>
      </ul>
      <div class="flex space-x-4">
        <?php if ($is_logged_in): ?>
          <span class="px-4 py-2">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
          <a href="logout.php" class="px-4 py-2 hover:text-purple-400 transition">Logout</a>
        <?php else: ?>
          <a href="login.php" class="px-4 py-2 hover:text-purple-400 transition">Login</a>
          <a href="register.php" class="px-6 py-2 gradient-bg rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
        </body>
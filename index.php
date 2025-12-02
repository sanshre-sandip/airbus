<?php
// Start session early so we can render nav based on login state
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? ($_SESSION['user_name'] ?? '') : '';
$is_admin = $is_logged_in && !empty($_SESSION['is_admin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="description" content="BusGo - Smart Bus Ticketing System. Book bus tickets online across Nepal easily, check real-time availability, get instant confirmation, and enjoy the best routes.">
<meta name="keywords" content="bus tickets, online bus booking, Nepal buses, BusGo, travel, bus schedule, real-time booking">
<meta name="author" content="BusGo Team">

<!-- Open Graph / Social Sharing -->
<meta property="og:title" content="BusGo - Smart Bus Ticketing System">
<meta property="og:description" content="Book bus tickets online across Nepal easily. Real-time availability, instant confirmation, best routes.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://busgo.infinityfreeapp.com/">
<meta property="og:image" content="https://busgo.infinityfreeapp.com/assets/images/busgo-og-image.png">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="BusGo - Smart Bus Ticketing System">
<meta name="twitter:description" content="Book bus tickets online across Nepal easily. Real-time availability, instant confirmation, best routes.">
<meta name="twitter:image" content="https://busgo.infinityfreeapp.com/assets/images/busgo-og-image.png">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BusGo | Online Bus Ticket Booking in Nepal, Instant Confirmation</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TravelAgency",
  "name": "BusGo",
  "url": "https://busgo.infinityfreeapp.com/",
  "logo": "https://busgo.infinityfreeapp.com/assets/images/busgo-logo.png",
  "sameAs": [
    "https://www.facebook.com/busgo",
    "https://www.instagram.com/busgo"
  ],
  "description": "BusGo - Book bus tickets online across Nepal. Real-time availability, instant confirmation, best routes.",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+977-9800000000",
    "contactType": "customer service",
    "availableLanguage": ["English","Nepali"]
  }
}
</script>

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
      <div class="p-1 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500">
  <img src="logo.png" alt="logo" class="w-12 h-auto rounded-full">
</div>


      <ul class="hidden md:flex space-x-8">
        <li><a href="#home" class="hover:text-purple-400 transition">Home</a></li>
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

  <!-- Hero Section -->
  <section id="home" class="min-h-screen flex items-center justify-center pt-20 px-6">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <!-- Hero Content -->
      <div class="fade-in-up">
        <h2 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
          Travel Smarter, <br>
          <span class="gradient-text">Book Faster</span>
        </h2>
        <p class="text-xl text-gray-400 mb-8 leading-relaxed">
          Experience seamless bus ticket booking with real-time availability, instant confirmation, and the best routes across the country.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="#search" class="px-8 py-4 gradient-bg rounded-full font-semibold text-lg hover:shadow-lg hover:shadow-purple-500/50 transition transform hover:-translate-y-1">
            Book Your Journey
          </a>
          <a href="#about" class="px-8 py-4 glass-card rounded-full font-semibold text-lg hover:bg-white/10 transition transform hover:-translate-y-1">
            Learn More
          </a>
        </div>
      </div>

      <!-- Hero Visual -->
      <div class="flex justify-center">
        <div class="float-animation">
          <svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="busGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
              </linearGradient>
            </defs>
            <rect x="80" y="120" width="240" height="160" rx="20" fill="url(#busGradient)"/>
            <rect x="100" y="140" width="80" height="60" rx="5" fill="rgba(255,255,255,0.3)"/>
            <rect x="220" y="140" width="80" height="60" rx="5" fill="rgba(255,255,255,0.3)"/>
            <circle cx="130" cy="300" r="25" fill="#2d3748"/>
            <circle cx="270" cy="300" r="25" fill="#2d3748"/>
            <circle cx="130" cy="300" r="15" fill="#4a5568"/>
            <circle cx="270" cy="300" r="15" fill="#4a5568"/>
            <rect x="160" y="240" width="80" height="40" rx="10" fill="rgba(255,255,255,0.2)"/>
          </svg>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Search Section -->
  <section id="search" class="py-20 px-6 bg-gradient-to-b from-[#0f0f1e] to-[#1a1a2e]">
    <div class="max-w-5xl mx-auto">
      <h3 class="text-4xl font-bold text-center mb-12 gradient-text">Book Your Journey</h3>
      
      <div class="glass-card p-8 rounded-2xl">
        <form action="search-results.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="flex flex-col">
            <label class="mb-2 text-sm text-gray-400">From</label>
            <input 
              type="text" 
              name="from" 
              placeholder="Enter departure city" 
              required 
              class="p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
          </div>
          
          <div class="flex flex-col">
            <label class="mb-2 text-sm text-gray-400">To</label>
            <input 
              type="text" 
              name="to" 
              placeholder="Enter destination" 
              required 
              class="p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
          </div>
          
          <div class="flex flex-col">
            <label class="mb-2 text-sm text-gray-400">Date</label>
            <input 
              type="date" 
              name="date" 
              required 
              class="p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
          </div>
          
          <div class="flex items-end">
            <button 
              type="submit" 
              class="w-full p-4 gradient-bg rounded-xl font-semibold text-lg hover:shadow-lg hover:shadow-purple-500/50 transition transform hover:-translate-y-1">
              Search Buses
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 px-6 bg-[#1a1a2e]">
    <div class="max-w-7xl mx-auto">
      <h3 class="text-4xl font-bold text-center mb-4 gradient-text">Why Choose BusGo?</h3>
      <p class="text-center text-gray-400 mb-12 text-lg">Everything you need for a seamless travel experience</p>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition duration-300 group">
          <h4 class="text-2xl font-bold mb-3 group-hover:text-purple-400 transition">Instant Booking</h4>
          <p class="text-gray-400 leading-relaxed">Book your tickets in seconds with our lightning-fast booking system. No hassle, no waiting.</p>
        </div>

        <!-- Feature 2 -->
        <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition duration-300 group">
        
          <h4 class="text-2xl font-bold mb-3 group-hover:text-purple-400 transition">Secure Payments</h4>
          <p class="text-gray-400 leading-relaxed">Your transactions are protected with bank-level encryption and secure payment gateways.</p>
        </div>

        <!-- Feature 3 -->
        <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition duration-300 group">
        
          <h4 class="text-2xl font-bold mb-3 group-hover:text-purple-400 transition">Mobile Ready</h4>
          <p class="text-gray-400 leading-relaxed">Book from anywhere, anytime. Our platform works seamlessly on all devices.</p>
        </div>

        <!-- Feature 4 -->
        <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition duration-300 group">
        
          <h4 class="text-2xl font-bold mb-3 group-hover:text-purple-400 transition">Digital Tickets</h4>
          <p class="text-gray-400 leading-relaxed">Get instant digital tickets delivered to your email and phone. No need to print.</p>
        </div>

        <!-- Feature 5 -->
        <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition duration-300 group">
        
          <h4 class="text-2xl font-bold mb-3 group-hover:text-purple-400 transition">Live Tracking</h4>
          <p class="text-gray-400 leading-relaxed">Track your bus in real-time and get live updates on arrival times and delays.</p>
        </div>

        <!-- Feature 6 -->
        <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition duration-300 group">
        
          <h4 class="text-2xl font-bold mb-3 group-hover:text-purple-400 transition">Best Prices</h4>
          <p class="text-gray-400 leading-relaxed">Compare prices across operators and get the best deals on your bus tickets.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 px-6 bg-gradient-to-b from-[#1a1a2e] to-[#0f0f1e]">
    <div class="max-w-5xl mx-auto">
      <div class="glass-card p-12 rounded-2xl">
        <h3 class="text-4xl font-bold mb-6 text-center gradient-text">About BusGo System</h3>
        <div class="space-y-6 text-gray-300 leading-relaxed text-lg">
          <p>
            The BusGo Booking System is a cutting-edge web platform that revolutionizes your travel planning by allowing passengers to book tickets online with unprecedented ease and convenience.
          </p>
          <p>
            Our platform features real-time seat availability, instant booking confirmation, comprehensive route management, and a user-friendly interface designed with modern travelers in mind.
          </p>
          <p class="text-center mt-8 text-xl font-semibold gradient-text">
            Making traveling easier, faster, and more convenient for everyone.
          </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-12 text-center">
          <div>
            <div class="text-4xl font-bold gradient-text mb-2">1000+</div>
            <div class="text-gray-400">Daily Bookings</div>
          </div>
          <div>
            <div class="text-4xl font-bold gradient-text mb-2">50+</div>
            <div class="text-gray-400">Routes</div>
          </div>
          <div>
            <div class="text-4xl font-bold gradient-text mb-2">98%</div>
            <div class="text-gray-400">Satisfaction</div>
          </div>
          <div>
            <div class="text-4xl font-bold gradient-text mb-2">24/7</div>
            <div class="text-gray-400">Support</div>
          </div>
        </div>
      </div>
    </div>
  </section>



  <script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });

    // Navbar background on scroll
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('nav');
      if (window.scrollY > 50) {
        nav.classList.add('shadow-lg');
      } else {
        nav.classList.remove('shadow-lg');
      }
    });
  </script>

<?php
include 'footer.php';
?>
</body>
</html>
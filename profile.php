<?php
session_start();
require_once 'backend/config.php';

// Ensure user logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
  $photo = $_FILES['photo'];

  if ($photo['error'] === 0) {
    // Create uploads folder if not exists
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    // Generate unique file name
    $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
    $new_name = 'user_' . $user_id . '_' . time() . '.' . $ext;
    $target_path = $upload_dir . $new_name;

    // Check if folder is writable
    if (is_writable($upload_dir)) {
      if (move_uploaded_file($photo['tmp_name'], $target_path)) {
        // Save only filename to DB
        $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
        $stmt->bind_param("si", $new_name, $user_id);
        $stmt->execute();
        $stmt->close();
      } else {
        echo "<script>alert('❌ File move failed!');</script>";
      }
    } else {
      echo "<script>alert('❌ Upload folder not writable!');</script>";
    }
  } else {
    echo "<script>alert('❌ Upload error code: " . $photo['error'] . "');</script>";
  }

  header("Location: profile.php");
  exit();
}

// Fetch user info
$user_sql = "SELECT name, email, photo, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch bookings with routes
$bookings_sql = "
    SELECT 
        b.id, 
        bs.bus_name,
        r.from_location, 
        r.to_location, 
        r.departure_time, 
        r.arrival_time, 
        r.fare, 
        b.booking_date, 
        b.seat_number, 
        b.total_amount, 
        b.status 
    FROM bookings b
    JOIN buses bs ON b.bus_id = bs.id
    JOIN routes r ON bs.route_id = r.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
";
$stmt = $conn->prepare($bookings_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Profile - BusGo</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <canvas id="globalParticleCanvas"></canvas>

  <!-- Navbar -->
  <nav class="fixed top-0 w-full z-50 nav-blur border-b border-white/10">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <h1 class="text-2xl font-bold gradient-text">🚌 </h1>
      <ul class="hidden md:flex space-x-8">
        <li><a href="index.php" class="hover:text-purple-400 transition">Home</a></li>
        <li><a href="available-bookings.php" class="hover:text-purple-400 transition">Booking Available</a></li>
        <li><a href="profile.php" class="text-purple-400 font-semibold">Profile</a></li>
      </ul>
      <a href="logout.php"
        class="px-6 py-2 gradient-bg rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition">Logout</a>
    </div>
  </nav>

  <!-- Profile Section -->
  <div class="max-w-6xl mx-auto mt-32 p-8 glass-card rounded-2xl shadow-lg">
    <div class="flex flex-col md:flex-row items-center md:items-start gap-10 mb-10">
      <img src="uploads/<?php echo htmlspecialchars($user['photo'] ?? 'default.png'); ?>"
        class="w-32 h-32 rounded-full border-4 border-purple-500 object-cover shadow-lg" alt="Profile">
      <div>
        <h2 class="text-4xl font-bold mb-2 gradient-text"><?php echo htmlspecialchars($user['name']); ?></h2>
        <p class="text-gray-300 text-lg mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
        <p class="text-gray-400 text-sm">Member since: <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
      </div>
    </div>

    <!-- Upload Photo -->
    <form action="" method="POST" enctype="multipart/form-data" class="mb-10">
      <label class="block mb-2 font-semibold text-gray-300">Change Profile Picture:</label>
      <div class="flex flex-col md:flex-row items-center gap-4">
        <input type="file" name="photo" accept="image/*"
          class="p-3 rounded-xl bg-white/5 border border-white/10 w-full md:w-1/2 text-gray-200 focus:ring-2 focus:ring-purple-500 transition"
          required>
        <button type="submit"
          class="gradient-bg px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition">Upload</button>
      </div>
    </form>

    <!-- Bookings Table -->
    <h3 class="text-3xl font-bold mb-6 gradient-text">Your Bookings</h3>
    <div class="overflow-x-auto glass-card rounded-xl">
      <table class="min-w-full text-left border-collapse">
        <thead class="bg-white/10 text-gray-200">
          <tr>
            <th class="px-4 py-3">Bus</th>
            <th class="px-4 py-3">Route</th>
            <th class="px-4 py-3">Departure</th>
            <th class="px-4 py-3">Arrival</th>
            <th class="px-4 py-3">Seat</th>
            <th class="px-4 py-3">Fare (Rs.)</th>
            <th class="px-4 py-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($bookings->num_rows > 0): ?>
            <?php while ($row = $bookings->fetch_assoc()): ?>
              <tr class="hover:bg-white/5 transition">
                <td class="px-4 py-3"><?php echo htmlspecialchars($row['bus_name']); ?></td>
                <td class="px-4 py-3 text-gray-300">
                  <?php echo htmlspecialchars($row['from_location']) . ' → ' . htmlspecialchars($row['to_location']); ?>
                </td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($row['departure_time']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($row['arrival_time']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($row['seat_number']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($row['fare']); ?></td>
                <td class="px-4 py-3 flex items-center gap-2">
                  <span class="px-3 py-1 rounded-lg text-white 
    <?php echo $row['status'] === 'confirmed'
      ? 'bg-green-500/80'
      : ($row['status'] === 'cancelled' ? 'bg-red-500/80' : 'bg-yellow-500/80'); ?>">
                    <?php echo ucfirst($row['status']); ?>
                  </span>

                  <?php if ($row['status'] === 'confirmed'): ?>
                    <form action="cancel-booking.php" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                      <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                      <button type="submit" class="px-3 py-1 bg-red-600 rounded-lg hover:bg-red-700 text-white text-sm">
                        Cancel
                      </button>
                    </form>
                  <?php endif; ?>
                </td>


              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-6 text-gray-400">No bookings found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('nav');
      if (window.scrollY > 50) nav.classList.add('shadow-lg');
      else nav.classList.remove('shadow-lg');
    });
  </script>
  <br><br>
  <?php include 'footer.php'; ?>
  <script src="assets/js/particles.js"></script>
</body>

</html>
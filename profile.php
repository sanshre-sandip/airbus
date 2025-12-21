<?php
session_start();
session_regenerate_id(true);

require_once 'backend/config.php';

/* -------------------------
   Ensure user logged in
-------------------------- */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/* -------------------------
   Handle profile photo upload
-------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {

    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if ($_FILES['photo']['error'] === 0) {

        $mime = mime_content_type($_FILES['photo']['tmp_name']);
        if (!in_array($mime, $allowed_types)) {
            header("Location: profile.php");
            exit();
        }

        if ($_FILES['photo']['size'] > $max_size) {
            header("Location: profile.php");
            exit();
        }

        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $new_name = 'user_' . $user_id . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $target_path = $upload_dir . $new_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {

            // Fetch old photo
            $old_stmt = $conn->prepare("SELECT photo FROM users WHERE id = ?");
            $old_stmt->bind_param("i", $user_id);
            $old_stmt->execute();
            $old_photo = $old_stmt->get_result()->fetch_assoc()['photo'] ?? null;
            $old_stmt->close();

            // Delete old photo if exists
            if ($old_photo && $old_photo !== 'default.png') {
                @unlink($upload_dir . $old_photo);
            }

            // Update new photo
            $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
            $stmt->bind_param("si", $new_name, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: profile.php");
    exit();
}

/* -------------------------
   Fetch user info
-------------------------- */
$user_sql = "SELECT name, email, photo, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* -------------------------
   Fetch user bookings
-------------------------- */
$bookings_sql = "
    SELECT 
        b.id,
        bs.bus_name,
        r.from_location,
        r.to_location,
        r.departure_datetime,
        r.arrival_datetime,
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

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
* { font-family: 'Inter', sans-serif; }
body { background: #0f0f1e; color: #fff; }
.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.gradient-bg {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.glass-card {
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.1);
}
.nav-blur {
  background: rgba(15,15,30,0.9);
  backdrop-filter: blur(10px);
}
table th, table td {
  border-color: rgba(255,255,255,0.1);
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="fixed top-0 w-full z-50 nav-blur border-b border-white/10">
  <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
    <h1 class="text-2xl font-bold gradient-text">🚌 BusGo</h1>
    <ul class="hidden md:flex space-x-8">
      <li><a href="index.php" class="hover:text-purple-400">Home</a></li>
      <li><a href="available-bookings.php" class="hover:text-purple-400">Booking Available</a></li>
      <li><a href="profile.php" class="text-purple-400 font-semibold">Profile</a></li>
    </ul>
    <a href="logout.php" class="px-6 py-2 gradient-bg rounded-full font-semibold">Logout</a>
  </div>
</nav>

<!-- Profile -->
<div class="max-w-6xl mx-auto mt-32 p-8 glass-card rounded-2xl">

  <div class="flex flex-col md:flex-row items-center gap-10 mb-10">
    <img src="uploads/<?php echo htmlspecialchars($user['photo'] ?: 'default.png'); ?>"
         class="w-32 h-32 rounded-full border-4 border-purple-500 object-cover">
    <div>
      <h2 class="text-4xl font-bold gradient-text"><?php echo htmlspecialchars($user['name']); ?></h2>
      <p class="text-gray-300"><?php echo htmlspecialchars($user['email']); ?></p>
      <p class="text-gray-400 text-sm">
        Member since: <?php echo date("F j, Y", strtotime($user['created_at'])); ?>
      </p>
    </div>
  </div>

  <!-- Upload -->
  <form method="POST" enctype="multipart/form-data" class="mb-10">
    <label class="block mb-2 text-gray-300 font-semibold">Change Profile Picture</label>
    <div class="flex flex-col md:flex-row gap-4">
      <input type="file" name="photo" accept="image/*" required
        class="p-3 rounded-xl bg-white/5 border border-white/10 w-full md:w-1/2">
      <button class="gradient-bg px-6 py-3 rounded-xl font-semibold">Upload</button>
    </div>
  </form>

  <!-- Bookings -->
  <h3 class="text-3xl font-bold mb-6 gradient-text">Your Bookings</h3>

  <div class="overflow-x-auto glass-card rounded-xl">
    <table class="min-w-full text-left border-collapse">
      <thead class="bg-white/10">
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

      <?php if ($bookings->num_rows): ?>
        <?php while ($row = $bookings->fetch_assoc()): ?>
        <tr class="hover:bg-white/5">
          <td class="px-4 py-3"><?php echo htmlspecialchars($row['bus_name']); ?></td>
          <td class="px-4 py-3"><?php echo htmlspecialchars($row['from_location'].' → '.$row['to_location']); ?></td>
          <td class="px-4 py-3"><?php echo date('M j, Y h:i A', strtotime($row['departure_datetime'])); ?></td>
          <td class="px-4 py-3"><?php echo date('M j, Y h:i A', strtotime($row['arrival_datetime'])); ?></td>
          <td class="px-4 py-3"><?php echo htmlspecialchars($row['seat_number']); ?></td>
          <td class="px-4 py-3"><?php echo htmlspecialchars($row['fare']); ?></td>
          <td class="px-4 py-3 flex gap-2 items-center">
            <span class="px-3 py-1 rounded-lg
              <?php echo $row['status']=='confirmed'?'bg-green-500/80':($row['status']=='cancelled'?'bg-red-500/80':'bg-yellow-500/80'); ?>">
              <?php echo ucfirst($row['status']); ?>
            </span>

            <?php if ($row['status'] === 'confirmed'): ?>
            <form action="cancel-booking.php" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this booking?');">
              <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
              <button class="px-3 py-1 bg-red-600 rounded-lg text-sm">Cancel</button>
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

<?php include 'footer.php'; ?>
</body>
</html>

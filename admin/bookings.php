<?php
session_start();
require_once '../backend/config.php';

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch all bookings with users, buses, and routes
$sql = "
    SELECT 
        b.id AS booking_id,
        u.name AS user_name,
        bs.bus_name,
        bs.bus_number,
        r.from_location,
        r.to_location,
        b.booking_date,
        b.seat_number,
        b.total_amount,
        b.status,
        b.created_at
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN buses bs ON b.bus_id = bs.id
    JOIN routes r ON bs.route_id = r.id
    ORDER BY b.created_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">🚌 Admin Dashboard</h1>
            <div>
                <a href="dashboard.php" class="mr-4 hover:text-gray-200">Dashboard</a>
                <a href="../backend/logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6">All Bookings</h2>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full border-collapse border border-gray-200 text-sm">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">#</th>
                        <th class="border px-4 py-2 text-left">User</th>
                        <th class="border px-4 py-2 text-left">Bus</th>
                        <th class="border px-4 py-2 text-left">Route</th>
                        <th class="border px-4 py-2 text-left">Date</th>
                        <th class="border px-4 py-2 text-left">Seat</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Booked At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2"><?php echo $row['booking_id']; ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td class="border px-4 py-2">
                                    <?php echo htmlspecialchars($row['bus_name']); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo htmlspecialchars($row['bus_number']); ?></span>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo htmlspecialchars($row['from_location']); ?> →
                                    <?php echo htmlspecialchars($row['to_location']); ?>
                                </td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['seat_number']); ?></td>
                                <td class="border px-4 py-2">
                                    <span class="<?php 
                                        echo $row['status'] == 'confirmed' ? 'text-green-600' : 
                                            ($row['status'] == 'cancelled' ? 'text-red-600' : 'text-yellow-600'); 
                                    ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-gray-500 text-xs"><?php echo $row['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="border px-4 py-4 text-center text-gray-500">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

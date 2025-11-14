<?php
session_start();
require_once '../backend/config.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch stats
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users WHERE is_admin = 0")->fetch_assoc()['count'];
$total_routes = $conn->query("SELECT COUNT(*) AS count FROM routes")->fetch_assoc()['count'];
$total_buses = $conn->query("SELECT COUNT(*) AS count FROM buses")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(r.fare) AS total FROM bookings b 
    JOIN buses bs ON b.bus_id = bs.id 
    JOIN routes r ON bs.route_id = r.id 
    WHERE b.status = 'confirmed'")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bus Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Sidebar -->
    <div class="flex min-h-screen">
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 text-center border-b border-blue-500">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>
            <nav class="flex-1 p-4 space-y-3">
                <a href="dashboard.php" class="block px-4 py-2 rounded bg-blue-800">Dashboard</a>
                <a href="routes.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Routes</a>
                <a href="buses.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Buses</a>
                <a href="bookings.php" class="block px-4 py-2 rounded hover:bg-blue-800">bookings</a>
                <a href="help-center.php" class="block px-4 py-2 rounded hover:bg-blue-800">help center</a>
                            </nav>
            <div class="p-4 border-t border-blue-500">
                <a href="../backend/logout.php" class="btn btn-danger">Logout</a>

                <a href="logout.php" class="block text-center bg-red-500 py-2 rounded hover:bg-red-600">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h2 class="text-3xl font-bold text-gray-700 mb-8">Dashboard Overview</h2>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Users</h3>
                    <p class="text-3xl font-bold text-blue-700 mt-2"><?php echo $total_users; ?></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Routes</h3>
                    <p class="text-3xl font-bold text-blue-700 mt-2"><?php echo $total_routes; ?></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Buses</h3>
                    <p class="text-3xl font-bold text-blue-700 mt-2"><?php echo $total_buses; ?></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Bookings</h3>
                    <p class="text-3xl font-bold text-blue-700 mt-2"><?php echo $total_bookings; ?></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Revenue</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">Rs. <?php echo number_format($total_revenue, 2); ?></p>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4 text-gray-700">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border text-left">User</th>
                                <th class="px-4 py-2 border text-left">Bus</th>
                                <th class="px-4 py-2 border text-left">Seat</th>
                                <th class="px-4 py-2 border text-left">Date</th>
                                <th class="px-4 py-2 border text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent = $conn->query("SELECT b.*, u.name AS user_name, bs.bus_name 
                                FROM bookings b 
                                JOIN users u ON b.user_id = u.id
                                JOIN buses bs ON b.bus_id = bs.id
                                ORDER BY b.created_at DESC LIMIT 8");
                            if ($recent->num_rows > 0) {
                                while ($row = $recent->fetch_assoc()) {
                                    echo "<tr>
                                        <td class='border px-4 py-2'>{$row['user_name']}</td>
                                        <td class='border px-4 py-2'>{$row['bus_name']}</td>
                                        <td class='border px-4 py-2'>{$row['seat_number']}</td>
                                        <td class='border px-4 py-2'>" . date('d M Y', strtotime($row['booking_date'])) . "</td>
                                        <td class='border px-4 py-2'>
                                            <span class='px-2 py-1 rounded text-sm " . 
                                            ($row['status'] == 'confirmed' ? 'bg-green-100 text-green-700' : 
                                            ($row['status'] == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) . "'>
                                            {$row['status']}
                                            </span>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-4 text-gray-500'>No bookings yet</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<?php
session_start();
require_once '../backend/config.php';

// ✅ Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// ✅ Add new route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_route'])) {
    $from_location = trim($_POST['from_location']);
    $to_location = trim($_POST['to_location']);
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $fare = floatval($_POST['fare']);

    if ($from_location && $to_location && $departure_time && $arrival_time && $fare > 0) {
        $stmt = $conn->prepare("
            INSERT INTO routes (from_location, to_location, departure_time, arrival_time, fare) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssd", $from_location, $to_location, $departure_time, $arrival_time, $fare);
        $stmt->execute();
    }

    header("Location: routes.php");
    exit();
}

// ✅ Delete route
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM routes WHERE id = $id");
    header("Location: routes.php");
    exit();
}

// ✅ Fetch all routes
$routes = $conn->query("SELECT * FROM routes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Routes - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 text-center border-b border-blue-500">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>
            <nav class="flex-1 p-4 space-y-3">
                <a href="dashboard.php" class="block px-4 py-2 rounded hover:bg-blue-800">Dashboard</a>
                <a href="routes.php" class="block px-4 py-2 rounded bg-blue-800">Manage-Routes</a>
                <a href="buses.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage-Buses</a>
                <a href="bookings.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage-Bookings</a>
                <a href="users.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Users</a>
            </nav>
            <div class="p-4 border-t border-blue-500">
                <a href="../logout.php" class="block text-center bg-red-500 py-2 rounded hover:bg-red-600">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h2 class="text-3xl font-bold text-gray-700 mb-8">Manage Routes</h2>

            <!-- Add Route Form -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Add New Route</h3>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="text" name="from_location" placeholder="From Location" required
                        class="p-2 border rounded focus:outline-blue-500">
                    <input type="text" name="to_location" placeholder="To Location" required
                        class="p-2 border rounded focus:outline-blue-500">
                    <input type="time" name="departure_time" required class="p-2 border rounded focus:outline-blue-500">
                    <input type="time" name="arrival_time" required class="p-2 border rounded focus:outline-blue-500">
                    <input type="number" step="0.01" name="fare" placeholder="Fare (Rs.)" required
                        class="p-2 border rounded focus:outline-blue-500">
                    <button type="submit" name="add_route"
                        class="col-span-full bg-blue-700 text-white py-2 rounded hover:bg-blue-800 transition">Add
                        Route</button>
                </form>
            </div>

            <!-- Routes Table -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">All Routes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2">#</th>
                                <th class="border px-4 py-2">From</th>
                                <th class="border px-4 py-2">To</th>
                                <th class="border px-4 py-2">Departure</th>
                                <th class="border px-4 py-2">Arrival</th>
                                <th class="border px-4 py-2">Fare (Rs.)</th>
                                <th class="border px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($routes->num_rows > 0) {
                                $i = 1;
                                while ($r = $routes->fetch_assoc()) {
                                    echo "
                                <tr class='hover:bg-gray-50'>
                                    <td class='border px-4 py-2'>{$i}</td>
                                    <td class='border px-4 py-2'>{$r['from_location']}</td>
                                    <td class='border px-4 py-2'>{$r['to_location']}</td>
                                    <td class='border px-4 py-2'>{$r['departure_time']}</td>
                                    <td class='border px-4 py-2'>{$r['arrival_time']}</td>
                                    <td class='border px-4 py-2'>Rs. {$r['fare']}</td>
                                    <td class='border px-4 py-2'>
                                        <a href='edit-route.php?id={$r['id']}' class='text-blue-600 hover:underline'>Edit</a> | 
                                        <a href='routes.php?delete={$r['id']}' class='text-red-600 hover:underline' onclick='return confirm(\"Delete this route?\")'>Delete</a>
                                    </td>
                                </tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-gray-500 py-4'>No routes found</td></tr>";
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
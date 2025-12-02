<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../backend/config.php';

// Admin access only
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// DELETE BUS
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM buses WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: buses.php");
        exit();
    } else {
        die("Error deleting bus: " . $stmt->error);
    }
}

// ADD BUS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bus'])) {
    $bus_name = trim($_POST['bus_name']);
    $bus_number = trim($_POST['bus_number']);
    $route_id = intval($_POST['route_id']);
    $total_seats = intval($_POST['total_seats']);

    if ($bus_name && $bus_number && $route_id > 0 && $total_seats > 0) {
        $stmt = $conn->prepare("INSERT INTO buses (bus_name, bus_number, route_id, total_seats) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $bus_name, $bus_number, $route_id, $total_seats);
        if ($stmt->execute()) {
            header("Location: buses.php");
            exit();
        } else {
            die("Error adding bus: " . $stmt->error);
        }
    } else {
        $error_message = "Please fill all fields correctly.";
    }
}

// FETCH BUSES
$sql = "SELECT b.*, r.from_location, r.to_location FROM buses b 
        JOIN routes r ON b.route_id = r.id ORDER BY b.id DESC";
$buses = $conn->query($sql);
if (!$buses) die("Error fetching buses: " . $conn->error);

// FETCH ROUTES FOR DROPDOWN
$routes = $conn->query("SELECT id, from_location, to_location FROM routes");
if (!$routes) die("Error fetching routes: " . $conn->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Buses - Admin Panel</title>
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
            <a href="routes.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Routes</a>
            <a href="buses.php" class="block px-4 py-2 rounded bg-blue-800">Manage Buses</a>
            <a href="bookings.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Bookings</a>
        </nav>
        <div class="p-4 border-t border-blue-500">
            <a href="../backend/logout.php" class="block text-center bg-red-500 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-700">Manage Buses</h2>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">+ Add New Bus</button>
        </div>

        <?php if(isset($error_message)): ?>
            <div class="mb-4 p-4 bg-red-200 text-red-700 rounded"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-left border">
                <thead class="bg-blue-700 text-white">
                    <tr>
                        <th class="py-3 px-4 border">#</th>
                        <th class="py-3 px-4 border">Bus Name</th>
                        <th class="py-3 px-4 border">Bus Number</th>
                        <th class="py-3 px-4 border">Route</th>
                        <th class="py-3 px-4 border">Total Seats</th>
                        <th class="py-3 px-4 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($buses->num_rows > 0): $i=1; ?>
                        <?php while ($bus = $buses->fetch_assoc()): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-4 border"><?= $i++ ?></td>
                                <td class="py-2 px-4 border"><?= htmlspecialchars($bus['bus_name']) ?></td>
                                <td class="py-2 px-4 border"><?= htmlspecialchars($bus['bus_number']) ?></td>
                                <td class="py-2 px-4 border"><?= htmlspecialchars($bus['from_location'] . " → " . $bus['to_location']) ?></td>
                                <td class="py-2 px-4 border"><?= htmlspecialchars($bus['total_seats']) ?></td>
                                <td class="py-2 px-4 border text-center">
                                    <a href="edit-bus.php?id=<?= $bus['id'] ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                                    <a href="buses.php?delete=<?= $bus['id'] ?>" onclick="return confirm('Are you sure you want to delete this bus?')" class="text-red-600 hover:underline">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-gray-600">No buses available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Bus Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <h3 class="text-2xl font-bold text-gray-700 mb-4">Add New Bus</h3>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-2">Bus Name</label>
                <input type="text" name="bus_name" required class="w-full p-2 border rounded focus:outline-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Bus Number</label>
                <input type="text" name="bus_number" required class="w-full p-2 border rounded focus:outline-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Route</label>
                <select name="route_id" required class="w-full p-2 border rounded focus:outline-blue-500">
                    <option value="">-- Select Route --</option>
                    <?php while ($r = $routes->fetch_assoc()): ?>
                        <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['from_location'] . " → " . $r['to_location']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Total Seats</label>
                <input type="number" name="total_seats" min="10" required class="w-full p-2 border rounded focus:outline-blue-500">
            </div>
            <div class="flex justify-between mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="bg-gray-300 px-6 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" name="add_bus" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Add Bus</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

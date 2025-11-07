<?php
session_start();
require_once '../backend/config.php';

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Check if route ID is given
if (!isset($_GET['id'])) {
    header("Location: routes.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM routes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$route = $stmt->get_result()->fetch_assoc();

if (!$route) {
    header("Location: routes.php");
    exit();
}

// Update route
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start = trim($_POST['start_point']);
    $end = trim($_POST['end_point']);
    $fare = floatval($_POST['fare']);
    if ($start && $end && $fare > 0) {
        $update = $conn->prepare("UPDATE routes SET start_point=?, end_point=?, fare=? WHERE id=?");
        $update->bind_param("ssdi", $start, $end, $fare, $id);
        $update->execute();
    }
    header("Location: routes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Route - Admin Panel</title>
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
            <a href="routes.php" class="block px-4 py-2 rounded bg-blue-800">Manage Routes</a>
            <a href="buses.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Buses</a>
            <a href="bookings.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Bookings</a>
        </nav>
        <div class="p-4 border-t border-blue-500">
            <a href="../backend/logout.php" class="block text-center bg-red-500 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h2 class="text-3xl font-bold text-gray-700 mb-8">Edit Route</h2>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Start Point</label>
                    <input type="text" name="start_point" value="<?= htmlspecialchars($route['start_point']) ?>" required class="w-full p-2 border rounded focus:outline-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">End Point</label>
                    <input type="text" name="end_point" value="<?= htmlspecialchars($route['end_point']) ?>" required class="w-full p-2 border rounded focus:outline-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Fare (Rs.)</label>
                    <input type="number" step="0.01" name="fare" value="<?= htmlspecialchars($route['fare']) ?>" required class="w-full p-2 border rounded focus:outline-blue-500">
                </div>
                <div class="flex justify-between mt-6">
                    <a href="routes.php" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 transition">Cancel</a>
                    <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800 transition">Update Route</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>

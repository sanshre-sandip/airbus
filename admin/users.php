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

// DELETE USER
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Prevent deleting self
    if ($delete_id == $_SESSION['user_id']) {
        $error_message = "You cannot delete your own account.";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            header("Location: users.php");
            exit();
        } else {
            $error_message = "Error deleting user: " . $stmt->error;
        }
    }
}

// FETCH USERS
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$users = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
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
                <a href="buses.php" class="block px-4 py-2 rounded hover:bg-blue-800">Manage Buses</a>
                <a href="bookings.php" class="block px-4 py-2 rounded hover:bg-blue-800">Bookings</a>
                <a href="users.php" class="block px-4 py-2 rounded bg-blue-800">Manage Users</a>
            </nav>
            <div class="p-4 border-t border-blue-500">
                <a href="../logout.php"
                    class="block text-center bg-red-500 py-2 rounded hover:bg-red-600 transition">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-700">Manage Users</h2>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="mb-4 p-4 bg-red-200 text-red-700 rounded"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full text-left border">
                    <thead class="bg-blue-700 text-white">
                        <tr>
                            <th class="py-3 px-4 border">#</th>
                            <th class="py-3 px-4 border">Name</th>
                            <th class="py-3 px-4 border">Email</th>
                            <th class="py-3 px-4 border">Role</th>
                            <th class="py-3 px-4 border">Joined Date</th>
                            <th class="py-3 px-4 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && $users->num_rows > 0):
                            $i = 1; ?>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4 border"><?= $i++ ?></td>
                                    <td class="py-2 px-4 border"><?= htmlspecialchars($user['name']) ?></td>
                                    <td class="py-2 px-4 border"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="py-2 px-4 border">
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold <?= $user['is_admin'] ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' ?>">
                                            <?= $user['is_admin'] ? 'Admin' : 'User' ?>
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                    <td class="py-2 px-4 border text-center">
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="users.php?delete=<?= $user['id'] ?>"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                class="text-red-600 hover:underline">Delete</a>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Current User</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-600">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>
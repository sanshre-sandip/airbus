<?php
include 'backend/config.php';
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Hash password and insert
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bus Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Create an Account</h2>

        <?php if ($error): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Register
            </button>

            <p class="text-sm text-center text-gray-600 mt-4">
                Already have an account?
                <a href="login.php" class="text-blue-600 hover:underline">Login</a>
            </p>
        </form>
    </div>
</body>
</html>

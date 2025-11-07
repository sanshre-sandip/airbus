<?php
session_start();
include 'backend/config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Step 1: Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Step 2: Verify user existence
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Step 3: Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];

            // Step 4: Redirect based on role
            if ($user['is_admin'] == 1) {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bus Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login to Bus Booking</h2>

        <?php if (!empty($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    required 
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Enter your email"
                >
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Enter your password"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200"
            >
                Login
            </button>

            <p class="text-sm text-center text-gray-600 mt-4">
                Don't have an account?
                <a href="register.php" class="text-blue-600 hover:underline">Register</a>
            </p>
        </form>
    </div>
</body>
</html>

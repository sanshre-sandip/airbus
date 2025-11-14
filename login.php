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
    <title>Login - BusGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f0f1e; color: #fff; }
        .gradient-text { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .glass-card { background: rgba(255,255,255,0.03); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.06); }
        .gradient-bg { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); }
        input, select, textarea { background: rgba(255,255,255,0.03); color: #fff; border: 1px solid rgba(255,255,255,0.06); }
        input::placeholder { color: rgba(255,255,255,0.5); }
        input:focus, select:focus, textarea:focus { outline: none; box-shadow: 0 0 0 4px rgba(102,126,234,0.08); border-color: #667eea; }
    </style>
</head>
<body>

    <!-- Navbar (reuse index style) -->
    <nav class="fixed top-0 w-full z-50 nav-blur border-b border-white/10">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold gradient-text">🚌 BusGo</h1>
            <ul class="hidden md:flex space-x-8">
                <li><a href="index.php" class="hover:text-purple-400 transition">Home</a></li>
                <li><a href="#search" class="hover:text-purple-400 transition">Book Now</a></li>
                <li><a href="#features" class="hover:text-purple-400 transition">Features</a></li>
            </ul>
            <div class="flex space-x-4">
                <a href="login.php" class="px-4 py-2 hover:text-purple-400 transition">Login</a>
                <a href="register.php" class="px-6 py-2 gradient-bg rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition">Register</a>
            </div>
        </div>
    </nav>

    <!-- Login Card -->
    <main class="min-h-screen flex items-center justify-center pt-24 px-6">
        <div class="max-w-md w-full glass-card p-8 rounded-2xl">
            <h2 class="text-3xl font-bold mb-4 text-center">Welcome back</h2>
            <p class="text-center text-gray-300 mb-6">Log in to manage your bookings and access faster checkout.</p>

            <?php if (!empty($error)): ?>
                <div class="mb-4 text-sm text-red-400 bg-red-900/10 p-3 rounded"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Email</label>
                    <input type="email" name="email" required placeholder="you@example.com" class="w-full p-3 rounded-xl">
                </div>

                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Password</label>
                    <input type="password" name="password" required placeholder="Your password" class="w-full p-3 rounded-xl">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl gradient-bg font-semibold text-lg">Login</button>

                <p class="text-center text-sm text-gray-400 mt-3">Don't have an account ? <a href="register.php" class="text-purple-300 hover:underline">Create one</a></p>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>

<?php
/**
 * create-admin.php
 * ----------------
 * Usage (web):  http://localhost/yourproject/create-admin.php
 *               or add ?email=you@domain.com&password=yourpass
 * Usage (cli):  php create-admin.php
 *
 * IMPORTANT: Run locally only. Delete this file after use.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// load DB config (adjust path if needed)
require_once __DIR__ . '/backend/config.php';

// Default credentials (you can override with ?email=...&password=...)
$admin_email = isset($_GET['email']) ? trim($_GET['email']) : (php_sapi_name() === 'cli' && isset($argv[1]) ? $argv[1] : 'admin@busticket.com');
$admin_password = isset($_GET['password']) ? trim($_GET['password']) : (php_sapi_name() === 'cli' && isset($argv[2]) ? $argv[2] : 'admin123');
$admin_name = 'Admin';

if (empty($admin_email) || empty($admin_password)) {
    echo "Error: email or password empty. Provide via GET params or CLI args.\n";
    echo "Web example: /create-admin.php?email=admin@busticket.com&password=admin123\n";
    exit;
}

// Security reminder
echo "Creating/updating admin user for: {$admin_email}\n";

// Create hashed password
$hash = password_hash($admin_password, PASSWORD_BCRYPT);
if ($hash === false) {
    echo "Failed to hash password.\n";
    exit;
}

// Check if user exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
if (!$check) {
    echo "DB prepare error (check): " . $conn->error . "\n";
    exit;
}
$check->bind_param("s", $admin_email);
$check->execute();
$res = $check->get_result();

if ($res && $res->num_rows > 0) {
    // Update existing user: set hashed password and is_admin=1
    $row = $res->fetch_assoc();
    $user_id = (int)$row['id'];

    $update = $conn->prepare("UPDATE users SET name = ?, password = ?, is_admin = 1 WHERE id = ?");
    if (!$update) {
        echo "DB prepare error (update): " . $conn->error . "\n";
        exit;
    }
    $update->bind_param("ssi", $admin_name, $hash, $user_id);
    if ($update->execute()) {
        echo "Admin user updated (id={$user_id}).\n";
        echo "Email: {$admin_email}\n";
    } else {
        echo "Failed to update admin: " . $update->error . "\n";
    }
    $update->close();
} else {
    // Insert new admin user
    $insert = $conn->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, 1)");
    if (!$insert) {
        echo "DB prepare error (insert): " . $conn->error . "\n";
        exit;
    }
    $insert->bind_param("sss", $admin_name, $admin_email, $hash);
    if ($insert->execute()) {
        $newId = $insert->insert_id;
        echo "Admin user created successfully (id={$newId}).\n";
        echo "Email: {$admin_email}\n";
    } else {
        echo "Failed to insert admin: " . $insert->error . "\n";
    }
    $insert->close();
}

$check->close();
$conn->close();

echo "\nIMPORTANT: Delete create-admin.php from the server after use for security.\n";
?>
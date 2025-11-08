<?php
include 'backend/config.php'; // adjust if your config file is elsewhere

$status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);

  if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    if ($stmt->execute()) {
      $status = '<p class="text-green-600 text-center mt-4">✅ Message sent successfully!</p>';
    } else {
      $status = '<p class="text-red-600 text-center mt-4">❌ Failed to send message. Try again later.</p>';
    }
    $stmt->close();
  } else {
    $status = '<p class="text-red-600 text-center mt-4">⚠️ Please fill in all fields.</p>';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Bus Booking System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  
  <!-- Navbar -->
  <nav class="bg-blue-700 text-white py-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center px-6">
      <h1 class="text-2xl font-bold">🚌 Yatra Nepal</h1>
      <div>
        <a href="index.php" class="mx-3 hover:underline">Home</a>
        <a href="buses.php" class="mx-3 hover:underline">Buses</a>
        <a href="contact.php" class="mx-3 underline font-semibold">Contact</a>
      </div>
    </div>
  </nav>

  <!-- Contact Section -->
  <section class="flex-grow container mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Contact Us</h2>
    <p class="text-center text-gray-600 mb-10">We’d love to hear from you! Please fill out the form below and we’ll get back to you soon.</p>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
      <form method="POST" action="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 mb-2">Full Name</label>
            <input type="text" name="name" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring focus:ring-blue-400" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring focus:ring-blue-400" required>
          </div>
        </div>

        <div class="mt-6">
          <label class="block text-gray-700 mb-2">Subject</label>
          <input type="text" name="subject" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring focus:ring-blue-400" required>
        </div>

        <div class="mt-6">
          <label class="block text-gray-700 mb-2">Message</label>
          <textarea name="message" rows="5" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring focus:ring-blue-400" required></textarea>
        </div>

        <button type="submit" class="mt-6 bg-blue-700 text-white px-6 py-3 rounded-lg w-full hover:bg-blue-800 transition">Send Message</button>
      </form>

      <?= $status ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-blue-800 text-white text-center py-4 mt-8">
    <p>© 2025 Yatra Nepal | All Rights Reserved</p>
  </footer>
</body>
</html>

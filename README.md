# 🚌 Bus Booking System

A dynamic Bus Booking System built with PHP, MySQL, AJAX, JavaScript, HTML, and CSS. This web platform lets users search routes, select seats on an interactive seat map, and complete bookings with real-time seat availability. Administrators can manage buses, routes, and bookings via a simple admin panel.

---

## 🚀 Features

### User
- User registration & login with password hashing
- Search buses by route and date
- Visual seat map and real-time seat availability (AJAX)
- Select multiple seats and view a live booking summary
- Booking confirmation page with saved booking records

### Admin
- Admin-only dashboard (add/edit/delete buses and routes)
- View and manage all bookings (confirm/cancel/delete)
- Simple analytics (counts for users, buses, bookings)

---

## 💻 Technologies
- Frontend: HTML5, Tailwind CSS, (Bootstrap-ready), JavaScript, AJAX
- Backend: PHP (MySQLi, procedural)
- Database: MySQL
- Server: XAMPP / Apache (local development)

---

## 📂 Project Structure

```
/var/www/html/advanced/
├── admin/
│   ├── dashboard.php        # Admin overview
│   ├── buses.php            # Manage buses (CRUD)
│   ├── routes.php           # Manage routes (CRUD)
│   └── bookings.php         # View/manage bookings
├── assets/
│   ├── css/
│   │   └── styles.css       # Custom styles
│   └── js/
│       └── scripts.js       # Client-side scripts
├── backend/
│   ├── config.php           # DB connection
│   ├── logout.php           # Logout handler
│   ├── process-booking.php  # Booking processor (transactions)
│   └── get-available-seats.php # AJAX seat availability
├── index.php                # Homepage + search
├── login.php                # User login
├── register.php             # User registration
├── search-results.php       # Results + seat availability (AJAX)
├── book.php                 # Seat map and booking UI
├── booking-success.php      # Booking confirmation
├── database.sql             # Database schema + sample data
└── README.md
```

---

## 🗄️ Database Schema (summary)

Tables and key columns:

- `users` — `id`, `name`, `email`, `password`, `is_admin`, `created_at`
- `routes` — `id`, `from_location`, `to_location`, `departure_time`, `arrival_time`, `fare`
- `buses` — `id`, `bus_name`, `bus_number`, `total_seats`, `route_id`
- `bookings` — `id`, `user_id`, `bus_id`, `seat_number`, `booking_date`, `status`, `created_at`

Note: The provided `database.sql` contains the full CREATE TABLE statements and some sample data.

---

## 🔧 Installation (Local - XAMPP)

1. Place the project folder in your web server directory (e.g., XAMPP `htdocs`):

```bash
# copy project to XAMPP htdocs
# cp -r advanced /opt/lampp/htdocs/advanced
```

2. Create the database and import schema:

- Open phpMyAdmin (http://localhost/phpmyadmin) and create a database named `bus_booking`, then import `database.sql`. Or use the MySQL CLI:

```bash
mysql -u root -p
CREATE DATABASE bus_booking;
EXIT;
mysql -u root -p bus_booking < /path/to/advanced/database.sql
```

3. Configure database connection:

Edit `backend/config.php` and set your credentials. Example:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bus_booking');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

4. Open the app in your browser:

```
http://localhost/advanced/
```

---

## ⚡ Admin Access (example)

- Email: `admin@busticket.com` (or `admin@example.com` depending on your imported SQL)
- Password: `admin123` (the SQL file includes a hashed password example)

To create or reset an admin account, run in PHP or use phpMyAdmin to insert a user with a hashed password:

```php
<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
?>
```

Then insert that hash into `users.password` with `is_admin = 1`.

---

## 🧭 How it works (high level)

- Users search for buses by selecting `From`, `To`, and `Date` on `index.php`.
- `search-results.php` queries matching buses and uses AJAX (`backend/get-available-seats.php`) to show live availability.
- On `book.php`, users see a seat map; booked seats are disabled. Selecting seats updates the booking summary client-side.
- `backend/process-booking.php` runs a DB transaction: re-checks seat availability, inserts bookings, and commits or rolls back on error.
- Admin pages live under `/admin` and are protected by the `is_admin` session flag.

---

## ✅ Security & Data Integrity

- Passwords hashed with `password_hash()`
- Prepared statements (MySQLi) to prevent SQL injection
- Server-side validation and transaction handling for bookings
- Session checks for protected areas (admin and user-only pages)

---

## ✨ Contribution

Contributions are welcome. Suggested workflow:

1. Fork the repository
2. Create a branch for your feature/fix
3. Open a pull request with a clear description

---

## 📝 Notes & Next Steps

- You may want to add email notifications, payment gateway integration, and user booking history pages.
- Consider moving to an MVC structure or using a framework (Laravel) for larger projects.

---

## 📄 License

This project is licensed under the MIT License. See the `LICENSE` file for details.

---

If you want, I can also add screenshots, a setup script, or an .env-based config loader. Tell me which you'd like next.



messages from users;
Sajilo Booking
yatra nepal


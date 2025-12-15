# BusGo – Bus Booking System

A dynamic Bus Booking System built with PHP, MySQL, AJAX, JavaScript, HTML, and CSS. BusGo allows users to search for available bus routes, view real-time seat availability, select seats using an interactive seat map, and complete bookings securely. Administrators can manage buses, routes, and all bookings through a dedicated admin dashboard.

---

## Features

### User Features
- User registration and login with secure password hashing
- Search for buses by origin, destination, and travel date
- Interactive seat map with real-time seat availability (updated via AJAX)
- Ability to select multiple seats and view a live booking summary
- Booking confirmation with saved records in the database

### Admin Features
- Secure admin-only dashboard
- Full CRUD operations for buses and routes
- View, confirm, cancel, or delete any booking
- Overview statistics including total users, buses, and bookings

---

## Technologies Used
- **Frontend**: HTML5, CSS (Tailwind CSS supported, Bootstrap-ready), JavaScript, AJAX
- **Backend**: PHP (procedural style with MySQLi)
- **Database**: MySQL
- **Local Development Server**: XAMPP / Apache

---

## Project Structure

```
/var/www/html/advanced/
├── admin/
│   ├── dashboard.php        # Admin overview page
│   ├── buses.php            # Manage buses (Create, Read, Update, Delete)
│   ├── routes.php           # Manage routes (CRUD)
│   └── bookings.php         # View and manage all bookings
├── assets/
│   ├── css/
│   │   └── styles.css       # Custom CSS styles
│   └── js/
│       └── scripts.js       # Client-side JavaScript and AJAX handlers
├── backend/
│   ├── config.php           # Database connection settings
│   ├── logout.php           # Session destroy and redirect
│   ├── process-booking.php  # Handles booking submission with database transaction
│   └── get-available-seats.php # Returns available seats via AJAX
├── index.php                # Homepage with route search form
├── login.php                # User login page
├── register.php             # User registration page
├── search-results.php       # Displays matching buses and triggers seat availability
├── book.php                 # Seat selection interface
├── booking-success.php      # Booking confirmation page
├── database.sql             # Complete database schema and sample data
└── README.md
```

---

## Database Schema (Summary)

Key tables and columns:

- **users**: `id`, `name`, `email`, `password`, `is_admin`, `created_at`
- **routes**: `id`, `from_location`, `to_location`, `departure_time`, `arrival_time`, `fare`
- **buses**: `id`, `bus_name`, `bus_number`, `total_seats`, `route_id`
- **bookings**: `id`, `user_id`, `bus_id`, `seat_number`, `booking_date`, `status`, `created_at`

The `database.sql` file includes full `CREATE TABLE` statements and sample data for testing.

---

## Installation (Local Development with XAMPP)

1. **Place the project folder** in your web server directory:
   ```bash
   # Example for XAMPP on Linux
   cp -r advanced /opt/lampp/htdocs/advanced
   ```

2. **Create and populate the database**:
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Create a new database named `bus_booking`
   - Import the `database.sql` file into this database  
   **OR** use the command line:
     ```bash
     mysql -u root -p
     CREATE DATABASE bus_booking;
     EXIT;
     mysql -u root -p bus_booking < /path/to/advanced/database.sql
     ```

3. **Configure database connection** in `backend/config.php`:
   ```php
   <?php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');        // Default XAMPP password is empty
   define('DB_NAME', 'bus_booking');

   $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
   ```

4. **Launch the application** in your browser:
   ```
   http://localhost/advanced/
   ```

---

## Admin Access

After importing the provided `database.sql`, the following admin account is available:

- **Email**: `admin@busticket.com`
- **Password**: `admin123`

> Note: The password is stored as a `password_hash()` value in the database for security.

To create a new admin account manually:
1. Generate a password hash using PHP:
   ```php
   <?php echo password_hash('your_password', PASSWORD_DEFAULT); ?>
   ```
2. Insert a new user into the `users` table with `is_admin = 1` and the generated hash.

---

## How It Works

1. Users enter origin, destination, and travel date on the homepage (`index.php`).
2. Matching buses are displayed on `search-results.php`.
3. Real-time seat availability is fetched via AJAX (`backend/get-available-seats.php`) when a user clicks "Book."
4. On `book.php`, users interact with a visual seat map. Booked seats are disabled; selected seats update a live summary.
5. Upon submission, `backend/process-booking.php`:
   - Starts a database transaction
   - Re-validates seat availability
   - Inserts booking records
   - Commits on success or rolls back on failure
6. Admin pages (under `/admin`) require an active session with `is_admin = 1`.

---

## Security and Data Integrity

- Passwords are hashed using PHP’s `password_hash()` and verified with `password_verify()`
- All database queries use prepared statements to prevent SQL injection
- Booking logic uses MySQL transactions to ensure data consistency
- Session-based authentication protects user and admin routes
- Input validation is performed on both client and server sides

---

## Future Enhancements (Suggestions)

- Add user booking history page
- Integrate email/SMS notifications for booking confirmations
- Implement payment gateway integration (e.g., Stripe, PayPal)
- Add responsive design improvements for mobile users
- Migrate to an MVC architecture or a modern framework (e.g., Laravel) for scalability

---

## Contributing

Contributions are welcome. To contribute:

1. Fork the repository
2. Create a new feature or bugfix branch
3. Commit your changes with clear messages
4. Push to your fork and open a pull request

Please include a description of the problem and your solution.

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for more details.

---

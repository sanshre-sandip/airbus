-- Create Database
CREATE DATABASE IF NOT EXISTS bus_booking
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE bus_booking;

-- =========================
-- Users Table
-- =========================
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB;

-- =========================
-- Routes Table
-- =========================
CREATE TABLE routes (
  id INT NOT NULL AUTO_INCREMENT,
  from_location VARCHAR(100) NOT NULL,
  to_location VARCHAR(100) NOT NULL,
  departure_time TIME NOT NULL,
  arrival_time TIME NOT NULL,
  fare DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- =========================
-- Buses Table
-- =========================
CREATE TABLE buses (
  id INT NOT NULL AUTO_INCREMENT,
  bus_name VARCHAR(100) NOT NULL,
  bus_number VARCHAR(50) NOT NULL,
  total_seats INT NOT NULL,
  route_id INT NOT NULL,
  fare DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY bus_number (bus_number),
  KEY route_id (route_id),
  CONSTRAINT buses_ibfk_1
    FOREIGN KEY (route_id)
    REFERENCES routes(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- Bookings Table
-- =========================
CREATE TABLE bookings (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  bus_id INT NOT NULL,
  booking_date DATE NOT NULL,
  seat_number VARCHAR(10) NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending','confirmed','cancelled') DEFAULT 'confirmed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY bus_id (bus_id),
  CONSTRAINT bookings_ibfk_1
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,
  CONSTRAINT bookings_ibfk_2
    FOREIGN KEY (bus_id)
    REFERENCES buses(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;
INSERT INTO users (name, email, password, is_admin)
VALUES (
  'Admin',
  'admin@busticket.com',
  '$2y$10$ePeJA7kqj6FhVClwzZeZ7.7XGzT01Y5c4YeKwBj5YfgFmg7gao9RC',
  1
);

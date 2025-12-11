-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: Dec 11, 2025 at 08:14 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40403175_bus`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `bus_id`, `booking_date`, `seat_number`, `total_amount`, `status`, `created_at`) VALUES
(1, 5, 14, '2025-11-12', 'S04', '0.00', 'confirmed', '2025-11-12 12:12:01'),
(2, 5, 14, '2025-11-12', 'S08', '0.00', 'confirmed', '2025-11-12 12:12:01'),
(3, 5, 14, '2025-11-12', 'S12', '0.00', 'cancelled', '2025-11-12 12:12:01'),
(5, 5, 13, '2025-11-12', 'S38', '0.00', 'cancelled', '2025-11-12 22:33:48'),
(6, 5, 14, '2025-11-12', 'S29', '0.00', 'confirmed', '2025-11-12 23:28:23'),
(11, 5, 13, '2025-11-14', 'S01', '0.00', 'cancelled', '2025-11-14 12:58:17'),
(12, 5, 13, '2025-11-14', 'S02', '0.00', 'confirmed', '2025-11-14 12:58:17'),
(13, 5, 13, '2025-11-14', 'S03', '0.00', 'cancelled', '2025-11-14 12:58:17'),
(14, 5, 13, '2025-11-14', 'S04', '0.00', 'confirmed', '2025-11-14 12:58:17'),
(15, 5, 15, '2025-11-16', 'S01', '0.00', 'cancelled', '2025-11-15 05:49:34'),
(16, 5, 15, '2025-11-16', 'S02', '0.00', 'confirmed', '2025-11-15 05:49:34'),
(17, 5, 15, '2025-11-16', 'S03', '0.00', 'confirmed', '2025-11-15 05:49:34'),
(18, 5, 15, '2025-11-16', 'S04', '0.00', 'confirmed', '2025-11-15 05:49:34'),
(19, 5, 15, '2025-11-16', 'S30', '0.00', 'confirmed', '2025-11-15 05:49:34'),
(20, 5, 15, '2025-11-15', 'S03', '0.00', 'confirmed', '2025-11-15 08:11:49'),
(21, 5, 15, '2025-11-15', 'S04', '0.00', 'confirmed', '2025-11-15 08:11:49'),
(22, 5, 15, '2025-11-15', 'S01', '0.00', 'confirmed', '2025-11-15 09:01:47'),
(23, 5, 15, '2025-11-15', 'S02', '0.00', 'confirmed', '2025-11-15 09:01:47'),
(24, 5, 15, '2025-11-15', 'S05', '0.00', 'confirmed', '2025-11-15 09:01:47'),
(25, 5, 15, '2025-11-15', 'S06', '0.00', 'confirmed', '2025-11-15 09:01:47'),
(26, 5, 15, '2025-11-15', 'S07', '0.00', 'confirmed', '2025-11-15 09:01:47'),
(27, 5, 15, '2025-11-15', 'S08', '0.00', 'confirmed', '2025-11-15 09:01:47'),
(28, 5, 16, '2025-11-15', 'S02', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(29, 5, 16, '2025-11-15', 'S06', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(30, 5, 16, '2025-11-15', 'S09', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(31, 5, 16, '2025-11-15', 'S05', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(32, 5, 16, '2025-11-15', 'S01', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(33, 5, 16, '2025-11-15', 'S10', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(34, 5, 16, '2025-11-15', 'S11', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(35, 5, 16, '2025-11-15', 'S07', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(36, 5, 16, '2025-11-15', 'S08', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(37, 5, 16, '2025-11-15', 'S04', '0.00', 'confirmed', '2025-11-15 09:05:22'),
(38, 5, 16, '2025-11-15', 'S03', '0.00', 'cancelled', '2025-11-15 09:05:22'),
(39, 11, 15, '2025-11-18', 'S01', '0.00', 'confirmed', '2025-11-16 13:21:36'),
(40, 11, 15, '2025-11-18', 'S02', '0.00', 'confirmed', '2025-11-16 13:21:36'),
(41, 11, 15, '2025-11-18', 'S03', '0.00', 'confirmed', '2025-11-16 13:21:36'),
(42, 11, 15, '2025-11-18', 'S04', '0.00', 'confirmed', '2025-11-16 13:21:36'),
(43, 11, 15, '2025-11-18', 'S05', '0.00', 'confirmed', '2025-11-16 13:22:34'),
(44, 11, 15, '2025-11-18', 'S06', '0.00', 'confirmed', '2025-11-16 13:22:34'),
(45, 11, 15, '2025-11-18', 'S07', '0.00', 'confirmed', '2025-11-16 13:22:34'),
(46, 11, 15, '2025-11-18', 'S08', '0.00', 'cancelled', '2025-11-16 13:22:34'),
(47, 5, 17, '2025-11-16', 'S03', '0.00', 'confirmed', '2025-11-16 13:27:07'),
(48, 5, 17, '2025-11-16', 'S07', '0.00', 'confirmed', '2025-11-16 13:27:07'),
(49, 5, 17, '2025-11-16', 'S03', '0.00', 'confirmed', '2025-11-16 13:27:08'),
(50, 5, 17, '2025-11-16', 'S07', '0.00', 'confirmed', '2025-11-16 13:27:08'),
(51, 12, 17, '2025-11-16', 'S43', '0.00', 'confirmed', '2025-11-17 02:35:09'),
(52, 13, 15, '2025-11-18', 'S17', '0.00', 'confirmed', '2025-11-17 04:19:26'),
(53, 5, 16, '2025-11-22', 'S02', '0.00', 'confirmed', '2025-11-22 13:39:16'),
(54, 5, 16, '2025-11-22', 'S03', '0.00', 'confirmed', '2025-11-22 13:39:16'),
(55, 5, 16, '2025-11-22', 'S02', '0.00', 'confirmed', '2025-11-22 13:39:16'),
(56, 5, 16, '2025-11-22', 'S03', '0.00', 'confirmed', '2025-11-22 13:39:16'),
(57, 5, 15, '2025-12-04', 'S03', '0.00', 'confirmed', '2025-12-04 17:55:43'),
(58, 14, 15, '2025-12-05', 'S11', '0.00', 'confirmed', '2025-12-05 06:32:31'),
(59, 14, 15, '2025-12-05', 'S12', '0.00', 'confirmed', '2025-12-05 06:32:31'),
(60, 5, 15, '2025-12-05', 'S27', '0.00', 'confirmed', '2025-12-05 06:47:05'),
(61, 5, 15, '2025-12-05', 'S28', '0.00', 'confirmed', '2025-12-05 06:47:05'),
(62, 5, 15, '2025-12-05', 'S23', '0.00', 'confirmed', '2025-12-05 06:47:05'),
(63, 5, 15, '2025-12-05', 'S24', '0.00', 'confirmed', '2025-12-05 06:47:05'),
(64, 5, 15, '2025-12-20', 'S26', '0.00', 'cancelled', '2025-12-05 06:54:58'),
(65, 14, 15, '2025-12-20', 'S15', '0.00', 'cancelled', '2025-12-05 07:26:37'),
(66, 14, 15, '2025-12-20', 'S27', '0.00', 'confirmed', '2025-12-05 07:26:37'),
(67, 14, 15, '2025-12-20', 'S23', '0.00', 'confirmed', '2025-12-05 07:32:23'),
(68, 14, 15, '2025-12-20', 'S28', '0.00', 'confirmed', '2025-12-05 07:32:23'),
(69, 14, 15, '2025-12-05', 'S29', '0.00', 'confirmed', '2025-12-05 08:22:52'),
(70, 14, 15, '2025-12-05', 'S30', '0.00', 'confirmed', '2025-12-05 08:22:52'),
(71, 14, 15, '2025-12-05', 'S01', '0.00', 'confirmed', '2025-12-05 08:26:57'),
(72, 14, 15, '2025-12-05', 'S02', '0.00', 'confirmed', '2025-12-05 08:26:57'),
(73, 14, 15, '2025-12-05', 'S05', '0.00', 'confirmed', '2025-12-05 08:43:38'),
(74, 14, 15, '2025-12-05', 'S09', '0.00', 'confirmed', '2025-12-05 08:43:38'),
(75, 14, 15, '2025-12-05', 'S13', '0.00', 'confirmed', '2025-12-05 08:43:38'),
(76, 14, 15, '2025-12-05', 'S16', '0.00', 'confirmed', '2025-12-05 08:43:38'),
(77, 14, 15, '2025-12-05', 'S15', '0.00', 'confirmed', '2025-12-05 08:43:38'),
(78, 14, 15, '2025-12-05', 'S14', '0.00', 'confirmed', '2025-12-05 08:43:38'),
(79, 15, 13, '2025-12-11', 'S35', '0.00', 'confirmed', '2025-12-11 13:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `bus_name` varchar(100) NOT NULL,
  `bus_number` varchar(50) NOT NULL,
  `total_seats` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `fare` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bus_name`, `bus_number`, `total_seats`, `route_id`, `created_at`, `fare`) VALUES
(13, 'qwerty', '123', 40, 8, '2025-11-12 11:13:03', '0.00'),
(14, 'qaws', '3445', 30, 8, '2025-11-12 11:13:46', '0.00'),
(15, 'qwertyqw', '12345', 30, 9, '2025-11-15 05:48:16', '0.00'),
(16, 'qwedc', 'ka123', 11, 9, '2025-11-15 09:04:12', '0.00'),
(17, 'qafhjj', '245678765', 45, 10, '2025-11-16 13:25:45', '0.00'),
(18, 'dhlkgj', '5434', 40, 11, '2025-12-05 06:57:06', '0.00'),
(19, 'yadav sarkar', '77777', 40, 12, '2025-12-05 08:47:46', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `from_location` varchar(100) NOT NULL,
  `to_location` varchar(100) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `fare` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `from_location`, `to_location`, `departure_time`, `arrival_time`, `fare`, `created_at`) VALUES
(8, 'Kathmandu', 'Pokhara', '05:00:00', '16:54:00', '1200.00', '2025-11-12 11:09:53'),
(9, 'kathmandu', 'gorkha', '00:33:00', '04:36:00', '2000.00', '2025-11-15 05:47:47'),
(10, 'ktm', 'gtm', '19:11:00', '07:13:00', '100.00', '2025-11-16 13:25:10'),
(11, 'sfgfg', 'fgxdfjghh', '12:43:00', '16:41:00', '12.00', '2025-12-05 06:56:13'),
(12, 'ktm', 'siraha', '01:31:00', '15:29:00', '2000.00', '2025-12-05 08:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`, `photo`) VALUES
(5, 'sandip', 'sandpipe98@gmail.com', '$2y$10$rHJdUgIDySGnVdwyzIop.eRs3BNgThHYm.AnfXH/gQxc0KI87s8zG', 0, '2025-11-12 11:16:07', 'user_5_1763298129.webp'),
(6, 'Admin', 'admin@busticket.com', '$2y$10$9h8hNXDyWdKp4PBRAEVPCe9zoEieQosPexaEOZcNGtjA6u3JPf47S', 1, '2025-11-12 11:17:57', 'default.png'),
(7, 'sandip shrestha', 'admi@example.com', '$2y$10$MF7Y5uLOEDQ4WKgMRWmpzuq1hkevFglFhqH1QluDDZWO08dne6O5i', 0, '2025-11-12 23:30:20', 'default.png'),
(8, 'abc', 'santosh@gmail.com', '$2y$10$EciR6h97Au4wglTo7Ma7WOPdn2mmBd.2j82rj0HBcjXpu.GgWAs/a', 0, '2025-11-13 07:38:11', 'default.png'),
(9, 'Diwas Acharya', 'acharyad438@gmail.com', '$2y$10$Zpbjnq4MRyeNG81tCV33defwiuhqXVDY0NceQ8WpY2wJKY0TyfxJi', 0, '2025-11-16 13:04:58', 'default.png'),
(10, 'Suhana Subedi', 'suhanasubedi646@gmail.com', '$2y$10$M.a285dug9EIbX228xZtaerHzlHupCtvjFbigRB5Lw0AnK74yK1u.', 0, '2025-11-16 13:08:14', 'default.png'),
(11, 'sandip', 'admin@example.com', '$2y$10$vu5LgfQhEIUeWNnr1yIR4.ohawd288lQ8ZN2V4s1euKTEHe4.Ny6O', 0, '2025-11-16 13:19:45', 'user_11_1763299419.jpg'),
(12, 'Santosh', 'redroman321@gmail.com', '$2y$10$mQF2bS1eSOZ/5WxCgGF3L.zRafFDqF4gYQESiSViS8k3mxOizIaHO', 0, '2025-11-17 02:33:58', 'user_12_1763347132.jpg'),
(13, 'Research Basnet', 'basnetresearch1010@gmail.com', '$2y$10$BDH4kbraWPKyQLUYMXoM4u7Ap0AqyhznhpOa5PsEFs/cSiOhHzeqq', 0, '2025-11-17 04:18:22', 'user_13_1763353232.jpg'),
(14, 'Sandip Shrestha', 'qazwsx@qw.com', '$2y$10$iZUxot3Jj5qSox1CH258bOPUn1K/ZZd/VYPsPpsh5vfNk/FPCbDtO', 0, '2025-11-27 16:10:51', 'user_14_1764924328.jpg'),
(15, 'qwerty', 'qwertyu@qwe.com', '$2y$10$lU/kV2k7fHYD4y/ijonV5OgwKJRG2BhxnYLIRpyJ811dorkBXWy02', 0, '2025-12-11 13:10:17', 'user_15_1765458702.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bus_number` (`bus_number`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `buses_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

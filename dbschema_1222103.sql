-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 03:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `birzeit_flat_rent`
--

-- --------------------------------------------------------

--
-- Table structure for table `flats`
--

CREATE TABLE `flats` (
  `flat_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `reference_number` varchar(6) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `available_to` date DEFAULT NULL,
  `num_bedrooms` int(11) DEFAULT NULL,
  `num_bathrooms` int(11) DEFAULT NULL,
  `size_sqm` int(11) DEFAULT NULL,
  `furnished` tinyint(1) DEFAULT NULL,
  `rent_conditions` text DEFAULT NULL,
  `heating` tinyint(1) DEFAULT NULL,
  `air_conditioning` tinyint(1) DEFAULT NULL,
  `access_control` tinyint(1) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flats`
--

INSERT INTO `flats` (`flat_id`, `owner_id`, `reference_number`, `location`, `address`, `price`, `available_from`, `available_to`, `num_bedrooms`, `num_bathrooms`, `size_sqm`, `furnished`, `rent_conditions`, `heating`, `air_conditioning`, `access_control`, `approved`) VALUES
(4, 14, '901699', 'birzeit', 'birzeit', 500.00, '2025-06-26', '2025-07-31', 3, 2, 130, NULL, 'good', 1, 1, 0, 1),
(5, 14, '307139', 'ramallah', 'ramallah', 300.00, '2025-07-08', '2025-08-21', 2, 2, 89, NULL, 'good', 0, 1, 1, 1),
(6, 14, '476365', 'tulkarm', 'tulkarm', 100.00, '2025-06-25', '2025-08-25', 1, 1, 50, NULL, 'good', 1, 0, 0, 1),
(7, 14, '672431', 'birzeit', 'birzeit', 500.00, '2025-06-12', '2025-08-12', 2, 2, 100, NULL, 'nice', 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `flat_features`
--

CREATE TABLE `flat_features` (
  `feature_id` int(11) NOT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `car_parking` tinyint(1) DEFAULT NULL,
  `backyard` enum('none','individual','shared') DEFAULT 'none',
  `playground` tinyint(1) DEFAULT NULL,
  `storage` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flat_features`
--

INSERT INTO `flat_features` (`feature_id`, `flat_id`, `car_parking`, `backyard`, `playground`, `storage`) VALUES
(4, 4, 1, 'individual', 0, 0),
(5, 5, 1, 'none', 0, 1),
(6, 6, 1, 'none', 0, 0),
(7, 7, 1, 'shared', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `flat_images`
--

CREATE TABLE `flat_images` (
  `image_id` int(11) NOT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flat_images`
--

INSERT INTO `flat_images` (`image_id`, `flat_id`, `image_path`) VALUES
(10, 4, 'images/flats/flat_68479f1b0b1a4.jpeg'),
(11, 4, 'images/flats/flat_68479f1b0b31a.jpeg'),
(12, 4, 'images/flats/flat_68479f1b0b458.jpeg'),
(13, 5, 'images/flats/flat_68479f9badebf.jpeg'),
(14, 5, 'images/flats/flat_68479f9bae05a.jpeg'),
(15, 5, 'images/flats/flat_68479f9bae1af.jpeg'),
(16, 6, 'images/flats/flat_684a1989176a9.jpeg'),
(17, 6, 'images/flats/flat_684a198917a37.jpeg'),
(18, 6, 'images/flats/flat_684a198917b76.jpeg'),
(19, 7, 'images/flats/flat_684a19dac70a1.jpeg'),
(20, 7, 'images/flats/flat_684a19dac724e.jpeg'),
(21, 7, 'images/flats/flat_684a19dac76de.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `marketing`
--

CREATE TABLE `marketing` (
  `marketing_id` int(11) NOT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketing`
--

INSERT INTO `marketing` (`marketing_id`, `flat_id`, `title`, `description`, `url`) VALUES
(4, 4, 'nearby schools', 'tt', ''),
(5, 5, 'nearby mall', 'hh', ''),
(6, 6, 'nearby schools', 'dddd', ''),
(7, 7, 'nearby schools', 'aaa', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `sender_role` enum('system','manager','owner','customer') DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `sent_date` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `recipient_id`, `sender_role`, `title`, `body`, `sent_date`, `is_read`) VALUES
(8, 10, 'system', 'Flat Approval Request', 'Owner Test Owner submitted flat ID 4 for approval.', '2025-06-10 05:57:48', 1),
(9, 10, 'system', 'Flat Approval Request', 'Owner Test Owner submitted flat ID 5 for approval.', '2025-06-10 05:59:49', 1),
(10, 14, 'manager', 'Flat Approved', 'Your flat has been approved with reference number: 307139', '2025-06-10 06:00:28', 0),
(11, 14, 'manager', 'Flat Approved', 'Your flat has been approved with reference number: 901699', '2025-06-10 06:00:31', 0),
(12, 10, 'system', 'Flat Approval Request', 'Owner Test Owner submitted flat ID 6 for approval.', '2025-06-12 03:04:33', 0),
(13, 10, 'system', 'Flat Approval Request', 'Owner Test Owner submitted flat ID 7 for approval.', '2025-06-12 03:05:53', 0),
(14, 14, 'manager', 'Flat Approved', 'Your flat has been approved with reference number: 672431', '2025-06-12 03:06:10', 0),
(15, 14, 'manager', 'Flat Approved', 'Your flat has been approved with reference number: 476365', '2025-06-12 03:06:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `preview_appointments`
--

CREATE TABLE `preview_appointments` (
  `appointment_id` int(11) NOT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` int(11) NOT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `rent_start` date DEFAULT NULL,
  `rent_end` date DEFAULT NULL,
  `credit_card_number` varchar(20) DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT 0,
  `credit_card_expiry` varchar(10) DEFAULT NULL,
  `credit_card_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `slot_id` int(11) NOT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `available_date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `is_booked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`slot_id`, `flat_id`, `available_date`, `time`, `is_booked`) VALUES
(8, 4, '2025-06-13', '14:00:00', 0),
(9, 4, '2025-06-13', '17:00:00', 0),
(10, 4, '2025-06-14', '10:00:00', 0),
(11, 4, '2025-06-14', '14:00:00', 0),
(12, 5, '2025-06-16', '10:00:00', 0),
(13, 5, '2025-06-16', '14:00:00', 0),
(14, 5, '2025-06-16', '17:00:00', 0),
(15, 5, '2025-06-17', '10:00:00', 0),
(16, 5, '2025-06-17', '14:00:00', 0),
(17, 6, '2025-06-13', '10:00:00', 0),
(18, 6, '2025-06-13', '14:00:00', 0),
(19, 6, '2025-06-13', '17:00:00', 0),
(20, 6, '2025-06-14', '10:00:00', 0),
(21, 6, '2025-06-14', '14:00:00', 0),
(22, 7, '2025-06-17', '10:00:00', 0),
(23, 7, '2025-06-17', '14:00:00', 0),
(24, 7, '2025-06-17', '17:00:00', 0),
(25, 7, '2025-06-18', '10:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `customer_code` char(9) DEFAULT NULL,
  `national_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_type` enum('customer','owner','manager') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `telephone_number` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `customer_code`, `national_id`, `name`, `user_type`, `address`, `city`, `postal_code`, `date_of_birth`, `email`, `mobile_number`, `telephone_number`, `bank_name`, `bank_branch`, `account_number`, `username`, `password_hash`, `photo`) VALUES
(10, NULL, '111111111', 'Site Manager', 'manager', NULL, NULL, NULL, NULL, 'manager@birzeitflat.com', NULL, NULL, NULL, NULL, NULL, 'manager@birzeitflat.com', '1admina', NULL),
(13, NULL, '985845247', 'Test Customer', 'customer', 'Tulkarm', NULL, NULL, '1993-02-16', 'customer@birzeitflat.com', '1234567891', '1234567891', NULL, NULL, NULL, 'customer@birzeitflat.com', '2helloa', NULL),
(14, NULL, '385591248', 'Test Owner', 'owner', 'ramallah', NULL, NULL, '1999-06-17', 'owner@birzeitflat.com', '9876543211', '9876543211', 'arab bank', 'ramallah', '998571', 'owner@birzeitflat.com', '3ownerz', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flats`
--
ALTER TABLE `flats`
  ADD PRIMARY KEY (`flat_id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `flat_features`
--
ALTER TABLE `flat_features`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `flat_id` (`flat_id`);

--
-- Indexes for table `flat_images`
--
ALTER TABLE `flat_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `flat_id` (`flat_id`);

--
-- Indexes for table `marketing`
--
ALTER TABLE `marketing`
  ADD PRIMARY KEY (`marketing_id`),
  ADD KEY `flat_id` (`flat_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `preview_appointments`
--
ALTER TABLE `preview_appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `flat_id` (`flat_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `flat_id` (`flat_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`slot_id`),
  ADD KEY `flat_id` (`flat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `national_id` (`national_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `customer_code` (`customer_code`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flats`
--
ALTER TABLE `flats`
  MODIFY `flat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `flat_features`
--
ALTER TABLE `flat_features`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `flat_images`
--
ALTER TABLE `flat_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `marketing`
--
ALTER TABLE `marketing`
  MODIFY `marketing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `preview_appointments`
--
ALTER TABLE `preview_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `flats`
--
ALTER TABLE `flats`
  ADD CONSTRAINT `fk_flats_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `flat_features`
--
ALTER TABLE `flat_features`
  ADD CONSTRAINT `fk_features_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`flat_id`) ON DELETE CASCADE;

--
-- Constraints for table `flat_images`
--
ALTER TABLE `flat_images`
  ADD CONSTRAINT `fk_images_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`flat_id`) ON DELETE CASCADE;

--
-- Constraints for table `marketing`
--
ALTER TABLE `marketing`
  ADD CONSTRAINT `fk_marketing_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`flat_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `preview_appointments`
--
ALTER TABLE `preview_appointments`
  ADD CONSTRAINT `fk_preview_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_preview_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`flat_id`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `fk_rentals_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rentals_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`flat_id`) ON DELETE CASCADE;

--
-- Constraints for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD CONSTRAINT `fk_slots_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`flat_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 04:33 PM
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
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone_number`, `password`) VALUES
(1, 'Sibonelo Maphelana', 'maphelanasibonelo@gmail.com', '0748535721', '$2y$10$4Ty4EhoOi4xbAisXRDvVmef6z3cl/HR4Y8K8mLCQ1alGcM/s.IoJa'),
(2, 'Kevin', 'kdb@gmail.com', '0123456789', '$2y$10$REoR/1yRBTb5B3K8.hQYj.nmcmv3n.V62BKNaYCzWRrynnmeVKdAq'),
(4, 'Ilkay', 'gundo@gmail.com', '27698743215', '$2y$10$qldpK3vD/.YOd9aKjyIat.L5d36/GCBs.HAcds1ckgaHb8NZrADuy'),
(5, 'Sibonelo Maphelana', 'doku@gmail.com', '0748535721', '$2y$10$V7gNEBF1cny.nXcrB6ikn.b3cKtPbZL1El7V/2aW/GQkIK2QeQwfy'),
(6, 'Arya', 'stark@gmail.com', '2334456789', '$2y$10$GCH8oqjDrV0o8gKVAz.4t.acuL2saEfnzUaifc9/JOjDEgFWMRTNC');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `imageName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `imageName`) VALUES
(2, 'Sweets', 'sweets(cat).jpeg'),
(3, 'Breakfast', 'breakfast.jpeg'),
(4, 'Beverages', 'beverages.jpeg'),
(5, 'Snacks', 'snacks(cat).jpeg'),
(6, 'Lunch', 'lunch.jpeg'),
(7, 'Dinner', 'dinner.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `description`, `category_id`, `quantity`, `price`, `image`, `admin_id`) VALUES
(7, 'Maynards', '5', 2, 15, 5.00, 'Bee Confectionery.jpeg', 4),
(8, 'Doritos Flamin&#039; Hot Nacho Cheese Flavoured Corn Chips 145g', 'Experience a fiery hot and cheesy crunch with every bite of these corn chips. Perfect for on-the-go snacking, parties, or relaxing at home, the 145g pack offers ample servings to share or enjoy solo. Plus, each chip is packed with bold flavour, ensuring a memorable and satisfying snack experience.', 5, 50, 25.00, 'doritos.jpeg', 4),
(9, 'Pap and Chicken', 'This meal has pap, chicken and a small salad to enjoy for your lunch.', 6, 50, 45.00, 'pap and chicken.jpeg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `registration_number` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `registration_number`, `role`, `email`, `password`) VALUES
(1, 'Sibonelo', 'Maphelana', '1234567890', '', 'maphelanasibonelo@gmail.com', '$2y$10$SLra92KZE0eZIJL5Y/N9JeP8Qcd70W0yxs.QzHTeU.8DhM5G/mWL.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `food_items`
--
ALTER TABLE `food_items`
  ADD CONSTRAINT `food_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `food_items_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 12:28 PM
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
-- Database: `login_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_group` varchar(32) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_group`, `product_name`, `price`, `quantity`, `total`, `status`, `address`, `payment_method`, `created_at`, `updated_at`) VALUES
(8, 33, 'order_20260312000227_33', 'Pastel Wrist Strap', 8.99, 1, 8.99, 'Rejected', NULL, NULL, '2026-03-11 16:02:27', '2026-03-28 13:45:42'),
(9, 33, 'order_20260315215108_33', 'Pastel Wrist Strap', 8.99, 1, 8.99, 'Rejected', NULL, NULL, '2026-03-15 13:51:08', '2026-03-28 13:45:42'),
(10, 33, 'order_20260315215108_33', 'Pastel Wrist Strap', 8.99, 1, 8.99, 'Rejected', NULL, NULL, '2026-03-15 13:51:17', '2026-03-28 13:45:42'),
(11, 33, 'order_20260315215433_33', 'Pastel Wrist Strap', 8.99, 1, 8.99, 'Rejected', NULL, NULL, '2026-03-15 13:54:33', '2026-03-28 13:45:42'),
(12, 33, 'order_20260315221522_33', 'Unknown Product', 8.99, 1, 8.99, 'Rejected', 'No address provided', 'Unknown', '2026-03-15 14:15:22', '2026-03-28 13:45:42'),
(13, 33, 'order_20260315221624_33', 'Unknown Product', 8.99, 1, 8.99, 'Rejected', 'No address provided', 'Unknown', '2026-03-15 14:16:24', '2026-03-28 13:45:42'),
(14, 33, 'order_20260315221624_33', 'Unknown Product', 8.99, 1, 8.99, 'Rejected', 'No address provided', 'Unknown', '2026-03-15 14:16:48', '2026-03-28 13:45:42'),
(15, 33, 'order_20260315222528_33', 'Unknown Product', 8.99, 1, 8.99, 'Rejected', 'No address provided', 'Unknown', '2026-03-15 14:25:28', '2026-03-28 13:45:42'),
(16, 33, 'order_20260328203107_33', 'Y2K Beaded Phone Strap', 60.00, 1, 60.00, 'Rejected', '22 Aklan St. Luzviminda', 'Cash on Delivery', '2026-03-28 12:31:07', '2026-03-28 13:45:42'),
(17, 33, 'order_20260328203107_33', 'Cute Pastel Phone Strap', 50.00, 1, 50.00, 'Rejected', '22 Aklan St. Luzviminda', 'Cash on Delivery', '2026-03-28 12:31:07', '2026-03-28 13:45:42'),
(18, 33, 'order_20260328204806_33', 'Ocean Beaded Phone Strap', 60.00, 1, 60.00, 'Rejected', '22 Aklan St. Luzviminda', 'Cash on Delivery', '2026-03-28 12:48:06', '2026-03-28 13:45:42'),
(19, 33, 'order_20260328210844_33', 'Flower Bead Strap', 70.00, 1, 70.00, 'Rejected', '22 Aklan St. Luzviminda', 'Cash on Delivery', '2026-03-28 13:08:44', '2026-03-28 13:45:42'),
(20, 33, 'order_20260328211019_33', 'Y2K Beaded Phone Strap', 60.00, 1, 60.00, 'Rejected', 'Kahit Saan', 'Cash on Delivery', '2026-03-28 13:10:19', '2026-03-28 13:45:42'),
(21, 33, 'order_20260328211019_33', 'Cute Pastel Phone Strap', 50.00, 1, 50.00, 'Rejected', 'Kahit Saan', 'Cash on Delivery', '2026-03-28 13:10:19', '2026-03-28 13:45:42'),
(22, 33, 'order_20260328211019_33', 'Flower Bead Strap', 70.00, 1, 70.00, 'Rejected', 'Kahit Saan', 'Cash on Delivery', '2026-03-28 13:10:19', '2026-03-28 13:45:42'),
(23, 33, 'order_20260328211019_33', 'Ocean Beaded Phone Strap', 60.00, 1, 60.00, 'Rejected', 'Kahit Saan', 'Cash on Delivery', '2026-03-28 13:10:19', '2026-03-28 13:45:42'),
(24, 33, 'order_20260328211019_33', 'Star Bead Phone Strap', 55.00, 1, 55.00, 'Rejected', 'Kahit Saan', 'Cash on Delivery', '2026-03-28 13:10:19', '2026-03-28 13:45:42'),
(25, 33, 'order_20260328211019_33', 'Coquette Bow Phone Strap', 50.00, 1, 50.00, 'Rejected', 'Kahit Saan', 'Cash on Delivery', '2026-03-28 13:10:19', '2026-03-28 13:45:42'),
(26, 33, 'order_20260328214213_33', 'Y2K Beaded Phone Strap', 60.00, 1, 60.00, 'Rejected', 'Ewan', 'Cash on Delivery', '2026-03-28 13:42:13', '2026-03-28 14:03:49'),
(27, 33, 'order_20260328214213_33', 'Cute Pastel Phone Strap', 50.00, 1, 50.00, 'Rejected', 'Ewan', 'Cash on Delivery', '2026-03-28 13:42:13', '2026-03-28 14:03:49'),
(28, 33, 'order_20260328214213_33', 'Flower Bead Strap', 70.00, 1, 70.00, 'Rejected', 'Ewan', 'Cash on Delivery', '2026-03-28 13:42:13', '2026-03-28 14:03:49'),
(29, 33, 'order_20260328214213_33', 'Ocean Beaded Phone Strap', 60.00, 1, 60.00, 'Rejected', 'Ewan', 'Cash on Delivery', '2026-03-28 13:42:13', '2026-03-28 14:03:49'),
(30, 33, '69c7df8cac528_33_1774706572', 'Y2K Beaded Phone Strap', 60.00, 1, 60.00, 'Accepted', 'Ormoc City', 'Cash on Delivery', '2026-03-28 14:02:52', '2026-03-28 14:03:37'),
(31, 33, '69c7df8cac528_33_1774706572', 'Cute Pastel Phone Strap', 50.00, 1, 50.00, 'Accepted', 'Ormoc City', 'Cash on Delivery', '2026-03-28 14:02:52', '2026-03-28 14:03:37'),
(32, 33, '69c7df8cac528_33_1774706572', 'Flower Bead Strap', 70.00, 12, 840.00, 'Accepted', 'Ormoc City', 'Cash on Delivery', '2026-03-28 14:02:52', '2026-03-28 14:03:37'),
(33, 33, '69c7df8cac528_33_1774706572', 'Ocean Beaded Phone Strap', 60.00, 1, 60.00, 'Accepted', 'Ormoc City', 'Cash on Delivery', '2026-03-28 14:02:52', '2026-03-28 14:03:37'),
(34, 33, '69c7e062049dd_33_1774706786', 'Y2K Beaded Phone Strap', 60.00, 1, 60.00, 'Accepted', 'Sa bahay ni Cuase', 'GCash', '2026-03-28 14:06:26', '2026-03-28 14:07:15'),
(35, 33, '69c7e062049dd_33_1774706786', 'Cute Pastel Phone Strap', 50.00, 1, 50.00, 'Accepted', 'Sa bahay ni Cuase', 'GCash', '2026-03-28 14:06:26', '2026-03-28 14:07:15'),
(36, 33, '69c7e062049dd_33_1774706786', 'Flower Bead Strap', 70.00, 1, 70.00, 'Accepted', 'Sa bahay ni Cuase', 'GCash', '2026-03-28 14:06:26', '2026-03-28 14:07:15'),
(37, 33, '69c7e062049dd_33_1774706786', 'Ocean Beaded Phone Strap', 60.00, 1, 60.00, 'Accepted', 'Sa bahay ni Cuase', 'GCash', '2026-03-28 14:06:26', '2026-03-28 14:07:15'),
(38, 33, '69c7e165d872c_33_1774707045', 'Y2K Beaded Phone Strap', 60.00, 1, 60.00, 'Accepted', 'Sa bahay ko', 'GCash', '2026-03-28 14:10:45', '2026-03-28 14:11:03'),
(39, 33, '69c7e165d872c_33_1774707045', 'Cute Pastel Phone Strap', 50.00, 1, 50.00, 'Accepted', 'Sa bahay ko', 'GCash', '2026-03-28 14:10:45', '2026-03-28 14:11:03'),
(40, 33, '69c7e165d872c_33_1774707045', 'Flower Bead Strap', 70.00, 1, 70.00, 'Accepted', 'Sa bahay ko', 'GCash', '2026-03-28 14:10:45', '2026-03-28 14:11:03');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `product_name`, `category`, `price`, `quantity`, `description`, `created_at`, `product_image`) VALUES
(1, 12, 'Akainu Strap', 'Phone Strap', 150.00, 5, '', '2026-02-07 08:54:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `reset_expire` datetime DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `reset_token`, `verification_code`, `is_verified`, `reset_expire`, `role`) VALUES
(33, 'Yus', 'juliuscuase85@gmail.com', '$2y$10$pMFXMHZtH74pTH2uFXIm2OALTURa0zFFS6jUzEhUSWnAToZZcTgW2', NULL, '9f68c8cad142ab70d6dde2b4e422a7e0', 1, NULL, 'user'),
(34, 'Admin', 'admin@strapify.com', '$2y$10$YSNzlVck9A.NNpLGarISO..2d0LmRRM3M1z/uxQi/QCgs/hODi5eC', NULL, NULL, 1, NULL, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_group` (`order_group`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

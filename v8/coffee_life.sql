-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 08:42 AM
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
-- Database: `coffee_life`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'test123', '$2y$10$tmgr3gekHnBGxwd9IAoHM.xSbynYQL92kKuMctj.eCh6dfeUsans.', '2025-07-01 15:37:36', '2025-07-01 15:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `category`, `description`, `ingredients`, `benefits`, `price`, `quantity`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Espresso', 'Coffee', 'Rich, concentrated coffee shot with bold flavor and aromatic crema.', 'Premium coffee beans, filtered water', 'High in antioxidants, boosts energy and focus', 5.50, 50, 'uploads/images/espresso.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(2, 'Americano', 'Coffee', 'Smooth espresso with hot water, perfect for everyday coffee lovers.', 'Premium espresso, hot water', 'Lower calorie coffee option, rich in antioxidants', 6.00, 45, 'uploads/images/americano.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(3, 'Latte', 'Coffee', 'Creamy espresso with steamed milk, topped with microfoam.', 'Premium espresso, steamed milk, microfoam', 'Good source of calcium and protein', 7.50, 40, 'uploads/images/latte.jpg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(4, 'Cappuccino', 'Coffee', 'Perfect balance of espresso, steamed milk, and thick foam.', 'Premium espresso, steamed milk, milk foam', 'Balanced caffeine with calcium benefits', 7.00, 35, 'uploads/images/cappuccino.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(5, 'Mocha', 'Coffee', 'Rich espresso with chocolate syrup and steamed milk.', 'Premium espresso, chocolate syrup, steamed milk, whipped cream', 'Combines coffee antioxidants with mood-boosting chocolate', 8.00, 30, 'uploads/images/mocha.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(6, 'Macchiato', 'Coffee', 'Espresso \"marked\" with a dollop of steamed milk foam.', 'Premium espresso, steamed milk foam', 'Strong caffeine kick with minimal calories', 7.50, 25, 'uploads/images/macchiato.jpg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(7, 'Curry Puff', 'Snacks', 'Traditional Malaysian pastry filled with spiced curry potatoes.', 'Puff pastry, potatoes, curry spices, onions', 'Good source of carbohydrates and fiber', 4.50, 60, 'uploads/images/curry-puff.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(8, 'Chicken Roll', 'Snacks', 'Flaky pastry filled with seasoned chicken and vegetables.', 'Puff pastry, chicken breast, vegetables, spices', 'High protein snack with vegetables', 5.90, 45, 'uploads/images/chicken-roll.jpg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(9, 'Sausage Roll', 'Snacks', 'Seasoned pork sausage wrapped in golden puff pastry.', 'Puff pastry, pork sausage, herbs, spices', 'Good source of protein and energy', 7.50, 35, 'uploads/images/sausage-roll.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(10, 'Tuna Puff', 'Snacks', 'Light and flaky pastry filled with seasoned tuna.', 'Puff pastry, tuna, onions, spices', 'High in protein and omega-3 fatty acids', 5.50, 40, 'uploads/images/tuna-puff.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(11, 'Blueberry Muffin', 'Pastries', 'Fresh blueberries in a tender, moist muffin. Perfect breakfast treat.', 'Flour, blueberries, eggs, butter, sugar', 'Rich in antioxidants from blueberries', 6.50, 50, 'uploads/images/blueberry-muffin.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(12, 'Chocolate Chip Muffin', 'Pastries', 'Rich chocolate chip muffin with a tender crumb.', 'Flour, chocolate chips, eggs, butter, sugar', 'Provides energy and mood-boosting compounds', 6.50, 45, 'uploads/images/chocolate-muffin.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(13, 'Banana Walnut Muffin', 'Pastries', 'Moist banana muffin with crunchy walnuts.', 'Flour, bananas, walnuts, eggs, butter, sugar', 'Rich in potassium and healthy fats from walnuts', 6.90, 40, 'uploads/images/banana-muffin.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(14, 'Croissant', 'Pastries', 'Buttery, layered French pastry. Perfect for breakfast.', 'Flour, butter, yeast, milk, eggs', 'Provides energy and satisfaction for breakfast', 8.90, 30, 'uploads/images/croissant.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(15, 'Iced Chocolate', 'Cold Drinks', 'Rich chocolate drink with ice and whipped cream.', 'Chocolate syrup, milk, ice, whipped cream', 'Cooling and energizing chocolate treat', 10.90, 35, 'uploads/images/iced-chocolate.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(16, 'Iced Tea', 'Cold Drinks', 'Refreshing iced tea with lemon. Various flavors available.', 'Tea leaves, water, ice, lemon, natural flavors', 'Hydrating with antioxidants from tea', 8.90, 50, 'uploads/images/iced-tea.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(17, 'Frappe', 'Cold Coffee', 'Blended iced coffee with milk and ice. Thick and creamy.', 'Coffee, milk, ice, sugar, whipped cream', 'Refreshing caffeine boost with cooling effect', 15.90, 25, 'uploads/images/frappe.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36'),
(18, 'Smoothie Bowl', 'Healthy', 'Acai smoothie topped with granola, fruits, and nuts.', 'Acai berries, banana, granola, mixed fruits, nuts', 'High in antioxidants, fiber, and healthy fats', 16.90, 20, 'uploads/images/smoothie-bowl.jpeg', '2025-07-01 15:37:36', '2025-07-01 15:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `contact_number`, `created_at`, `address`) VALUES
(1, 'test123', '$2y$10$J8yLxOiW3WSShyQLUaQ1PukDvVS.gfLsXck0LF2tY0.s9WtWrwzg6', 'tesr@gmail.com', '011222222223', '2025-06-11 07:50:36', '11A, Jalan 11\r\nTaman Berkat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

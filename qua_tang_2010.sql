-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 01:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qua_tang_2010`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', '1'),
(2, 'hoang', '$2y$10$vwsNMku2RIQyiInAX3tPzeHtx64NcNu42sRj23uXa98.QNm/gC2Om'),
(3, 'hoang', 'matkhau123'),
(4, 'hoang11', '$2y$10$e.w/M7z2Q0qA1.C1B.W5j.Y7Z0.vRz.nU1sT7vT9uYQ4Q9X1y');

-- --------------------------------------------------------

--
-- Table structure for table `recipients`
--

CREATE TABLE `recipients` (
  `id` varchar(20) NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipients`
--

INSERT INTO `recipients` (`id`, `recipient_name`, `sender_name`, `photo`, `message`, `created_at`) VALUES
('gift_6ad86637a4cda', 'Hoàng', 'ho', 'uploads/gift_6ad86637a4cda.png', 'Chúc bạn có một ngày 20/10 thật vui vẻ và ngập tràn hạnh phúc! Mong bạn luôn xinh đẹp, tự tin, gặp nhiều may mắn và được yêu thương thật nhiều. Chúc mọi điều dịu dàng nhất sẽ đến với bạn, hôm nay và cả những ngày sau này. 💗', '2026-10-21 07:13:59'),
('gift_6ad8678782fc6', 'Hoàng', 'ho', 'uploads/gift_6ad8678782fc6.png', 'Chúc bạn có một ngày 20/10 thật vui vẻ và ngập tràn hạnh phúc! Mong bạn luôn xinh đẹp, tự tin, gặp nhiều may mắn và được yêu thương thật nhiều. Chúc mọi điều dịu dàng nhất sẽ đến với bạn, hôm nay và cả những ngày sau này. 💗', '2026-10-21 07:19:35'),
('gift_6ad8692a17844', 'Hoàng', 'ho', 'uploads/gift_6ad8692a17844.png', 'Chúc bạn có một ngày 20/10 thật vui vẻ và ngập tràn hạnh phúc! Mong bạn luôn xinh đẹp, tự tin, gặp nhiều may mắn và được yêu thương thật nhiều. Chúc mọi điều dịu dàng nhất sẽ đến với bạn, hôm nay và cả những ngày sau này. 💗', '2026-10-21 07:26:34'),
('gift_6ad86b3e21d5d', 'Hoàng', 'ho', 'uploads/gift_6ad86b3e21d5d.png', 'Chúc bạn có một ngày 20/10 thật vui vẻ và ngập tràn hạnh phúc! Mong bạn luôn xinh đẹp, tự tin, gặp nhiều may mắn và được yêu thương thật nhiều. Chúc mọi điều dịu dàng nhất sẽ đến với bạn, hôm nay và cả những ngày sau này. 💗', '2026-10-21 07:35:26'),
('gift_6ad87c41a8159', 'Hoàng', 'ho', 'uploads/gift_6ad87c41a8159.png', 'Chúc bạn có một ngày 20/10 thật vui vẻ và ngập tràn hạnh phúc! Mong bạn luôn xinh đẹp, tự tin, gặp nhiều may mắn và được yêu thương thật nhiều. Chúc mọi điều dịu dàng nhất sẽ đến với bạn, hôm nay và cả những ngày sau này. 💗', '2026-10-21 08:48:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

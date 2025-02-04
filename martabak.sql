-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jan 2025 pada 18.00
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38036101_food_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '8cb2237d0679ca88db6464eac60da96345513964'),
(3, 'martabak', '8cb2237d0679ca88db6464eac60da96345513964');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(10, 4, 9, 'martbak 4', 20000, 1, 'martabak-74.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(2, 0, 'RIDO RIFKI HAKIM', 'ridorifkihakim@gmail.com', '5657850', 'yes yes\r\n'),
(3, 0, 'q', 'q@gmail.com', '0', 'q'),
(4, 1, 'RIDO RIFKI HAKIM', 'ridorifkihakim@gmail.com', '1223333', 'fbesjcfnskcnk'),
(5, 4, 'Welly', 'willydamara@gmail.com', '0898395377', 'enakkk'),
(6, 0, 'Ahmad Tejo Sutejo', 'luluchoirunnisa03@smk.belajar.id', '0852122495', 'Martabaknya Enak dan responnya cepat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'proses',
  `delivery_status` varchar(50) DEFAULT 'dikemas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `delivery_status`) VALUES
(13, 10, 'RIDO RIFKI HAKIM', '1319357119', 'sarababa@gmail.com', 'credit card', '90, 90,  vjvfj, jawa barat, jawa barat, bekaasi, Indonesia - 17147', 'Martabak Kacang Coklat  (150000 x 1) - ', 150000, '2025-01-07', 'berhasil', 'dikemas'),
(14, 10, 'RIDO RIFKI HAKIM', '1319357119', 'sarababa@gmail.com', 'cash on delivery', 'jl cabang dua, gang sentiong, , Bekasi, Jawa Barat, 17147', 'Martabak Spesial Coklat Keju (25000 x 1) - ', 25000, '2025-01-08', 'berhasil', 'dikemas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`) VALUES
(5, 'Martabak Kacang Coklat ', 'martabak manis', 20000, 'martabak-80.jpg'),
(6, 'Martabak Spesial Coklat Keju', 'martabak manis', 25000, '14.jpg'),
(7, 'Martabak Spesial Topping A', 'martabak manis', 20000, 'martabakTropica.png'),
(8, 'Martabak Spesial Topping B', 'martabak manis', 57000, '3.jpg'),
(9, 'Martbak Spesial Coklat ', 'martabak manis', 20000, 'martabak-74.jpg'),
(10, 'Martabak Spesial Kacang Coklat Keju', 'martabak pandan', 40000, '12.jpg'),
(11, 'Martabak Spesial Topping', 'martabak manis', 45000, '4.jpg'),
(12, 'Martabak Spesial Coklat', 'martabak pandan', 45000, '13.jpg'),
(13, 'Martabak Black Keju Kacang', 'martabak manis', 25000, '10.jpg'),
(14, 'Martabak Spesial Almond', 'martabak manis', 50000, '11.jpg'),
(16, 'Martabak Mini Spesial A', 'martabak klasik', 40000, '5.jpg'),
(17, 'Martabak Spesial Kacang Nutella', 'martabak manis', 45000, '15.jpg'),
(18, 'Martabak Jagung Keju', 'martabak jagung', 55000, '8.jpg'),
(19, 'Martabak Red Velvet', 'martabak manis', 40000, '9.jpg'),
(20, 'Martabak Spesial Topping D', 'martabak manis', 45000, '2.jpg'),
(21, 'Martabak 2 Telur', 'martabak telur', 20000, 'a.jpg'),
(22, 'Martabak 1 Telur', 'martabak telur', 15000, 'b.jpg'),
(23, 'Martabak 3Telur Spesial Bawang', 'martabak telur', 35000, 'd.jpg'),
(24, 'MartabaK Spesial Sambel Guyur', 'martabak telur', 45000, 'e.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `reset_token_hash` int(11) NOT NULL,
  `reset_token_expires_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `address`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'RIDO RIFKI HAKIM', 'ridorifkihakim@gmail.com', '5657850', '$2y$10$5/tl5R1qEgBKGk84tRqAmOFRyVUMucZ78l9WdRNEjnJ', '90, 90,  vjvfj, vfvfv, vfvf, vfvfvvf, vfvfv - 130533', 9373, '2025-01-08 00:05:25'),
(2, 'what?', 'syifaaulia7138@gmail.com', '0895121296', '$2y$10$.gr.6nX05fRafyvdZQJ/deUJGv4SjV6L2RgrS6QZA5l', 'Y, 87, Guyon, Bekasi, Bekasi, Jawa Barat, Indonesia - 14511', 4930, '2025-01-07 04:19:46'),
(4, 'Willy', 'willydamara@gmail.com', '0898395377', 'f865b53623b121fd34ee5426c792e5c33af8c227', '', 0, '2025-01-06 23:59:28'),
(5, 'Lulu Choirunnisa', 'luluchoirunnisa4@gmail.com', '0812828641', '7c222fb2927d828af22f592134e8932480637c0d', '6, Isekai 5, Konoha, Myeongdong, Myengdong, Busan, Korea Selatan - 3', 0, '2025-01-06 23:59:28'),
(6, 'naufAL', 'naufalljr96@gmail.com', '09080123', '$2y$10$v2WP6VeLDAQfeFMro3U6ze30ysbWiJ7kKl/G9PfPYIQ', '', 6864, '2025-01-07 05:04:42'),
(7, 'syifa aulia', 'jokonanda0@gmail.com', '0859460526', '85d6f78b4e6d732ad76e6d5efff556f8e8ee9ac1', '', 0, '2025-01-07 00:55:01'),
(8, 'Ridki', 'garenamoonton398@gmail.com', '0815176578', '$2y$10$fzF2wMtK2OgXsjS53dJftOWTh1bZI4OAcn2T41hyt1K', '', 5582, '2025-01-07 10:29:08'),
(9, 'Sarah Annisa Meifian', 'sannisameifiana@gmail.com', '5774667230', 'fb656a651b2b1d22cbf60137ee252b3fed3367b4', '18, 88, Jl. Cempaka Wangi, Jakarta, Jakarta, Indonesia, Indonesia - 10640', 0, '2025-01-07 21:30:52'),
(10, 'RIDO RIFKI HAKIM', 'sarababa@gmail.com', '1319357119', '8cb2237d0679ca88db6464eac60da96345513964', 'jl cabang dua, gang sentiong, , Bekasi, Jawa Barat, 17147', 0, '2025-01-07 23:32:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

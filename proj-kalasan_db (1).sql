-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 04:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proj-kalasan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions_log`
--

CREATE TABLE `actions_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action_type` enum('validate','update_record','delete_record','create_record') DEFAULT NULL,
  `tree_records_id` int(11) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `username`, `email`, `password_hash`, `status`, `created_at`) VALUES
(1, 'andreejavier', 'andree', 'andreejavier@gmail.com', '$2y$10$rIg2iHOaD2hBoBB9CSIdOethkhPYbb95R65wSz77Xxyynx8v90pOG', 'active', '2024-10-28 09:59:36'),
(2, 'Marianne Gil Estrada', 'maryanhil', 'mariannegilestrada@gmail.com', '$2y$10$gc25F1wYswAcj7gNdpSFkOFyMd5cJawRPlJVut/vfBCa13Pd7hxHC', 'active', '2024-11-04 03:03:54');

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `id` int(11) NOT NULL,
  `tree_species_id` int(11) DEFAULT NULL,
  `total_count` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tree_images`
--

CREATE TABLE `tree_images` (
  `id` int(11) NOT NULL,
  `tree_record_id` int(11) DEFAULT NULL,
  `image_path` longblob DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tree_images`
--

INSERT INTO `tree_images` (`id`, `tree_record_id`, `image_path`, `uploaded_at`) VALUES
(1, 21, 0x75706c6f6164732f74726565732f494d475f32303234303630385f3036303831372e6a7067, '2024-11-03 12:50:04'),
(2, 21, 0x75706c6f6164732f74726565732f494d475f32303234303630385f3134333330372e6a7067, '2024-11-03 12:52:43'),
(3, 22, 0x75706c6f6164732f74726565732f494d475f32303234303630385f3134343030302e6a7067, '2024-11-03 12:55:48'),
(4, 22, 0x75706c6f6164732f74726565732f494d475f32303234303630385f3134343030302e6a7067, '2024-11-03 13:22:56'),
(5, 21, 0x75706c6f6164732f74726565732f494d475f32303234303630385f3039343032372e6a7067, '2024-11-03 13:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `tree_records`
--

CREATE TABLE `tree_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tree_species_id` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `exif_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exif_data`)),
  `validated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tree_records`
--

INSERT INTO `tree_records` (`id`, `user_id`, `tree_species_id`, `latitude`, `longitude`, `date_time`, `address`, `image_path`, `exif_data`, `validated`, `created_at`) VALUES
(18, 1, NULL, 7.87980250, 125.00839090, '2024-06-08 14:37:33', 'Lake Apo Trail, Valencia, Bukidnon, Northern Mindanao, 8710, Philippines', 'uploads/671f4ff1ddc2e-IMG_20240608_143733.jpg', '{\"latitude\":\"7.8798025\",\"longitude\":\"125.0083909\",\"date_time\":\"2024:06:08 14:37:33\",\"address\":\"Lake Apo Trail, Valencia, Bukidnon, Northern Mindanao, 8710, Philippines\"}', 1, '2024-10-28 08:48:49'),
(19, 1, NULL, 8.36401500, 124.86741730, '2024-06-08 06:08:17', 'Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines', 'uploads/671f5158576f9-IMG_20240608_060817.jpg', '{\"latitude\":\"8.364015\",\"longitude\":\"124.8674173\",\"date_time\":\"2024:06:08 06:08:17\",\"address\":\"Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines\"}', 1, '2024-10-28 08:54:48'),
(20, 1, 5, 7.87975140, 125.00843640, '2024-06-08 14:40:00', 'Lake Apo Trail, Valencia, Bukidnon, Northern Mindanao, 8710, Philippines', 'uploads/671f568e73e40-IMG_20240608_144000.jpg', '{\"latitude\":\"7.879751400000001\",\"longitude\":\"125.0084364\",\"date_time\":\"2024:06:08 14:40:00\",\"address\":\"Lake Apo Trail, Valencia, Bukidnon, Northern Mindanao, 8710, Philippines\"}', 1, '2024-10-28 09:17:02'),
(21, 2, 4, 8.36401500, 124.86741730, '2024-06-08 06:08:17', 'Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines', 'uploads/671f5aaba4668-IMG_20240608_060817.jpg', '{\"latitude\":\"8.364015\",\"longitude\":\"124.8674173\",\"date_time\":\"2024:06:08 06:08:17\",\"address\":\"Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines\"}', 1, '2024-10-28 09:34:35'),
(22, 2, NULL, 7.63796720, 124.72541850, '2024-06-09 09:21:27', 'Magsaysay Avenue, Eastern Wao, Muslim Village, Wao, Lanao del Sur, Bangsamoro, 9716, Philippines', 'uploads/6720a2732b983-IMG_20240609_092127.jpg', '{\"latitude\":\"7.637967199999999\",\"longitude\":\"124.7254185\",\"date_time\":\"2024:06:09 09:21:27\",\"address\":\"Magsaysay Avenue, Eastern Wao, Muslim Village, Wao, Lanao del Sur, Bangsamoro, 9716, Philippines\"}', 1, '2024-10-29 08:53:07'),
(23, 2, NULL, 8.36255117, 124.87401848, '2024-06-08 06:30:32', 'Mangima-Agusan Canyon Bypass Road, Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines', 'uploads/6720ac1f97c0a-IMG_20240608_063032.jpg', '{\"latitude\":\"8.36255117\",\"longitude\":\"124.87401847999999\",\"date_time\":\"2024:06:08 06:30:32\",\"address\":\"Mangima-Agusan Canyon Bypass Road, Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines\"}', 0, '2024-10-29 09:34:23'),
(25, 2, NULL, 8.30881081, 124.98571900, '2024-06-08 10:16:54', 'Sayre Highway, Impasugong, Bukidnon, Northern Mindanao, 8702, Philippines', 'uploads/67222fc0028a7-IMG_20240608_101654.jpg', '{\"latitude\":\"8.30881081\",\"longitude\":\"124.985719\",\"date_time\":\"2024:06:08 10:16:54\",\"address\":\"Sayre Highway, Impasugong, Bukidnon, Northern Mindanao, 8702, Philippines\"}', 0, '2024-10-30 13:08:16'),
(31, 2, 1, 8.36351681, 124.86948394, '2024-10-02 19:13:23', 'Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines', 'uploads/6725dbf4366f5-IMG_20241002_191323_542.jpg', '{\"latitude\":8.363516805555555,\"longitude\":124.86948394444444,\"date_time\":\"2024:10:02 19:13:23\",\"address\":\"Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines\"}', 0, '2024-11-02 07:59:48'),
(32, 2, 6, 7.87975140, 125.00843640, '2024-06-08 14:40:00', 'Lake Apo Trail, Valencia, Bukidnon, Northern Mindanao, 8710, Philippines', 'uploads/67279ae992420-IMG_20240608_144000.jpg', '{\"latitude\":\"7.879751400000001\",\"longitude\":\"125.0084364\",\"date_time\":\"2024:06:08 14:40:00\",\"address\":\"Lake Apo Trail, Valencia, Bukidnon, Northern Mindanao, 8710, Philippines\"}', 0, '2024-11-03 15:46:49'),
(33, 2, NULL, 8.36351681, 124.86948394, '2024-10-02 19:13:23', 'Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines', 'uploads/67279b34c2004-IMG_20241002_191323_542.jpg', '{\"latitude\":\"8.363516805555555\",\"longitude\":\"124.86948394444444\",\"date_time\":\"2024:10:02 19:13:23\",\"address\":\"Kihare, Manolo Fortich, Bukidnon, Northern Mindanao, 8703, Philippines\"}', 0, '2024-11-03 15:48:04'),
(34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-11-04 13:08:19'),
(35, 1, 24, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-11-04 14:10:15'),
(36, 1, 27, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-11-04 14:21:27'),
(37, NULL, 30, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-11-05 03:04:58'),
(38, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-11-05 03:05:41'),
(39, 1, 32, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-11-05 03:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `tree_species`
--

CREATE TABLE `tree_species` (
  `id` int(11) NOT NULL,
  `species_name` varchar(100) NOT NULL,
  `scientific_name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` enum('Endemic','Indigenous') NOT NULL DEFAULT 'Endemic'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tree_species`
--

INSERT INTO `tree_species` (`id`, `species_name`, `scientific_name`, `description`, `created_at`, `category`) VALUES
(1, 'Nara', 'need id', 'Description of Species 1', '2024-10-29 08:29:30', 'Endemic'),
(2, 'Species Name 2', 'Scientific Name 2', 'Description of Species 2', '2024-10-29 08:29:30', 'Indigenous'),
(3, '', NULL, NULL, '0000-00-00 00:00:00', 'Endemic'),
(4, 'Nara ', 'NA', 'NA', '0000-00-00 00:00:00', 'Endemic'),
(5, 'Nara', 'Pterocarpus indicus', 'Narra, also known as Pterocarpus indicus, is a large tree that can grow up to 40 meters in height and 2 meters in diameter. Its bark is rough and scaly, greyish-brown. Its leaves are bright green and oval-shaped, measuring around 10 centimeters long.', '0000-00-00 00:00:00', 'Endemic'),
(6, 'Nara', 'Pterocarpus indicus', 'Narra, also known as Pterocarpus indicus, is a large tree that can grow up to 40 meters in height and 2 meters in diameter. Its bark is rough and scaly, greyish-brown. Its leaves are bright green and oval-shaped, measuring around 10 centimeters long.', '0000-00-00 00:00:00', 'Endemic'),
(7, 'Nara', 'Pterocarpus indicus', 'NA', '2024-11-04 13:16:22', 'Endemic'),
(8, 'Balite', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:21', 'Endemic'),
(9, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:44', 'Endemic'),
(10, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:47', 'Endemic'),
(11, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:48', 'Endemic'),
(12, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:49', 'Endemic'),
(13, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:51', 'Endemic'),
(14, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:37:52', 'Endemic'),
(15, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:38:36', 'Endemic'),
(16, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:40:45', 'Endemic'),
(17, 'Balete', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:40:55', 'Endemic'),
(18, 'Balite', 'Ficus balete Merr', 'From Wikipedia, the free encyclopedia\r\n\r\nBalete tree from a Philippine forest, photographed in 1911\r\n\r\nA balete tree near Tagkawayan in southern Luzon, Philippines\r\nThe balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:44:36', 'Endemic'),
(19, 'Balite', 'Ficus balete Merr', 'From Wikipedia, the free encyclopedia\r\n\r\nBalete tree from a Philippine forest, photographed in 1911\r\n\r\nA balete tree near Tagkawayan in southern Luzon, Philippines\r\nThe balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1', '2024-11-04 13:47:56', 'Endemic'),
(20, 'Balite', 'Ficus balete Merr', 'From Wikipedia, the free encyclopedia\r\n\r\nThe balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1]', '2024-11-04 13:49:20', 'Endemic'),
(21, 'Balite', 'Ficus balete Merr', 'From Wikipedia, the free encyclopedia\r\n\r\nThe balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1]', '2024-11-04 14:01:10', 'Endemic'),
(22, 'Balite', 'Ficus balete Merr', 'The balete tree (also known as balite or baliti) are several species of trees in the Philippines from the genus Ficus, which are broadly referred to as balete in the local language. A number of these are strangler figs, as they germinate upon other trees, before entrapping their host tree entirely and eventually killing it. Consequently the young plants are hemiepiphytes, i.e. epiphytes or air plants that grow several hanging roots which eventually touch the ground and take root. Some baletes produce natural rubber of an inferior quality. The Indian rubber tree, F. elastica, was formerly cultivated to some extent for rubber. Some of the species like tangisang-bayawak or Ficus variegata are large and could probably be utilized for match wood. The wood of Ficus species are soft, light, and of inferior quality, and the trees usually have ill-formed, short boles.[1]', '2024-11-04 14:01:42', 'Endemic'),
(23, 'Balite', 'Ficus balete Merr', 'NA', '2024-11-04 14:03:33', 'Endemic'),
(24, 'Balite', 'Ficus balete Merr', 'b', '2024-11-04 14:10:15', 'Endemic'),
(25, 'Balite', 'Ficus balete Merr', 'b', '2024-11-04 14:17:48', 'Endemic'),
(26, 'Balite', 'Ficus balete Merr', 'b', '2024-11-04 14:20:55', 'Endemic'),
(27, 'Balite', 'Ficus balete Merr', 'b', '2024-11-04 14:21:27', 'Endemic'),
(28, 'Balite', 'Ficus balete Merr', 'na', '2024-11-05 03:03:21', 'Endemic'),
(29, 'Balite', 'Ficus balete Merr', 'm', '2024-11-05 03:04:04', 'Endemic'),
(30, 'Balite', 'Ficus balete Merr', 'm', '2024-11-05 03:04:58', 'Endemic'),
(31, 'Balite', 'Ficus balete Merr', 'na', '2024-11-05 03:05:41', 'Endemic'),
(32, 'Balite', 'Ficus balete Merr', 'NA', '2024-11-05 03:19:16', 'Endemic');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `email`, `role`, `profile_picture`, `created_at`) VALUES
(1, 'Andree Javier', '$2y$10$ERKZ3/BNPS8HOQtr9bRyiO2DtVmcx1t6bqC85IV2cC4riDKa9K46W', 'andreejavier@gmail.com', 'user', NULL, '2024-10-28 05:10:59'),
(2, 'andree', '$2y$10$i3YAnLqaGDKbm.EwGJQUsezG564koMhlzRrAWQvYn6sLKk.uyZV0W', 'andree@gmail.com', 'user', 'IMG_20240608_143733.jpg', '2024-10-28 08:44:01'),
(3, 'maryanhil', '$2y$10$4kc2cSnH7aSX1mW7YMN/pOvoG5n2I1.P0Qvu7s3WfwIb8vDfZXzcO', 'mariannegilestrada@gmail.com', 'user', NULL, '2024-11-04 04:59:54'),
(4, 'bimbo', '$2y$10$2pws2WRB01KpwvI2EiwSs.mGBvnvyhwqzx6R5pJghTwwqPdt71YpK', '20211076@nbsc.edu.ph', 'user', NULL, '2024-11-04 05:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `validations`
--

CREATE TABLE `validations` (
  `id` int(11) NOT NULL,
  `tree_record_id` int(11) DEFAULT NULL,
  `validated_by` int(11) DEFAULT NULL,
  `validation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','agree','disagree') DEFAULT 'pending',
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions_log`
--
ALTER TABLE `actions_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree_records_id` (`tree_records_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree_species_id` (`tree_species_id`);

--
-- Indexes for table `tree_images`
--
ALTER TABLE `tree_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree_record_id` (`tree_record_id`);

--
-- Indexes for table `tree_records`
--
ALTER TABLE `tree_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_tree_species` (`tree_species_id`);

--
-- Indexes for table `tree_species`
--
ALTER TABLE `tree_species`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `validations`
--
ALTER TABLE `validations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree_record_id` (`tree_record_id`),
  ADD KEY `validated_by` (`validated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions_log`
--
ALTER TABLE `actions_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tree_images`
--
ALTER TABLE `tree_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tree_records`
--
ALTER TABLE `tree_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tree_species`
--
ALTER TABLE `tree_species`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `validations`
--
ALTER TABLE `validations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analytics`
--
ALTER TABLE `analytics`
  ADD CONSTRAINT `analytics_ibfk_1` FOREIGN KEY (`tree_species_id`) REFERENCES `tree_species` (`id`);

--
-- Constraints for table `tree_images`
--
ALTER TABLE `tree_images`
  ADD CONSTRAINT `tree_images_ibfk_1` FOREIGN KEY (`tree_record_id`) REFERENCES `tree_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tree_records`
--
ALTER TABLE `tree_records`
  ADD CONSTRAINT `fk_tree_species` FOREIGN KEY (`tree_species_id`) REFERENCES `tree_species` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tree_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tree_records_ibfk_2` FOREIGN KEY (`tree_species_id`) REFERENCES `tree_species` (`id`);

--
-- Constraints for table `validations`
--
ALTER TABLE `validations`
  ADD CONSTRAINT `validations_ibfk_1` FOREIGN KEY (`tree_record_id`) REFERENCES `tree_records` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

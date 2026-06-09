-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 09, 2026 at 10:02 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `natural_disaster_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `disaster_id` tinyint NOT NULL,
  `province_id` int NOT NULL,
  `severity` tinyint NOT NULL COMMENT '1=کم,2=متوسط,3=بحرانی',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `disaster_id` (`disaster_id`),
  KEY `province_id` (`province_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `disaster_id`, `province_id`, `severity`, `message`, `start_time`, `end_time`, `is_active`, `created_at`) VALUES
(1, 6, 29, 3, '⚠️ هشدار سونامی در سواحل هرمزگان. ساکنین مناطق ساحلی فوراً به نقاط مرتفع转移 شوند.', '2026-05-28 22:38:00', '2026-05-30 23:59:59', 1, '2026-06-01 05:41:31'),
(2, 1, 1, 2, 'پسلرزه‌های احتمالی در تبریز. از تردد در ساختمان‌های فرسوده خودداری کنید.', '2026-05-26 08:00:00', '2026-05-29 20:00:00', 1, '2026-06-01 05:41:31'),
(3, 2, 13, 2, 'هشدار سیلابی شدن مسیل‌ها در خوزستان به دلیل بارش‌های پیش‌بینی شده.', '2026-06-01 00:00:00', '2026-06-05 23:59:59', 1, '2026-06-01 05:41:31'),
(4, 4, 16, 1, 'خشکسالی ادامه دارد. مدیریت مصرف آب ضروری است.', '2026-05-01 00:00:00', '2026-08-31 23:59:59', 1, '2026-06-01 05:41:31'),
(5, 8, 23, 2, 'خطر وقوع آتش‌سوزی در جنگل‌های کهگیلویه به دلیل گرما و خشکی هوا.', '2026-06-01 12:00:00', '2026-06-10 20:00:00', 1, '2026-06-01 05:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `backup_logs`
--

DROP TABLE IF EXISTS `backup_logs`;
CREATE TABLE IF NOT EXISTS `backup_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `backup_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `file_size` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `status` enum('pending','read','replied') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'pending',
  `reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disasters`
--

DROP TABLE IF EXISTS `disasters`;
CREATE TABLE IF NOT EXISTS `disasters` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `name_fa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `color_low` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT '#2ecc71',
  `color_medium` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT '#f1c40f',
  `color_high` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT '#e67e22',
  `color_extreme` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT '#e74c3c',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `disasters`
--

INSERT INTO `disasters` (`id`, `name`, `name_fa`, `icon`, `description`, `color_low`, `color_medium`, `color_high`, `color_extreme`) VALUES
(1, 'earthquake', 'زلزله', 'earthquake.svg', 'لرزش ناگهانی زمین ناشی از آزادسازی انرژی در پوسته زمین که می‌تواند خسارات جانی و مالی زیادی به همراه داشته باشد.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(2, 'flood', 'سیل', 'flood.svg', 'طغیان آب در اراضی که معمولاً خشک هستند، ناشی از بارش شدید باران، ذوب برف یا شکستن سدها.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(3, 'storm', 'طوفان', 'storm.svg', 'بادهای شدید و گردباد که می‌تواند درختان را ریشه‌کن کند و به ساختمان‌ها آسیب برساند.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(4, 'drought', 'خشکسالی', 'drought.svg', 'کاهش بارندگی طولانی مدت که منجر به کمبود آب، کاهش محصولات کشاورزی و آسیب به محیط زیست می‌شود.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(5, 'volcano', 'فوران آتشفشان', 'volcano.svg', 'خروج مواد مذاب، گازها و خاکستر از اعماق زمین به سطح که می‌تواند مناطق وسیعی را در بر گیرد.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(6, 'tsunami', 'سونامی', 'tsunami.svg', 'امواج عظیم دریایی ناشی از زلزله زیر آب یا فوران آتشفشان که با نزدیک شدن به ساحل ارتفاع آن بسیار زیاد می‌شود.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(7, 'landslide', 'رانش زمین', 'landslide.svg', 'حرکت توده خاک و سنگ به سمت پایین شیب که می‌تواند جاده‌ها و ساختمان‌ها را تخریب کند.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(8, 'wildfire', 'آتش سوزی جنگل', 'wildfire.svg', 'آتش سوزی گسترده در جنگل‌ها و مراتع که به محیط زیست و حیات وحش آسیب می‌زند.', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(9, 'زلزله', '', '🌋', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(10, 'سیل', '', '💧', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(11, 'خشکسالی', '', '☀️', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(12, 'طوفان', '', '🌪️', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(13, 'رانش زمین', '', '⛰️', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(14, 'سونامی', '', '🌊', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(15, 'آتشفشان', '', '🌋', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(16, 'طوفان گردوغبار', '', '🏜️', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(17, 'گرمای فرین', '', '🔥', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(18, 'فرونشست زمین', '', '📉', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(19, 'آتش‌سوزی طبیعی', '', '🔥', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'),
(20, 'سرمازدگی', '', '❄️', NULL, '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c');

-- --------------------------------------------------------

--
-- Table structure for table `disaster_events`
--

DROP TABLE IF EXISTS `disaster_events`;
CREATE TABLE IF NOT EXISTS `disaster_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `disaster_id` tinyint NOT NULL,
  `province_id` int NOT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `magnitude` decimal(5,2) DEFAULT NULL,
  `casualties` int DEFAULT NULL,
  `damage_estimate` bigint DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `disaster_id` (`disaster_id`),
  KEY `province_id` (`province_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `disaster_events`
--

INSERT INTO `disaster_events` (`id`, `disaster_id`, `province_id`, `city`, `start_date`, `end_date`, `magnitude`, `casualties`, `damage_estimate`, `description`, `created_by`, `created_at`) VALUES
(1, 1, 8, 'تهران', '2025-06-15 08:30:00', '2025-06-18 20:00:00', 5.20, 45, 1200000000, 'زلزله‌ای با بزرگی 5.2 در شمال تهران', 1, '2026-06-01 05:41:31'),
(2, 1, 21, 'بم', '2025-08-10 23:15:00', '2025-08-12 06:00:00', 6.10, 320, 8500000000, 'تخریب گسترده در بم و حومه', 1, '2026-06-01 05:41:31'),
(3, 1, 1, 'تبریز', '2025-10-05 14:20:00', '2025-10-06 02:00:00', 4.80, 12, 250000000, 'زلزله در منطقه سردرود', NULL, '2026-06-01 05:41:31'),
(4, 1, 11, 'مشهد', '2026-01-20 09:45:00', '2026-01-21 18:00:00', 5.50, 28, 780000000, 'زلزله در جنوب مشهد', NULL, '2026-06-01 05:41:31'),
(5, 1, 4, 'اصفهان', '2026-03-02 03:15:00', '2026-03-02 12:00:00', 4.20, 2, 45000000, 'زلزله خفیف در حومه اصفهان', 1, '2026-06-01 05:41:31'),
(6, 2, 13, 'اهواز', '2025-07-28 14:00:00', '2025-08-05 10:30:00', NULL, 12, 650000000, 'طغیان کارون و آبگرفتگی معابر', NULL, '2026-06-01 05:41:31'),
(7, 2, 27, 'نور', '2025-11-05 05:20:00', '2025-11-09 23:00:00', NULL, 8, 410000000, 'بارش ۲۰۰ میلی‌متری در ۲۴ ساعت', NULL, '2026-06-01 05:41:31'),
(8, 2, 24, 'گرگان', '2026-02-18 08:00:00', '2026-02-22 16:00:00', NULL, 3, 125000000, 'سیل در حوضه گرگانرود', 1, '2026-06-01 05:41:31'),
(9, 2, 17, 'شیراز', '2026-04-10 13:30:00', '2026-04-14 09:00:00', NULL, 5, 320000000, 'سیل ناگهانی در دروازه قرآن', NULL, '2026-06-01 05:41:31'),
(10, 3, 29, 'بندرعباس', '2025-09-22 11:00:00', '2025-09-24 18:00:00', NULL, 3, 95000000, 'طوفان با سرعت ۱۱۰ کیلومتر بر ساعت', 1, '2026-06-01 05:41:31'),
(11, 3, 7, 'بوشهر', '2026-01-15 07:00:00', '2026-01-16 14:00:00', NULL, 1, 28000000, 'طوفان گرد و خاک شدید', NULL, '2026-06-01 05:41:31'),
(12, 3, 16, 'چابهار', '2026-03-25 22:00:00', '2026-03-27 06:00:00', NULL, 0, 15000000, 'طوفان موسمی', 1, '2026-06-01 05:41:31'),
(13, 8, 23, 'یاسوج', '2025-08-28 09:00:00', '2025-09-03 12:00:00', NULL, 0, 180000000, 'حریق گسترده در جنگل‌های دنا', NULL, '2026-06-01 05:41:31'),
(14, 8, 21, 'جیرفت', '2025-10-12 14:30:00', '2025-10-15 20:00:00', NULL, 2, 75000000, 'آتش‌سوزی در مراتع', 1, '2026-06-01 05:41:31'),
(15, 8, 11, 'گناباد', '2026-04-05 11:00:00', '2026-04-07 18:00:00', NULL, 0, 42000000, 'آتش‌سوزی در مناطق حفاظت شده', NULL, '2026-06-01 05:41:31'),
(16, 4, 16, 'زاهدان', '2025-06-01 00:00:00', '2025-11-30 23:59:59', NULL, 0, 5000000000, 'کاهش ۷۰ درصدی بارندگی', 1, '2026-06-01 05:41:31'),
(17, 4, 21, 'کرمان', '2025-09-01 00:00:00', '2026-02-28 23:59:59', NULL, 0, 3200000000, 'خشکسالی شدید در حوزه کویر لوت', NULL, '2026-06-01 05:41:31'),
(18, 7, 5, 'طالقان', '2026-03-10 07:45:00', '2026-03-12 16:30:00', NULL, 22, 320000000, 'رانش در محور طالقان - کرج', 1, '2026-06-01 05:41:31'),
(19, 7, 27, 'رامسر', '2026-04-18 05:30:00', '2026-04-19 23:00:00', NULL, 5, 98000000, 'رانش زمین در جاده قدیم رامسر', NULL, '2026-06-01 05:41:31'),
(20, 6, 29, 'قشم', '2026-05-28 22:38:00', '2026-05-30 23:59:00', NULL, 0, 0, 'هشدار اولیه سونامی – تخلیه سواحل', 1, '2026-06-01 05:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
CREATE TABLE IF NOT EXISTS `provinces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `name_en` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `center_lat` decimal(10,6) DEFAULT NULL,
  `center_lng` decimal(10,6) DEFAULT NULL,
  `population` bigint DEFAULT NULL,
  `area_km2` int DEFAULT NULL,
  `total_risk_score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `risk_score` tinyint UNSIGNED DEFAULT '0' COMMENT 'امتیاز کلی ریسک 0-100',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `name_en`, `code`, `center_lat`, `center_lng`, `population`, `area_km2`, `total_risk_score`, `created_at`, `risk_score`) VALUES
(1, 'آذربایجان شرقی', 'East Azerbaijan', '01', 38.078600, 46.292400, 3900000, 45650, 26.25, '2026-06-01 05:41:31', 0),
(2, 'آذربایجان غربی', 'West Azerbaijan', '02', 37.552800, 45.075900, 3265219, 37411, 0.00, '2026-06-01 05:41:31', 0),
(3, 'اردبیل', 'Ardabil', '03', 38.242200, 48.288100, 1270420, 17800, 0.00, '2026-06-01 05:41:31', 0),
(4, 'اصفهان', 'Isfahan', '04', 32.654600, 51.667900, 5120850, 107029, 0.00, '2026-06-01 05:41:31', 0),
(5, 'البرز', 'Alborz', '05', 35.838000, 50.940500, 2712400, 5833, 0.00, '2026-06-01 05:41:31', 0),
(6, 'ایلام', 'Ilam', '06', 33.294500, 46.575900, 580158, 20133, 0.00, '2026-06-01 05:41:31', 0),
(7, 'بوشهر', 'Bushehr', '07', 28.765700, 51.521100, 1163400, 22743, 0.00, '2026-06-01 05:41:31', 0),
(8, 'تهران', 'Tehran', '08', 35.689200, 51.389000, 13267636, 18814, 26.25, '2026-06-01 05:41:31', 0),
(9, 'چهارمحال و بختیاری', 'Chaharmahal and Bakhtiari', '09', 32.292800, 50.850000, 947763, 16328, 0.00, '2026-06-01 05:41:31', 0),
(10, 'خراسان جنوبی', 'South Khorasan', '10', 32.863200, 59.216200, 768898, 151913, 0.00, '2026-06-01 05:41:31', 0),
(11, 'خراسان رضوی', 'Razavi Khorasan', '11', 36.297000, 59.599800, 6434501, 118884, 17.50, '2026-06-01 05:41:31', 0),
(12, 'خراسان شمالی', 'North Khorasan', '12', 37.471800, 57.336000, 863092, 28434, 0.00, '2026-06-01 05:41:31', 0),
(13, 'خوزستان', 'Khuzestan', '13', 31.318300, 48.669200, 4710509, 64055, 30.00, '2026-06-01 05:41:31', 0),
(14, 'زنجان', 'Zanjan', '14', 36.674000, 48.482800, 1057461, 21773, 0.00, '2026-06-01 05:41:31', 0),
(15, 'سمنان', 'Semnan', '15', 35.567700, 53.330500, 702360, 97491, 0.00, '2026-06-01 05:41:31', 0),
(16, 'سیستان و بلوچستان', 'Sistan and Baluchestan', '16', 29.495100, 60.855900, 2775014, 180726, 15.00, '2026-06-01 05:41:31', 0),
(17, 'فارس', 'Fars', '17', 29.519800, 53.131300, 4851274, 122608, 0.00, '2026-06-01 05:41:31', 0),
(18, 'قزوین', 'Qazvin', '18', 36.271900, 50.004500, 1273761, 15567, 0.00, '2026-06-01 05:41:31', 0),
(19, 'قم', 'Qom', '19', 34.639900, 50.875300, 1292283, 11240, 0.00, '2026-06-01 05:41:31', 0),
(20, 'کردستان', 'Kurdistan', '20', 35.557200, 46.803100, 1603011, 29137, 0.00, '2026-06-01 05:41:31', 0),
(21, 'کرمان', 'Kerman', '21', 30.283900, 57.079500, 3164718, 183285, 0.00, '2026-06-01 05:41:31', 0),
(22, 'کرمانشاه', 'Kermanshah', '22', 34.327200, 47.099500, 1952434, 24998, 0.00, '2026-06-01 05:41:31', 0),
(23, 'کهگیلویه و بویراحمد', 'Kohgiluyeh and Boyer-Ahmad', '23', 30.657800, 51.579100, 713052, 15504, 0.00, '2026-06-01 05:41:31', 0),
(24, 'گلستان', 'Golestan', '24', 36.831900, 54.414600, 1868819, 20367, 0.00, '2026-06-01 05:41:31', 0),
(25, 'گیلان', 'Gilan', '25', 37.280800, 49.581600, 2530696, 14042, 0.00, '2026-06-01 05:41:31', 0),
(26, 'لرستان', 'Lorestan', '26', 33.484000, 48.368800, 1760649, 28294, 0.00, '2026-06-01 05:41:31', 0),
(27, 'مازندران', 'Mazandaran', '27', 36.563500, 53.112400, 3283582, 23842, 0.00, '2026-06-01 05:41:31', 0),
(28, 'مرکزی', 'Markazi', '28', 34.069700, 49.688100, 1429475, 29130, 0.00, '2026-06-01 05:41:31', 0),
(29, 'هرمزگان', 'Hormozgan', '29', 27.173700, 56.275300, 1776415, 70697, 0.00, '2026-06-01 05:41:31', 0),
(30, 'همدان', 'Hamadan', '30', 34.799600, 48.525900, 1738234, 19368, 0.00, '2026-06-01 05:41:31', 0),
(31, 'یزد', 'Yazd', '31', 31.888700, 54.351200, 1138533, 129285, 0.00, '2026-06-01 05:41:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `province_disaster_risk`
--

DROP TABLE IF EXISTS `province_disaster_risk`;
CREATE TABLE IF NOT EXISTS `province_disaster_risk` (
  `province_id` int NOT NULL,
  `disaster_id` tinyint NOT NULL,
  `risk_value` tinyint NOT NULL,
  `confidence_score` float NOT NULL DEFAULT '0.5',
  `data_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`province_id`,`disaster_id`),
  KEY `risk_value` (`risk_value`),
  KEY `disaster_id` (`disaster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `province_disaster_risk`
--

INSERT INTO `province_disaster_risk` (`province_id`, `disaster_id`, `risk_value`, `confidence_score`, `data_source`, `last_updated`) VALUES
(1, 1, 3, 0.79, 'مرکز تحقیقات زلزله', '2026-06-01 05:41:32'),
(8, 1, 4, 0.95, 'مرکز تحقیقات زلزله', '2026-06-09 09:28:34'),
(8, 2, 2, 0.8, 'سیستم هوشمند', '2026-06-09 09:28:34'),
(8, 3, 1, 0.7, 'سیستم هوشمند', '2026-06-09 09:28:34'),
(8, 4, 1, 0.85, 'سیستم هوشمند', '2026-06-09 09:28:34'),
(8, 7, 2, 0.75, 'سیستم هوشمند', '2026-06-09 09:28:34'),
(11, 1, 2, 0.68, 'مرکز تحقیقات زلزله', '2026-06-01 05:41:32'),
(13, 2, 4, 0.92, 'سازمان هواشناسی', '2026-06-01 05:41:32'),
(16, 4, 4, 0.88, 'وزارت نیرو', '2026-06-01 05:41:32'),
(21, 8, 3, 0.74, 'سازمان جنگل‌ها', '2026-06-01 05:41:32'),
(23, 8, 3, 0.81, 'سازمان جنگل‌ها', '2026-06-01 05:41:32'),
(29, 6, 3, 0.78, 'مرکز اقیانوس‌شناسی', '2026-06-01 05:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `province_risk_scores`
--

DROP TABLE IF EXISTS `province_risk_scores`;
CREATE TABLE IF NOT EXISTS `province_risk_scores` (
  `province_id` int NOT NULL,
  `risk_score` decimal(5,2) NOT NULL COMMENT '0-100',
  `calculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `province_risk_scores`
--

INSERT INTO `province_risk_scores` (`province_id`, `risk_score`, `calculated_at`) VALUES
(1, 26.25, '2026-06-08 16:59:00'),
(2, 0.00, '2026-06-08 16:59:00'),
(3, 0.00, '2026-06-08 16:59:00'),
(4, 0.00, '2026-06-08 16:59:00'),
(5, 0.00, '2026-06-08 16:59:00'),
(6, 0.00, '2026-06-08 16:59:00'),
(7, 0.00, '2026-06-08 16:59:00'),
(8, 26.25, '2026-06-08 16:59:00'),
(9, 0.00, '2026-06-08 16:59:00'),
(10, 0.00, '2026-06-08 16:59:00'),
(11, 17.50, '2026-06-08 16:59:00'),
(12, 0.00, '2026-06-08 16:59:00'),
(13, 30.00, '2026-06-08 16:59:00'),
(14, 0.00, '2026-06-08 16:59:00'),
(15, 0.00, '2026-06-08 16:59:00'),
(16, 15.00, '2026-06-08 16:59:00'),
(17, 0.00, '2026-06-08 16:59:00'),
(18, 0.00, '2026-06-08 16:59:00'),
(19, 0.00, '2026-06-08 16:59:00'),
(20, 0.00, '2026-06-08 16:59:00'),
(21, 0.00, '2026-06-08 16:59:00'),
(22, 0.00, '2026-06-08 16:59:00'),
(23, 0.00, '2026-06-08 16:59:00'),
(24, 0.00, '2026-06-08 16:59:00'),
(25, 0.00, '2026-06-08 16:59:00'),
(26, 0.00, '2026-06-08 16:59:00'),
(27, 0.00, '2026-06-08 16:59:00'),
(28, 0.00, '2026-06-08 16:59:00'),
(29, 0.00, '2026-06-08 16:59:00'),
(30, 0.00, '2026-06-08 16:59:00'),
(31, 0.00, '2026-06-08 16:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

DROP TABLE IF EXISTS `recommendations`;
CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `disaster_id` tinyint UNSIGNED NOT NULL,
  `priority` tinyint UNSIGNED DEFAULT '1',
  `advice` text COLLATE utf8mb4_persian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `disaster_id` (`disaster_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`id`, `disaster_id`, `priority`, `advice`) VALUES
(1, 1, 1, 'ایمن‌سازی سازه، برگزاری مانور زلزله، آماده‌سازی کیف اضطراری'),
(2, 2, 1, 'لایروبی مسیل‌ها، سامانه هشدار سیل، ممنوعیت ساخت‌وساز در حریم رودخانه'),
(3, 3, 1, 'مدیریت مصرف آب، توسعه کشت‌های کم‌آب‌بر، استفاده از پساب'),
(4, 4, 1, 'تقویت سازه‌ها، تثبیت بادگیرها، هشدار طوفان‌های شدید'),
(5, 12, 1, 'عایق‌بندی تأسیسات، محافظت از محصولات کشاورزی، تجهیز راه‌های روستایی به نمک‌پاشی'),
(6, 1, 1, 'ایمن‌سازی سازه، برگزاری مانور زلزله، آماده‌سازی کیف اضطراری'),
(7, 1, 1, 'اجرای طرح‌های مقاوم‌سازی بافت فرسوده'),
(8, 1, 1, 'نصب و نگهداری سامانه‌های هشدار سریع'),
(9, 2, 1, 'لایروبی مسیل‌ها، سامانه هشدار سیل، ممنوعیت ساخت‌وساز در حریم رودخانه'),
(10, 2, 1, 'تهیه نقشه‌های پهنه‌بندی سیل'),
(11, 2, 1, 'مدیریت سدها و رهاسازی کنترل‌شده'),
(12, 3, 1, 'مدیریت مصرف آب، توسعه کشت‌های کم‌آب‌بر، استفاده از پساب'),
(13, 3, 1, 'اجرای طرح‌های تغذیه مصنوعی آبخوان'),
(14, 3, 1, 'آموزش همگانی برای کاهش مصرف'),
(15, 4, 1, 'تقویت سازه‌ها، تثبیت بادگیرها، هشدار طوفان‌های شدید'),
(16, 4, 1, 'نگهداری از درختان و حذف شاخه‌های خشک'),
(17, 4, 1, 'ایمن‌سازی تابلوها و سازه‌های شهری'),
(18, 5, 1, 'پایش مداوم حرکات دامنه‌ای، ممنوعیت ساخت در شیب‌های بالای 20 درصد'),
(19, 5, 1, 'اجرای سیستم‌های زهکشی و مهار زمین'),
(20, 5, 1, 'جنگل‌کاری و تثبیت خاک'),
(21, 6, 1, 'استقرار سامانه هشدار سونامی، برنامه تخلیه سریع سواحل'),
(22, 6, 1, 'ممنوعیت ساخت در حریم ۵۰۰ متری ساحل در مناطق پرخطر'),
(23, 6, 1, 'آموزش عمومی برای تشخیص علائم سونامی'),
(24, 7, 1, 'پایش دائمی فعالیت آتشفشانی، آماده‌باش تیم‌های امداد'),
(25, 7, 1, 'تهیه نقشه خطر و مسیرهای تخلیه'),
(26, 7, 1, 'آماده‌سازی تجهیزات حفاظت تنفسی'),
(27, 8, 1, 'کنترل کانون‌های گردوغبار (مالچ‌پاشی، کشت گیاهان مقاوم)'),
(28, 8, 1, 'استفاده از ماسک‌های تنفسی در روزهای بحرانی'),
(29, 8, 1, 'ایستگاه‌های پایش کیفیت هوا'),
(30, 9, 1, 'ایجاد مراکز خنک‌رسانی شهری، هشدار امواج گرما'),
(31, 9, 1, 'نگهداری از سالمندان و کودکان در ساعات اوج گرما'),
(32, 9, 1, 'کاهش جزایر حرارتی (توسعه فضای سبز)'),
(33, 10, 1, 'کاهش برداشت از سفره‌های زیرزمینی، تغذیه مصنوعی'),
(34, 10, 1, 'بازچرخانی آب و استفاده از آب‌های سطحی'),
(35, 10, 1, 'اجرای پروژه‌های تزریق آب به آبخوان'),
(36, 11, 1, 'پاکسازی پوشش گیاهی خشک، ایجاد آتش‌بُر'),
(37, 11, 1, 'آموزش جوامع محلی برای پیشگیری از آتش‌سوزی'),
(38, 11, 1, 'استقرار تیم‌های واکنش سریع'),
(39, 12, 1, 'عایق‌بندی تأسیسات، محافظت از محصولات کشاورزی، تجهیز راه‌های روستایی به نمک‌پاشی'),
(40, 12, 1, 'استفاده از ارقام مقاوم به سرما'),
(41, 12, 1, 'پیش‌بینی و هشدار یخبندان');

-- --------------------------------------------------------

--
-- Table structure for table `risk_history`
--

DROP TABLE IF EXISTS `risk_history`;
CREATE TABLE IF NOT EXISTS `risk_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `province_id` int NOT NULL,
  `risk_score` decimal(5,2) NOT NULL COMMENT 'امتیاز ریسک 0 تا 100',
  `record_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `province_id` (`province_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `risk_history`
--

INSERT INTO `risk_history` (`id`, `province_id`, `risk_score`, `record_date`) VALUES
(1, 8, 65.50, '2026-05-10'),
(2, 8, 66.20, '2026-05-11'),
(3, 8, 67.80, '2026-05-12'),
(4, 8, 65.10, '2026-05-13'),
(5, 8, 68.30, '2026-05-14'),
(6, 8, 70.40, '2026-05-15'),
(7, 8, 72.10, '2026-05-16'),
(8, 8, 71.50, '2026-05-17'),
(9, 8, 73.20, '2026-05-18'),
(10, 8, 74.00, '2026-05-19'),
(11, 8, 72.80, '2026-05-20'),
(12, 8, 71.10, '2026-05-21'),
(13, 8, 73.50, '2026-05-22'),
(14, 8, 75.20, '2026-05-23'),
(15, 8, 76.00, '2026-05-24'),
(16, 8, 74.80, '2026-05-25'),
(17, 8, 73.30, '2026-05-26'),
(18, 8, 75.90, '2026-05-27'),
(19, 8, 77.40, '2026-05-28'),
(20, 8, 78.00, '2026-05-29'),
(21, 8, 76.50, '2026-05-30'),
(22, 8, 77.80, '2026-05-31'),
(23, 8, 79.20, '2026-06-01'),
(24, 8, 78.50, '2026-06-02'),
(25, 8, 80.10, '2026-06-03'),
(26, 8, 81.30, '2026-06-04'),
(27, 8, 80.00, '2026-06-05'),
(28, 8, 79.70, '2026-06-06'),
(29, 8, 81.90, '2026-06-07'),
(30, 8, 82.40, '2026-06-08');

-- --------------------------------------------------------

--
-- Table structure for table `risk_levels`
--

DROP TABLE IF EXISTS `risk_levels`;
CREATE TABLE IF NOT EXISTS `risk_levels` (
  `level` tinyint NOT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  PRIMARY KEY (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `risk_levels`
--

INSERT INTO `risk_levels` (`level`, `name`, `color`, `description`) VALUES
(0, 'ناچیز', '#2ecc71', 'خطر بسیار کم - نیاز به آگاهی اولیه'),
(1, 'کم', '#2ecc71', 'خطر پایین - نیاز به آگاهی و رصد'),
(2, 'متوسط', '#f1c40f', 'خطر متوسط - نیاز به هشدار و آمادگی'),
(3, 'زیاد', '#e67e22', 'خطر بالا - نیاز به آمادگی کامل'),
(4, 'بحرانی', '#e74c3c', 'خطر بحرانی - نیاز به اقدام فوری');

-- --------------------------------------------------------

--
-- Table structure for table `risk_weights`
--

DROP TABLE IF EXISTS `risk_weights`;
CREATE TABLE IF NOT EXISTS `risk_weights` (
  `disaster_id` tinyint NOT NULL,
  `weight` decimal(3,2) NOT NULL,
  PRIMARY KEY (`disaster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `risk_weights`
--

INSERT INTO `risk_weights` (`disaster_id`, `weight`) VALUES
(1, 0.35),
(2, 0.30),
(3, 0.20),
(4, 0.15),
(5, 0.20),
(6, 0.20),
(7, 0.20),
(8, 0.20);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `setting_type` enum('text','textarea','image','number') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `updated_at`) VALUES
(1, 'site_name', 'Natural Disaster Controller', 'text', '2026-06-01 05:41:32'),
(2, 'site_email', 'info@ndc.ir', 'text', '2026-06-01 05:41:32'),
(3, 'site_phone', '021-88888888', 'text', '2026-06-01 05:41:32'),
(4, 'site_address', 'تهران، خیابان انقلاب، پلاک ۱۲۳', 'text', '2026-06-01 05:41:32'),
(5, 'site_description', 'پلتفرم مدیریت و پیشبینی بلایای طبیعی', 'textarea', '2026-06-01 05:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `role` enum('admin','expert','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `is_active`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@ndc.ir', '$2y$10$nfGU322SHFuW/puRuFcIHOIzaaB6owWYghgjcus0e.cAT4V7iXLWm', 'مدیر سیستم', 'admin', 1, '2026-06-01 05:41:31', '2026-06-01 10:40:44'),
(3, 'user1', 'user1@gmail.com', '$2y$10$iVlxdXcEIs1yIkICp8Jl..XgMr.eesZm2axgavcJHh.Y9BsyUKUrK', 'کاربر عادی', 'user', 1, '2026-06-01 05:41:31', '2026-06-01 10:33:14'),
(9, 'LURIX', 'player.programmer.f@gmail.com', '$2y$10$wYrnx0/HmIsbuuClsZm4Nu7P4zzpke1Kv5Gkh8Mn2fIUVd64oSQfi', NULL, 'admin', 1, '2026-06-01 07:00:26', '2026-06-01 10:43:06'),
(10, 'farham', 'farhame42@gmail.com', '$2y$10$fOqAbc53WBwGY92YvN.6iepdBeEQipljg7j5AtW7gojxnLglRjvMu', NULL, 'expert', 1, '2026-06-01 07:12:56', '2026-06-01 10:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_reports`
--

DROP TABLE IF EXISTS `user_reports`;
CREATE TABLE IF NOT EXISTS `user_reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `province_id` int NOT NULL,
  `disaster_id` tinyint NOT NULL,
  `location` point DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `photo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `severity_estimate` tinyint DEFAULT NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `province_id` (`province_id`),
  KEY `disaster_id` (`disaster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`disaster_id`) REFERENCES `disasters` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `disaster_events`
--
ALTER TABLE `disaster_events`
  ADD CONSTRAINT `disaster_events_ibfk_1` FOREIGN KEY (`disaster_id`) REFERENCES `disasters` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `disaster_events_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `disaster_events_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `province_disaster_risk`
--
ALTER TABLE `province_disaster_risk`
  ADD CONSTRAINT `province_disaster_risk_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `province_disaster_risk_ibfk_2` FOREIGN KEY (`disaster_id`) REFERENCES `disasters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `province_disaster_risk_ibfk_3` FOREIGN KEY (`risk_value`) REFERENCES `risk_levels` (`level`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `province_risk_scores`
--
ALTER TABLE `province_risk_scores`
  ADD CONSTRAINT `province_risk_scores_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `risk_history`
--
ALTER TABLE `risk_history`
  ADD CONSTRAINT `fk_risk_history_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `risk_weights`
--
ALTER TABLE `risk_weights`
  ADD CONSTRAINT `risk_weights_ibfk_1` FOREIGN KEY (`disaster_id`) REFERENCES `disasters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_reports`
--
ALTER TABLE `user_reports`
  ADD CONSTRAINT `user_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_reports_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_reports_ibfk_3` FOREIGN KEY (`disaster_id`) REFERENCES `disasters` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

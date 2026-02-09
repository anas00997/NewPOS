-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2025 at 06:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `image`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Gemma Ball', 'Quia alias ad a inci', 1, '2025-07-17 16:46:11', '2025-07-17 16:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `active`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(2, 'Euro', 'EUR', '€', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(3, 'British Pound', 'GBP', '£', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(4, 'Japanese Yen', 'JPY', '¥', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(5, 'Australian Dollar', 'AUD', 'A$', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(6, 'Canadian Dollar', 'CAD', 'C$', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(7, 'Chinese Yuan', 'CNY', '¥', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(8, 'Indian Rupee', 'INR', '₹', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(9, 'Indonesian Rupiah', 'IDR', 'Rp', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(10, 'Pakistani Rupee', 'PKR', '₨', 1, '2025-07-17 16:45:56', '2025-07-17 17:04:51'),
(11, 'Bangladeshi Taka', 'BDT', '৳', 0, '2025-07-17 16:45:56', '2025-07-17 17:04:51'),
(12, 'Vietnamese Dong', 'VND', '₫', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(13, 'Philippine Peso', 'PHP', '₱', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(14, 'Thai Baht', 'THB', '฿', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(15, 'South Korean Won', 'KRW', '₩', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(16, 'Malaysian Ringgit', 'MYR', 'RM', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(17, 'Singapore Dollar', 'SGD', 'S$', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(18, 'Sri Lankan Rupee', 'LKR', '₨', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(19, 'Nepalese Rupee', 'NPR', '₨', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(20, 'Afghan Afghani', 'AFN', '؋', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(21, 'Iraqi Dinar', 'IQD', 'ع.د', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(22, 'Iranian Rial', 'IRR', '﷼', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(23, 'Saudi Riyal', 'SAR', '﷼', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(24, 'Israeli New Shekel', 'ILS', '₪', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(25, 'Turkish Lira', 'TRY', '₺', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(26, 'Emirati Dirham', 'AED', 'د.إ', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(27, 'Qatari Riyal', 'QAR', '﷼', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(28, 'Omani Rial', 'OMR', '﷼', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(29, 'Kuwaiti Dinar', 'KWD', 'د.ك', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(30, 'Jordanian Dinar', 'JOD', 'د.ا', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(31, 'Lebanese Pound', 'LBP', 'ل.ل', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(32, 'Syrian Pound', 'SYP', '£', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(33, 'Yemeni Rial', 'YER', '﷼', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(34, 'Armenian Dram', 'AMD', '֏', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(35, 'Azerbaijani Manat', 'AZN', '₼', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(36, 'Georgian Lari', 'GEL', '₾', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(37, 'Kazakhstani Tenge', 'KZT', '₸', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(38, 'Uzbekistani Som', 'UZS', 'лв', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(39, 'Turkmenistan Manat', 'TMT', 'm', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(40, 'Tajikistani Somoni', 'TJS', 'ЅМ', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(41, 'Kyrgyzstani Som', 'KGS', 'лв', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(42, 'Mongolian Tugrik', 'MNT', '₮', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(43, 'Bahraini Dinar', 'BHD', '.د.ب', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(44, 'Maldivian Rufiyaa', 'MVR', 'Rf', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(45, 'Bhutanese Ngultrum', 'BTN', 'Nu.', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(46, 'Myanmar Kyat', 'MMK', 'K', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(47, 'Laotian Kip', 'LAK', '₭', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(48, 'Cambodian Riel', 'KHR', '៛', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(49, 'Brunei Dollar', 'BND', 'B$', 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email_address`, `dob`, `created_at`, `updated_at`) VALUES
(1, 'Walking Customer', '012345678', NULL, NULL, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(2, 'Erich Acevedo', '+1 (125) 968-7982', 'kaxuqe@mailinator.com', '2025-07-06', '2025-07-17 17:05:15', '2025-07-17 17:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forget_passwords`
--

CREATE TABLE `forget_passwords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `failed_attempt` smallint(6) NOT NULL DEFAULT 0,
  `token` varchar(255) DEFAULT NULL,
  `suspend_duration` varchar(255) NOT NULL DEFAULT '0',
  `resent_count` smallint(6) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_11_051813_create_forget_passwords_table', 1),
(6, '2023_07_18_170442_create_permission_tables', 1),
(7, '2024_09_10_161412_create_categories_table', 1),
(8, '2024_09_10_161420_create_brands_table', 1),
(9, '2024_09_10_161421_create_units_table', 1),
(10, '2024_09_10_161422_create_products_table', 1),
(11, '2024_09_10_161609_create_pos_carts_table', 1),
(12, '2024_09_10_161620_create_customers_table', 1),
(13, '2024_09_10_161625_create_orders_table', 1),
(14, '2024_09_10_161633_create_order_products_table', 1),
(15, '2024_10_15_144038_create_order_transactions_table', 1),
(16, '2024_10_16_123030_create_suppliers_table', 1),
(17, '2024_10_16_173030_create_purchases_table', 1),
(18, '2024_10_16_190049_create_purchase_items_table', 1),
(19, '2024_10_31_105132_create_currencies_table', 1),
(20, '2025_07_17_211931_remove_discounted_price_from_products_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `sub_total` double NOT NULL DEFAULT 0 COMMENT 'sumOf(total) from order_products table',
  `total` double NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `is_returned` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_id`, `sub_total`, `total`, `note`, `is_returned`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, 100, NULL, 0, 1, '2025-07-17 17:02:20', '2025-07-17 17:02:20'),
(2, 1, 1, 5000, 5000, NULL, 0, 1, '2025-07-17 17:09:25', '2025-07-17 17:09:25'),
(3, 1, 1, 500, 500, NULL, 0, 1, '2025-07-17 17:11:55', '2025-07-17 17:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` double NOT NULL DEFAULT 0,
  `purchase_price` double NOT NULL DEFAULT 0,
  `discount` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `total` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `order_id`, `product_id`, `quantity`, `price`, `purchase_price`, `discount`, `sub_total`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 100, 0, 0, 0, 100, '2025-07-17 17:02:20', '2025-07-17 17:02:20'),
(2, 2, 1, 1, 5000, 0, 0, 0, 5000, '2025-07-17 17:09:25', '2025-07-17 17:09:25'),
(3, 3, 1, 1, 500, 0, 0, 0, 500, '2025-07-17 17:11:55', '2025-07-17 17:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_transactions`
--

CREATE TABLE `order_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` double(10,2) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_by` varchar(255) NOT NULL COMMENT 'bank,cash,card',
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(2, 'customer_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(3, 'customer_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(4, 'customer_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(5, 'customer_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(6, 'customer_sales', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(7, 'supplier_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(8, 'supplier_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(9, 'supplier_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(10, 'supplier_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(11, 'product_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(12, 'product_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(13, 'product_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(14, 'product_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(15, 'product_import', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(16, 'brand_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(17, 'brand_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(18, 'brand_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(19, 'brand_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(20, 'category_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(21, 'category_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(22, 'category_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(23, 'category_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(24, 'unit_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(25, 'unit_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(26, 'unit_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(27, 'unit_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(28, 'sale_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(29, 'sale_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(30, 'sale_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(31, 'sale_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(32, 'purchase_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(33, 'purchase_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(34, 'purchase_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(35, 'purchase_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(36, 'reports_summary', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(37, 'reports_sales', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(38, 'reports_inventory', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(39, 'currency_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(40, 'currency_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(41, 'currency_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(42, 'currency_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(43, 'currency_set_default', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(44, 'role_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(45, 'role_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(46, 'role_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(47, 'role_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(48, 'permission_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(49, 'user_create', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(50, 'user_view', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(51, 'user_update', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(52, 'user_delete', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(53, 'user_suspend', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(54, 'website_settings', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(55, 'contact_settings', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(56, 'socials_settings', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(57, 'style_settings', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(58, 'custom_settings', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(59, 'notification_settings', 'web', '2025-07-17 16:45:57', '2025-07-17 16:45:57'),
(60, 'website_status_settings', 'web', '2025-07-17 16:45:57', '2025-07-17 16:45:57'),
(61, 'invoice_settings', 'web', '2025-07-17 16:45:57', '2025-07-17 16:45:57'),
(62, 'product_purchase', 'web', '2025-07-17 16:45:57', '2025-07-17 16:45:57'),
(63, 'sale_edit', 'web', '2025-07-17 16:45:57', '2025-07-17 16:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_carts`
--

CREATE TABLE `pos_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL DEFAULT '0',
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `image`, `name`, `slug`, `sku`, `description`, `category_id`, `quantity`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'William Lambert', 'william-lambert', '0', NULL, 1, -3, 1, '2025-07-17 16:55:45', '2025-07-17 17:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sub_total` double(10,2) NOT NULL DEFAULT 0.00,
  `tax` double(10,2) NOT NULL DEFAULT 0.00,
  `discount_value` double(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(255) NOT NULL DEFAULT 'fixed',
  `shipping` double(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` double(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_price` double(10,2) NOT NULL DEFAULT 0.00,
  `price` double(10,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(2, 'cashier', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(3, 'sales_associate', 'web', '2025-07-17 16:45:56', '2025-07-17 16:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 2),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(28, 2),
(28, 3),
(29, 1),
(29, 2),
(29, 3),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 2),
(63, 3);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Own Supplier', '012345678', NULL, '2025-07-17 16:45:56', '2025-07-17 16:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `title`, `short_name`, `created_at`, `updated_at`) VALUES
(1, 'Piece', 'pcs', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(2, 'Kilogram', 'kg', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(3, 'Liter', 'L', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(4, 'Meter', 'm', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(5, 'Dozen', 'dz', '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(6, 'Box', 'box', '2025-07-17 16:45:56', '2025-07-17 16:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `is_google_registered` tinyint(1) NOT NULL DEFAULT 0,
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `username`, `profile_image`, `google_id`, `is_google_registered`, `is_suspended`, `created_at`, `updated_at`) VALUES
(1, 'Mr Admin', 'demo@qtecsolution.net', NULL, '$2y$10$qcH633xh9yKWTiKdIS/JyOgfU9Dpqx5NYCdAOYuscgKnWWQQBsO2K', NULL, '68791ab467a91', NULL, NULL, 0, 0, '2025-07-17 16:45:56', '2025-07-17 16:45:56'),
(2, 'Mr Cashier', 'cashier@gmail.com', NULL, '$2y$10$tgHwahYA1rkUQ/CGlGsfq.FsZ0sS5Pz/.gD4ar2yy/Ml.cRJcmFSy', NULL, '68791ab513cf8', NULL, NULL, 0, 0, '2025-07-17 16:45:57', '2025-07-17 16:45:57'),
(3, 'Mr Sales', 'sales@gmail.com', NULL, '$2y$10$ZtTXs4VAC8GLHUsAwya2ae7liHF.HZEuItqXsUg/JAR2cFbvWtkcS', NULL, '68791ab521532', NULL, NULL, 0, 0, '2025-07-17 16:45:57', '2025-07-17 16:45:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_phone_unique` (`phone`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `forget_passwords`
--
ALTER TABLE `forget_passwords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `forget_passwords_user_id_unique` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_products_order_id_foreign` (`order_id`),
  ADD KEY `order_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_transactions`
--
ALTER TABLE `order_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_transactions_order_id_foreign` (`order_id`),
  ADD KEY `order_transactions_customer_id_foreign` (`customer_id`),
  ADD KEY `order_transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pos_carts`
--
ALTER TABLE `pos_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_carts_product_id_foreign` (`product_id`),
  ADD KEY `pos_carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_phone_unique` (`phone`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forget_passwords`
--
ALTER TABLE `forget_passwords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_transactions`
--
ALTER TABLE `order_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_carts`
--
ALTER TABLE `pos_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_transactions`
--
ALTER TABLE `order_transactions`
  ADD CONSTRAINT `order_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pos_carts`
--
ALTER TABLE `pos_carts`
  ADD CONSTRAINT `pos_carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

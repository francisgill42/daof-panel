-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2020 at 01:46 PM
-- Server version: 5.7.31
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sales-app-ai`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_details`
--

DROP TABLE IF EXISTS `bank_details`;
CREATE TABLE IF NOT EXISTS `bank_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iban_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_details`
--

INSERT INTO `bank_details` (`id`, `bank_name`, `branch_name`, `account_title`, `iban_number`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, '1|ALBARAKA', '1|Blue Area', 'TSETING', 'SDOIUSFNLSFJKLKJSKDKKDKD', 1, '2020-08-18 14:34:52', '2020-08-18 14:34:52'),
(2, '1|ALBARAKA', '4|Gulshan-e-Iqbal', 'TESTING PURPOSE', 'TESTING PURPOSE 111111', 4, '2020-08-19 07:00:02', '2020-08-19 07:00:02'),
(3, '1|ALBARAKA', '1|Blue Area', 'TEST', '031031301013003000300333', 5, '2020-09-30 12:37:42', '2020-09-30 12:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `qq` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic_attachment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic_issue_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pob_city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pob_country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occ_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_emp_bes_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_experience` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_of_business` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_dependants` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_figure` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `average_annual_income` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refused_account_by_institute` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `high_value_item` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soi_attachment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zakat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zakat_options` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zakat_certificate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `qq`, `name`, `father_name`, `mother_name`, `dob`, `cnic`, `cnic_attachment`, `cnic_issue_date`, `pob_city`, `pob_country`, `email`, `cell`, `occupation`, `occ_name`, `designation`, `department`, `org_emp_bes_name`, `working_experience`, `age_of_business`, `education`, `marital_status`, `no_of_dependants`, `public_figure`, `average_annual_income`, `refused_account_by_institute`, `high_value_item`, `soi`, `soi_attachment`, `address`, `city1`, `country1`, `zakat`, `zakat_options`, `zakat_certificate`, `created_at`, `updated_at`) VALUES
(1, 'pk', 'wow', 'TSSF', 'SSF', '1986-05-07', '94949-4949494-9', 'two.jpeg', '2020-08-18', '1|KARACHI', '1|Pakistan', 'FRANCISGILL1000@GMAIL.COM', '031085598585', 'Housewife', NULL, NULL, NULL, NULL, NULL, NULL, 'Undergraduate', 'Single', '5', 'no', '250-500k', 'no', 'no', 'Salary', 'error_in_credentails.jpeg', 'THIS', '2|Ahmadi', '2|Kuwait', 'no', 'file', '10153635_749537288434749_120338321888433169_n.jpg', '2020-08-18 14:34:52', '2020-08-18 15:22:31'),
(2, 'pk', 'TESTING PURPOSE', 'TESTING PURPOSE', 'TESTING PURPOSE', '1987-01-01', '11111-1111111-1', 'WordPress.jpg', '2020-01-01', '16|Abu Dhabi', '2|United Arab Emirates', 'SHAHEEDKHAN336@GMAIL.COM', '2', 'Housewife', NULL, NULL, NULL, NULL, NULL, NULL, 'Undergraduate', 'Married', '1', 'yes', '10mn-100mn', 'no', 'TESTING PURPOSE', 'Self-Owned', 'WordPress.jpg', 'TESTING PURPOSE', '16|Abu Dhabi', '2|United Arab Emirates', 'no', 'file', 'WordPress.jpg', '2020-08-19 06:52:09', '2020-08-19 06:52:09'),
(3, 'pk', 'TESTING PURPOSE', 'TESTING PURPOSE', 'TESTING PURPOSE', '2000-01-01', '111111111111111', 'WordPress.jpg', '2020-08-01', '17|Sharq', '3|Kuwait', 'SHAHEEDKHAN336@GMSIL.COM', '121221', 'Govt. Employee', 'TESTING PURPOSE', 'TESTING PURPOSE', 'TESTING PURPOSE', 'TESTING PURPOSE', '2', NULL, 'Postgraduate', 'Married', '2', 'no', '500k-1mn', 'no', 'no', 'Home Remittance', 'WordPress.jpg', 'TESTING PURPOSE', '17|Sharq', '3|Kuwait', 'no', 'file', 'WordPress.jpg', '2020-08-19 06:56:35', '2020-08-19 06:56:35'),
(4, 'pk', 'TESTING(Customer name)', 'TESTING (Customer F)', 'TESTING (Customer M)', '2000-01-01', '1111111111222222', 'WordPress.jpg', '2020-08-01', '17|Sharq', '3|Kuwait', 'SHAHEEDKHAN336@GMIL.COM', '121221', 'Govt. Employee', 'TESTING PURPOSE', 'TESTING PURPOSE', 'TESTING PURPOSE', 'TESTING PURPOSE', '2', NULL, 'Postgraduate', 'Married', '2', 'no', '500k-1mn', 'no', 'no', 'Home Remittance', 'vector1.jpg', 'TESTING PURPOSE2', '17|Sharq', '3|Kuwait', 'no', 'file', 'WordPress.jpg', '2020-08-19 07:00:02', '2020-08-19 07:16:46'),
(5, 'pk', 'TESTING', 'TESTING', 'TESTING', '1988-09-27', '42101-4079068-5', 'emoji-sunglasses.png', '2020-09-30', '1|KARACHI', '1|Pakistan', 'FRANCISGILL1000@GMAIL.COM', '03108559858', 'Govt. Employee', 'SDFJLK', 'LSDKFJ', 'LKSDJ', 'SDFLKJ', '05', NULL, 'Undergraduate', 'Single', '5', 'no', 'Less than 250k', 'no', 'no', 'Salary', 'emoji-sunglasses.png', 'THIS', '1|KARACHI', '1|Pakistan', 'no', 'file', 'emoji-sunglasses.png', '2020-09-30 12:37:42', '2020-09-30 13:32:51');

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

DROP TABLE IF EXISTS `discussions`;
CREATE TABLE IF NOT EXISTS `discussions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`id`, `form_id`, `msg`, `receiver_id`, `sender_id`, `created_at`, `updated_at`) VALUES
(1, 'SA1293', 'testing', '76', '74', '2020-08-18 14:42:37', '2020-08-18 14:42:37'),
(2, 'SA1293', 'ok', '0', '76', '2020-08-18 14:44:47', '2020-08-18 14:44:47'),
(3, 'SA1293', 'wow', '0', '76', '2020-08-18 15:00:49', '2020-08-18 15:00:49'),
(4, 'SA1293', 'tow', '0', '76', '2020-08-18 15:21:36', '2020-08-18 15:21:36'),
(5, 'SA1293', 'soi', '0', '76', '2020-08-18 15:21:54', '2020-08-18 15:21:54'),
(6, 'SA1293', 's', '0', '76', '2020-08-18 15:22:31', '2020-08-18 15:22:31'),
(7, 'SA0402', 'verify CNIC, attachment not showing correctly and check email', '75', '73', '2020-08-19 07:02:47', '2020-08-19 07:02:47'),
(8, 'SA0402', 'done', '73', '75', '2020-08-19 07:04:05', '2020-08-19 07:04:05'),
(9, 'SA0402', 'attachment and address verify', '75', '74', '2020-08-19 07:06:40', '2020-08-19 07:06:40'),
(10, 'SA0402', 'changes done', '73', '75', '2020-08-19 07:08:24', '2020-08-19 07:08:24'),
(11, 'SA0402', 'check and verify', '76', '74', '2020-08-19 07:09:23', '2020-08-19 07:09:23'),
(12, 'SA0402', 'name and attachment changed', '0', '76', '2020-08-19 07:11:10', '2020-08-19 07:11:10'),
(13, 'SA0402', 'change F.M. Name', '75', '73', '2020-08-19 07:13:17', '2020-08-19 07:13:17'),
(14, 'SA0402', 'done', '73', '75', '2020-08-19 07:14:20', '2020-08-19 07:14:20'),
(15, 'SA0402', 'check and verify', '76', '74', '2020-08-19 07:16:02', '2020-08-19 07:16:02'),
(16, 'SA0402', 'done', '0', '76', '2020-08-19 07:16:46', '2020-08-19 07:16:46'),
(17, 'SA9463', 'testing', '75', '74', '2020-09-30 12:59:55', '2020-09-30 12:59:55'),
(18, 'SA9463', 'sfklj', '75', '74', '2020-09-30 13:00:40', '2020-09-30 13:00:40'),
(19, 'SA9463', 'testing', '75', '74', '2020-09-30 13:01:09', '2020-09-30 13:01:09'),
(20, 'SA0402', 'ok', '73', '75', '2020-09-30 13:02:30', '2020-09-30 13:02:30'),
(21, 'SA0402', 'tsting', '73', '75', '2020-09-30 13:03:55', '2020-09-30 13:03:55'),
(22, 'SA9463', 'wow', '74', '75', '2020-09-30 13:07:22', '2020-09-30 13:07:22'),
(23, 'SA9463', 'testing done', '74', '75', '2020-09-30 13:07:31', '2020-09-30 13:07:31'),
(24, 'SA9463', 'send to cbc', '76', '73', '2020-09-30 13:22:56', '2020-09-30 13:22:56'),
(25, 'SA9463', 'testing', '0', '76', '2020-09-30 13:28:59', '2020-09-30 13:28:59'),
(26, 'SA9463', 'testing', '0', '76', '2020-09-30 13:29:31', '2020-09-30 13:29:31'),
(27, 'SA9463', 'testing', '0', '76', '2020-09-30 13:32:51', '2020-09-30 13:32:51');

-- --------------------------------------------------------

--
-- Table structure for table `email_activities`
--

DROP TABLE IF EXISTS `email_activities`;
CREATE TABLE IF NOT EXISTS `email_activities` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `msg` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fatca_details`
--

DROP TABLE IF EXISTS `fatca_details`;
CREATE TABLE IF NOT EXISTS `fatca_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `multiple_nat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nats` longtext COLLATE utf8mb4_unicode_ci,
  `green_card` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_resi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `for_citi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `co_ma` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `co_mp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attor` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attor_addr` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trans_fund` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wf` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wform_options` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fatca_details`
--

INSERT INTO `fatca_details` (`id`, `multiple_nat`, `nats`, `green_card`, `tax_resi`, `for_citi`, `co_ma`, `co_mp`, `attor`, `attor_addr`, `trans_fund`, `wf`, `wform`, `wform_options`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, 'no', NULL, 'no', 'no', 'no', NULL, NULL, 'no', NULL, 'no', 'no', 'no_image.png', NULL, 1, '2020-08-18 14:34:52', '2020-08-18 14:34:52'),
(2, 'no', NULL, 'no', 'no', 'no', NULL, NULL, 'no', NULL, 'no', 'no', 'no_image.png', NULL, 4, '2020-08-19 07:00:02', '2020-08-19 07:00:02'),
(3, 'no', NULL, 'no', 'no', 'no', NULL, NULL, 'no', NULL, 'no', 'no', 'no_image.png', NULL, 5, '2020-09-30 12:37:42', '2020-09-30 12:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_details` longtext COLLATE utf8mb4_unicode_ci,
  `bank_details` longtext COLLATE utf8mb4_unicode_ci,
  `investment_details` longtext COLLATE utf8mb4_unicode_ci,
  `other_details` longtext COLLATE utf8mb4_unicode_ci,
  `fatca_details` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `form_id`, `customer_details`, `bank_details`, `investment_details`, `other_details`, `fatca_details`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SA0402', '[\"father_name\",\"mother_name\"]', 'null', 'null', 'null', 'null', '75', 'checked', '2020-08-19 07:13:18', '2020-08-19 07:13:18'),
(2, 'SA9463', '[\"name\"]', 'null', 'null', 'null', 'null', '75', 'checked', '2020-09-30 13:01:09', '2020-09-30 13:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `assigned_to` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `form_id`, `channel`, `status`, `customer_id`, `user_id`, `assigned_to`, `created_at`, `updated_at`) VALUES
(1, 'SA1293', 'SA', 4, 1, 75, 0, '2020-08-18 14:34:53', '2020-08-18 14:34:53'),
(2, 'SA0402', 'SA', 2, 4, 75, 74, '2020-08-19 07:00:02', '2020-08-19 07:00:02'),
(3, 'SA9463', 'SA', 4, 5, 75, 0, '2020-09-30 12:37:43', '2020-09-30 12:37:43');

-- --------------------------------------------------------

--
-- Table structure for table `investment_details`
--

DROP TABLE IF EXISTS `investment_details`;
CREATE TABLE IF NOT EXISTS `investment_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_in_words` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `front_end_load` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instrument_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_details`
--

INSERT INTO `investment_details` (`id`, `fund_name`, `amount`, `amount_in_words`, `front_end_load`, `payment_mode`, `attachment`, `bank_name`, `instrument_number`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, 'HBL Cash Fund', '5,000', 'Five Thousand only', 'N/A', 'Cheque', 'two.jpeg', '1|ALBARAKA', '858', 1, '2020-08-18 14:34:52', '2020-08-18 14:34:52'),
(2, 'HBL Cash Fund', '1,221', 'One Thousand Two Hundred Twenty One only', 'N/A', 'Cheque', 'WordPress.jpg', '1|ALBARAKA', 'TESTING PURPOSE', 4, '2020-08-19 07:00:02', '2020-08-19 07:00:02'),
(3, 'HBL Cash Fund', '5,000', 'Five Thousand only', 'N/A', 'Cheque', 'emoji-sunglasses.png', '1|ALBARAKA', '0310030230', 5, '2020-09-30 12:37:42', '2020-09-30 12:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_09_23_174642_create_bank_details_table', 1),
(2, '2019_09_23_174933_create_investment_details_table', 1),
(3, '2019_09_23_180051_create_forms_table', 1),
(4, '2019_09_25_175306_create_fields_table', 1),
(5, '2019_12_03_135243_create_fatca_details_table', 1),
(6, '2019_12_03_135519_create_customers_table', 1),
(7, '2019_12_03_170014_create_other_details_table', 2),
(8, '2019_12_03_174913_create_fatca_details_table', 3),
(9, '2020_08_19_181326_create_email_activities_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `other_details`
--

DROP TABLE IF EXISTS `other_details`;
CREATE TABLE IF NOT EXISTS `other_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asf` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dpo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_of_units` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `other_details`
--

INSERT INTO `other_details` (`id`, `asf`, `dpo`, `type_of_units`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, 'Monthly', 'Cash', 'NA', 1, '2020-08-18 14:34:52', '2020-08-18 14:34:52'),
(2, 'Quarterly', 'Reinvestment (Net of applicable taxes)', 'growth', 4, '2020-08-19 07:00:02', '2020-08-19 07:00:02'),
(3, 'Monthly', 'Cash', 'NA', 5, '2020-09-30 12:37:42', '2020-09-30 12:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `role_title`, `created_at`, `updated_at`) VALUES
(0, 'admin', 'Admin', NULL, NULL),
(1, 'user', 'Sales', NULL, NULL),
(2, 'super_admin', 'Super Admin', NULL, NULL),
(3, 'retail_admin', 'Retail Admin', NULL, NULL),
(4, 'retail_user', 'Retail User', NULL, NULL),
(5, 'cbc', 'cbc', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(57, 'francis.gill', 'master@erp.com', '$2y$10$jdDNYITPuQtAAWg46xz3Me6ZiVLoHfSBq.2AlAoQKdqx1SSlKz8sO', 'OsAZpPnla47MdNotPl40MAC7Gsj7tChqlnhfX9ocQfP8vtkxspeE0igHbIRW', '2018-09-05 12:01:58', '2018-09-05 12:01:58', 2),
(73, 'retail admin', 'retailadmin@ord.com', '$2y$10$99Y9zFpo01JWWYFcOVfHJ.LSmq3vrJw1i2r75fqE.f/1gdCZRxw2O', 'FVkaVs1J6FlP5UsYgDOu4vlfrQLESRra9LcvuUhMMotRgEcWVzsJDDwNpvya', '2019-12-03 13:06:41', '2019-12-03 13:06:41', 3),
(74, 'retail user', 'retailuser@ord.com', '$2y$10$5/CQBjfRVYVcjz6WyBhFgOOfYedPFGZWgzfYEy4SrE2/EyY9CcSma', 'Ok8cdommmDpdLNf3MmIWVFfhRPDh4MOPgJksRQh7uZ54jOmh4EhoXbLq0tYF', '2019-12-03 13:11:37', '2019-12-03 13:11:37', 4),
(75, 'sales ord', 'sales@ord.com', '$2y$10$/G9DL2/jFtYKfBZ5zbM8POYbbaJGyYdA.8VrENz.95eBeCHItC91C', 'oppMHiABsZOsreW4G1X0aNwUJiATdNIcLJukDUtbBC6inpY3SorzEO5cxuSG', '2019-12-03 13:15:23', '2019-12-03 13:15:23', 1),
(76, 'cbc user', 'cbc@ord.com', '$2y$10$IfnTU1CIqHtOQdhL.vSxf.Dm6LmU41z8QbFTjICJj4MM.0uktKf/6', '0qenUktb34ERaWHABdAJTzCqc9HYtAOYQwEElc5waMmY0gFqegao6pRqKWD1', '2019-12-06 09:50:48', '2019-12-06 09:50:48', 5);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

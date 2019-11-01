-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2019 at 01:03 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eap`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_point`
--

CREATE TABLE `access_point` (
  `ap_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `ap_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_mac_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap_mesh_role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ap_identifier` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_point`
--

INSERT INTO `access_point` (`ap_id`, `org_id`, `venue_id`, `ap_name`, `ap_description`, `ap_serial`, `ap_tags`, `ap_status`, `ap_model`, `ap_ip_address`, `ap_mac_address`, `ap_mesh_role`, `created_at`, `updated_at`, `ap_identifier`) VALUES
(1, 101, 8, '1', NULL, '3', '4', NULL, NULL, NULL, NULL, NULL, '2019-10-17 06:52:48', '2019-10-17 06:52:48', NULL),
(2, 101, 7, 'testtttt', NULL, '300', '4', NULL, NULL, NULL, NULL, NULL, '2019-10-17 06:53:49', '2019-10-17 06:53:49', NULL),
(3, 101, 9, 'Test_AP', NULL, '12345', 'sample', NULL, NULL, NULL, NULL, NULL, '2019-10-21 01:26:29', '2019-10-21 01:26:29', NULL),
(4, 2, 10, 'AP_1', NULL, '1234567', 'test', NULL, NULL, NULL, NULL, NULL, '2019-10-21 02:32:51', '2019-10-21 02:32:51', NULL),
(5, 101, 8, 'Nucli', NULL, '123456', NULL, NULL, NULL, NULL, NULL, NULL, '2019-10-30 03:45:59', '2019-10-30 03:45:59', 'MAC Address'),
(6, 101, 8, 'Nucli', NULL, 'samPle', NULL, NULL, NULL, NULL, NULL, NULL, '2019-10-30 03:46:11', '2019-10-30 03:46:11', 'Serial Number'),
(7, 102, 11, 'AP-1', NULL, 'Hello-Serial-100', NULL, NULL, NULL, NULL, NULL, NULL, '2019-10-31 02:37:48', '2019-10-31 02:37:48', 'Serial Number'),
(8, 102, 12, 'Ap-2-Gurgaon', NULL, 'aa::bb::cc', NULL, NULL, NULL, NULL, NULL, NULL, '2019-10-31 02:39:08', '2019-10-31 02:39:08', 'MAC Address'),
(9, 102, 13, 'AP-Noida', NULL, 'Noida-Extension-AP', '1', NULL, NULL, NULL, NULL, NULL, '2019-10-31 02:42:52', '2019-10-31 02:42:52', 'Serial Number');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_10_15_064539_create_organisation_table', 2),
(5, '2014_10_12_000000_create_user_table', 3),
(6, '2019_10_15_065936_create_venue_table', 4),
(7, '2014_10_12_000000_create_users_table', 5),
(8, '2019_10_17_084955_create_access_point_table', 6),
(9, '2019_10_19_082449_create_network_table', 7),
(10, '2019_10_19_082658_create_network_meta_table', 7),
(11, '2019_10_19_082921_create_network_venue_mapping_table', 7),
(12, '2019_10_19_083140_create_network_ap_mapping_table', 7),
(13, '2019_10_30_085514_add_ap_identifier_to_ap_table', 8),
(14, '2019_10_30_105313_add_vlan_to_network_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `network`
--

CREATE TABLE `network` (
  `network_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(11) NOT NULL,
  `network_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `network_vlan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `network_ap_mapping`
--

CREATE TABLE `network_ap_mapping` (
  `network_ap_id` int(10) UNSIGNED NOT NULL,
  `network_id` int(11) NOT NULL,
  `ap_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `network_meta`
--

CREATE TABLE `network_meta` (
  `network_meta_id` int(10) UNSIGNED NOT NULL,
  `network_id` int(11) NOT NULL,
  `backup_phrase` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `security_protocol` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passphrase_format` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passphrase_length` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passphrase_expiry` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `captive_portal_provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `captive_portal_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `integration_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `walled_garden` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `network_venue_mapping`
--

CREATE TABLE `network_venue_mapping` (
  `network_venue_id` int(10) UNSIGNED NOT NULL,
  `network_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE `organisation` (
  `org_id` int(10) UNSIGNED NOT NULL,
  `org_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_status` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(11) NOT NULL,
  `venue_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue_address_notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_point`
--
ALTER TABLE `access_point`
  ADD PRIMARY KEY (`ap_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `network`
--
ALTER TABLE `network`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `network_ap_mapping`
--
ALTER TABLE `network_ap_mapping`
  ADD PRIMARY KEY (`network_ap_id`);

--
-- Indexes for table `network_meta`
--
ALTER TABLE `network_meta`
  ADD PRIMARY KEY (`network_meta_id`);

--
-- Indexes for table `network_venue_mapping`
--
ALTER TABLE `network_venue_mapping`
  ADD PRIMARY KEY (`network_venue_id`);

--
-- Indexes for table `organisation`
--
ALTER TABLE `organisation`
  ADD PRIMARY KEY (`org_id`),
  ADD UNIQUE KEY `organisation_org_name_unique` (`org_name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_id`),
  ADD UNIQUE KEY `venue_venue_name_unique` (`venue_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_point`
--
ALTER TABLE `access_point`
  MODIFY `ap_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `network`
--
ALTER TABLE `network`
  MODIFY `network_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `network_ap_mapping`
--
ALTER TABLE `network_ap_mapping`
  MODIFY `network_ap_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `network_meta`
--
ALTER TABLE `network_meta`
  MODIFY `network_meta_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `network_venue_mapping`
--
ALTER TABLE `network_venue_mapping`
  MODIFY `network_venue_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `venue_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

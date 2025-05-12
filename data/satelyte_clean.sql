-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 02, 2024 at 02:43 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `satelyte`
--

-- --------------------------------------------------------

--
-- Table structure for table `absents`
--

CREATE TABLE `absents` (
  `id` bigint UNSIGNED NOT NULL,
  `absent_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `evidence_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `absent_masters`
--

CREATE TABLE `absent_masters` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quota` int NOT NULL,
  `evc` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absent_masters`
--

INSERT INTO `absent_masters` (`id`, `name`, `quota`, `evc`, `created_at`, `updated_at`) VALUES
(1, 'Sakit', 15, 1, '2024-08-28 08:10:27', '2024-08-28 08:39:55'),
(3, 'Izin tidak hadir', 3, 0, '2024-08-28 08:47:58', '2024-08-28 08:47:58');

-- --------------------------------------------------------

--
-- Table structure for table `allowances`
--

CREATE TABLE `allowances` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allowances`
--

INSERT INTO `allowances` (`id`, `name`, `amount`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'THR Imlek', 500000.00, 1, '2024-10-21 07:39:28', '2024-10-21 07:39:28'),
(3, 'THR Idul Fitri', 2500000.00, 1, '2024-10-26 20:25:28', '2024-10-26 20:25:28'),
(4, 'THR Nataru', 500000.00, 1, '2024-10-26 20:28:55', '2024-10-26 20:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `allowance_payrolls`
--

CREATE TABLE `allowance_payrolls` (
  `id` bigint UNSIGNED NOT NULL,
  `period` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allow_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `time_in` timestamp NULL DEFAULT NULL,
  `time_out` timestamp NULL DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `approved_out` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_path_out` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salaries_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_positions`
--

INSERT INTO `job_positions` (`id`, `title`, `description`, `salaries_id`, `created_at`, `updated_at`) VALUES
(2, 'Direktur', 'Memimpin semua bawahan', 1, '2024-08-26 06:40:51', '2024-08-26 06:40:51'),
(3, 'Drafter', 'Karyawan pembuat gambar teknis', 3, '2024-09-26 09:43:02', '2024-09-26 09:43:02');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_05_24_214555_create_shifts_table', 2),
(6, '2024_05_27_042056_add_shift_id_to_users_table', 3),
(9, '2024_05_28_062938_create_attendances_table', 4),
(12, '2024_08_18_064540_create_salaries_table', 5),
(13, '2024_08_18_064554_create_job_positions_table', 5),
(14, '2024_08_28_134531_create_absent_masters_table', 6),
(19, '2024_08_28_211016_create_absents_table', 7),
(20, '2024_09_26_162411_add_position_id_to_users_table', 8),
(21, '2024_09_26_182014_create_reimbursments_table', 9),
(22, '2024_09_27_215117_create_overtimes_table', 10),
(23, '2024_10_09_081915_create_settings_table', 11),
(32, '2024_10_17_010200_create_projects_table', 12),
(33, '2024_10_17_010342_create_project_workers_table', 12),
(34, '2024_10_20_024100_create_project_evidences_table', 13),
(36, '2024_10_21_141758_create_allowances_table', 14),
(37, '2024_10_26_022508_create_allowance_payrolls_table', 15),
(38, '2024_10_27_072600_add_role_to_users_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `overtimes`
--

CREATE TABLE `overtimes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `foreman_id` bigint UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0: Terdaftar, 1: Terkunci, 2: Selesai, 3: Tunggu Validasi, 4: Disetujui, 5: Ditolak',
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_evidences`
--

CREATE TABLE `project_evidences` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_workers`
--

CREATE TABLE `project_workers` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `worker_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `working_days` int NOT NULL DEFAULT '0',
  `salary_day` double(10,2) NOT NULL,
  `total_salary` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reimbursments`
--

CREATE TABLE `reimbursments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` double(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reimbursement_date` date NOT NULL,
  `evidence_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','validated','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, 'Gaji 50rb', 50000.00, '2024-08-21 05:29:36', '2024-09-26 09:42:31'),
(3, 'Gaji 30rb', 30000.00, '2024-09-26 09:42:45', '2024-09-26 09:42:45');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `placeholder`, `created_at`, `updated_at`) VALUES
(1, 'tarif_lembur', '7250', NULL, '2024-10-09 01:29:13', '2024-10-21 08:03:29'),
(2, 'nama_ttd_slip_gaji', 'Yeremia Bayu', 'Isikan nama orang yang menandatangani slip gaji!', '2024-10-09 01:33:12', '2024-10-21 08:03:29'),
(3, 'posisi_ttd_slip_gaji', 'Ketua Umum PSI', 'Isikan judul posisi penandatangan slip gaji!', '2024-10-09 01:34:14', '2024-10-21 08:03:29');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `late_tolerance` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `start_time`, `end_time`, `late_tolerance`, `created_at`, `updated_at`) VALUES
(1, 'Shift Pagi', '08:30:00', '17:00:00', 5, '2024-05-26 00:39:03', '2024-05-26 00:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shift_id` bigint UNSIGNED DEFAULT NULL,
  `position_id` bigint UNSIGNED DEFAULT NULL,
  `role` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`, `shift_id`, `position_id`, `role`) VALUES
(1, 'Fransiskus Adrian Krisnata', 'fransiskus@adriankrisnata.com', '$2y$12$6SNZXR80VKVlN/9mRbcDBOAJxafSTSV6vzyCjbPXF4k6iBtW5azZO', 1, '2024-05-24 14:35:34', '2024-09-26 09:44:02', 1, 2, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absents`
--
ALTER TABLE `absents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absents_absent_id_foreign` (`absent_id`),
  ADD KEY `absents_created_by_foreign` (`created_by`),
  ADD KEY `absents_validated_by_foreign` (`validated_by`);

--
-- Indexes for table `absent_masters`
--
ALTER TABLE `absent_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowances`
--
ALTER TABLE `allowances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowance_payrolls`
--
ALTER TABLE `allowance_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allowance_payrolls_allow_id_foreign` (`allow_id`),
  ADD KEY `allowance_payrolls_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_positions_salaries_id_foreign` (`salaries_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overtimes`
--
ALTER TABLE `overtimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `overtimes_user_id_foreign` (`user_id`),
  ADD KEY `overtimes_validated_by_foreign` (`validated_by`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_foreman_id_foreign` (`foreman_id`),
  ADD KEY `projects_validated_by_foreign` (`validated_by`);

--
-- Indexes for table `project_evidences`
--
ALTER TABLE `project_evidences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_evidences_project_id_foreign` (`project_id`);

--
-- Indexes for table `project_workers`
--
ALTER TABLE `project_workers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_workers_project_id_foreign` (`project_id`);

--
-- Indexes for table `reimbursments`
--
ALTER TABLE `reimbursments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reimbursments_user_id_foreign` (`user_id`),
  ADD KEY `reimbursments_validated_by_foreign` (`validated_by`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_shift_id_foreign` (`shift_id`),
  ADD KEY `users_position_id_foreign` (`position_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absents`
--
ALTER TABLE `absents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `absent_masters`
--
ALTER TABLE `absent_masters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `allowances`
--
ALTER TABLE `allowances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `allowance_payrolls`
--
ALTER TABLE `allowance_payrolls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `overtimes`
--
ALTER TABLE `overtimes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_evidences`
--
ALTER TABLE `project_evidences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_workers`
--
ALTER TABLE `project_workers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reimbursments`
--
ALTER TABLE `reimbursments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absents`
--
ALTER TABLE `absents`
  ADD CONSTRAINT `absents_absent_id_foreign` FOREIGN KEY (`absent_id`) REFERENCES `absent_masters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absents_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `allowance_payrolls`
--
ALTER TABLE `allowance_payrolls`
  ADD CONSTRAINT `allowance_payrolls_allow_id_foreign` FOREIGN KEY (`allow_id`) REFERENCES `allowances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allowance_payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `job_positions_salaries_id_foreign` FOREIGN KEY (`salaries_id`) REFERENCES `salaries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `overtimes`
--
ALTER TABLE `overtimes`
  ADD CONSTRAINT `overtimes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `overtimes_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_foreman_id_foreign` FOREIGN KEY (`foreman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_evidences`
--
ALTER TABLE `project_evidences`
  ADD CONSTRAINT `project_evidences_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_workers`
--
ALTER TABLE `project_workers`
  ADD CONSTRAINT `project_workers_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reimbursments`
--
ALTER TABLE `reimbursments`
  ADD CONSTRAINT `reimbursments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reimbursments_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

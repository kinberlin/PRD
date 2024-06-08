-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: host.docker.internal:3306
-- Generation Time: Jun 08, 2024 at 08:47 AM
-- Server version: 8.0.36-2ubuntu3
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prd`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorisation_pilote`
--

CREATE TABLE `authorisation_pilote` (
  `id` int NOT NULL,
  `user` int NOT NULL,
  `process` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `interim` tinyint DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `authorisation_pilote`
--

INSERT INTO `authorisation_pilote` (`id`, `user`, `process`, `created_at`, `deleted_at`, `interim`, `updated_at`) VALUES
(3, 52, 1, '2024-06-07 11:15:08', NULL, 0, '2024-06-07 11:15:08');

-- --------------------------------------------------------

--
-- Table structure for table `authorisation_rq`
--

CREATE TABLE `authorisation_rq` (
  `id` int NOT NULL,
  `user` int NOT NULL,
  `enterprise` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `interim` tinyint(1) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `authorisation_rq`
--

INSERT INTO `authorisation_rq` (`id`, `user`, `enterprise`, `created_at`, `deleted_at`, `interim`, `updated_at`) VALUES
(2, 51, 1, '2024-05-29 16:00:48', NULL, 0, '2024-05-30 10:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `enterprise` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `created_at`, `deleted_at`, `enterprise`) VALUES
(1, 'Direction Qualité Hygiène Sécurité Environnement (DQHSE)', '2024-05-02 10:40:05', NULL, 1),
(2, 'Administration (DA)', '2024-05-24 11:41:10', NULL, 1),
(3, 'Direction Audit et Contrôle Interne (DACI)', '2024-05-24 11:41:10', NULL, 1),
(4, 'Direction Achat et Logistique (DAL)', '2024-05-24 11:41:10', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dysfunction`
--

CREATE TABLE `dysfunction` (
  `id` int NOT NULL,
  `enterprise` varchar(100) NOT NULL,
  `site` varchar(100) NOT NULL,
  `emp_signaling` varchar(100) NOT NULL,
  `emp_matricule` varchar(100) NOT NULL,
  `emp_email` varchar(100) DEFAULT NULL,
  `description` text,
  `concern_processes` json DEFAULT NULL,
  `impact_processes` json DEFAULT NULL,
  `gravity` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `probability` int DEFAULT NULL,
  `corrective_acts` json DEFAULT NULL,
  `invitations` json DEFAULT NULL,
  `status` int DEFAULT '1',
  `progression` int DEFAULT '0',
  `pj` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `occur_date` date NOT NULL,
  `cause` varchar(100) DEFAULT NULL,
  `rej_reasons` varchar(100) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dysfunction`
--

INSERT INTO `dysfunction` (`id`, `enterprise`, `site`, `emp_signaling`, `emp_matricule`, `emp_email`, `description`, `concern_processes`, `impact_processes`, `gravity`, `probability`, `corrective_acts`, `invitations`, `status`, `progression`, `pj`, `created_at`, `deleted_at`, `occur_date`, `cause`, `rej_reasons`, `code`) VALUES
(1, 'Panzani', 'Usine Pâtes', 'Test First', 'PZN0130', 't@t.t', '...', '[\"Manager l\'Amélioration Continu (MAC)\"]', '[\"Piloter l\'Entreprise (PEN)\", \"Produire les Farines et Semoules (PES)\"]', 'Négligeable', 1, NULL, NULL, 2, 0, '[\"http://localhost:8001/uploads/dysfonction/1715029881_MEETING RECAP cadyst.docx\", \"http://localhost:8001/uploads/dysfonction/1715029881_ctem-flyers-lait_sod-longrich.jpg\"]', '2024-05-06 21:11:21', NULL, '2024-04-30', 'Aucun Responsable Identifier', NULL, NULL),
(2, 'La Pasta', 'Usine Minoterie Semoulerie (UMS), Carrefour Mitzig', 'Test First', 'PZN0001', 't@t.t', 'Test reunion', '[\"Produire les Pâtes alimentaires\"]', '[\"Manager l\'Amélioration Continu\", \"Produire les Farines et Semoules\"]', 'Négligeable', 2, NULL, NULL, 2, 0, '[\"http://localhost:8001/uploads/dysfonction/1716032993_Configuring VLANs Instructions.pdf\"]', '2024-05-18 11:49:53', NULL, '2024-05-17', 'Aucun Responsable Identifier', NULL, NULL),
(4, 'Panzani', 'Usine Pâtes, Bassa', 'Test First', 'YU14AS', 't@t.t', 'popups', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '[]', '2024-05-23 11:41:43', NULL, '2024-05-16', NULL, NULL, 'D20245PZN4');

-- --------------------------------------------------------

--
-- Table structure for table `enterprise`
--

CREATE TABLE `enterprise` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `surfix` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `logo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enterprise`
--

INSERT INTO `enterprise` (`id`, `name`, `surfix`, `deleted_at`, `created_at`, `logo`) VALUES
(1, 'Panzani', 'PZN', NULL, '2024-05-02 09:57:06', NULL),
(2, 'La Pasta', 'LPT', NULL, '2024-05-04 06:01:10', NULL),
(3, 'Société Agro-alimentaire Equatoriale', 'SAE', NULL, '2024-05-24 11:55:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gravity`
--

CREATE TABLE `gravity` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `note` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gravity`
--

INSERT INTO `gravity` (`id`, `name`, `created_at`, `deleted_at`, `note`) VALUES
(1, 'Négligeable', '2024-05-07 15:13:44', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `invitation`
--

CREATE TABLE `invitation` (
  `id` int NOT NULL,
  `dysfonction` int NOT NULL,
  `object` varchar(150) NOT NULL,
  `dates` timestamp NOT NULL,
  `place` text,
  `link` text,
  `description` text,
  `rq` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `motif` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `internal_invites` json DEFAULT NULL,
  `external_invites` json DEFAULT NULL,
  `begin` varchar(10) NOT NULL,
  `end` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invitation`
--

INSERT INTO `invitation` (`id`, `dysfonction`, `object`, `dates`, `place`, `link`, `description`, `rq`, `created_at`, `deleted_at`, `motif`, `internal_invites`, `external_invites`, `begin`, `end`) VALUES
(1, 1, 'sss', '2024-05-17 11:00:00', 'lieu 1', 'http://localhost:8001/rq/plans', 'vvvv', 'Test RQ Mtricule 35258', '2024-05-14 10:32:32', '2024-05-14 10:32:32', 'Résolution de Dysfonctionnement', '[]', '[\"TT@rr.ss\"]', '12:00', '15:00'),
(2, 1, 'sss', '2024-05-17 11:00:00', 'lieu 1', NULL, 'vvvv', 'Test RQ Matricule 35258', '2024-05-16 19:35:40', '2024-05-16 19:35:40', 'Autres', '[{\"email\": \"paulnintcheu6@gmail.fr\", \"reasons\": \"Je confirme ma présence\", \"decision\": \"Confirmer\", \"matricule\": \"PZN0131\", \"created_at\": \"2024-05-30T11:31:29.416503Z\", \"deleted_at\": null, \"department\": 1, \"enterprise\": 1}, {\"email\": \"florentinbertrandn@gmail.com\", \"reasons\": null, \"decision\": \"En attente de Validation\", \"matricule\": \"LPT0143\", \"created_at\": \"2024-05-30T11:31:29.417612Z\", \"deleted_at\": null, \"department\": 2, \"enterprise\": 2}]', '[\"andersont@gmail.com\", \"sups@ss.ss\"]', '12:00', '15:00'),
(4, 1, 'test new', '2024-05-31 23:00:00', 'Saint Tropez', NULL, 'test new', 'Test RQ Matricule 35258', '2024-05-17 09:44:38', '2024-05-17 09:44:38', 'Evaluation de Dysfonctionnement', '[]', '[\"googs@google.com\"]', '08:00', '10:00'),
(6, 1, 'test reunion', '2024-05-21 06:10:00', 'Saint Tropez', NULL, 'test', 'Test RQ Matricule 35258', '2024-05-18 14:04:24', '2024-05-18 14:04:24', 'Autres', '[]', '[\"test@test.com\"]', '08:15', '12:00');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int UNSIGNED NOT NULL,
  `dysfunction` int DEFAULT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` int NOT NULL,
  `target` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_05_10_084657_create_tasks_table', 1),
(3, '2024_05_10_084717_create_links_table', 1),
(4, '2024_05_10_103224_add_sortorder_to_tasks_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `processes`
--

CREATE TABLE `processes` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `surfix` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `processes`
--

INSERT INTO `processes` (`id`, `name`, `surfix`, `created_at`, `deleted_at`) VALUES
(1, 'Piloter l\'Entreprise', 'PEN', '2024-05-04 04:34:10', '2024-05-04 04:34:10'),
(2, 'Manager l\'Amélioration Continu', 'MAC', '2024-05-08 09:24:24', '2024-05-08 09:24:24'),
(3, 'Produire les Farines et Semoules', 'PES', '2024-05-08 09:25:06', '2024-05-08 09:25:06'),
(4, 'Produire les Pâtes alimentaires', 'PPA', '2024-05-23 08:36:40', '2024-05-23 08:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL,
  `deleted_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `deleted_at`) VALUES
(1, 'admin', '2024-05-23 18:59:07', '2024-05-23 18:59:07'),
(2, 'Emlpoyé', '2024-05-23 18:59:07', '2024-05-23 18:59:07'),
(3, 'Responsable Qualité', '2024-05-23 18:59:35', '2024-05-23 18:59:35'),
(4, 'Pilote', '2024-05-23 18:59:35', '2024-05-23 18:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE `site` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `enterprise` int NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `name`, `enterprise`, `location`, `created_at`, `deleted_at`) VALUES
(1, 'Usine Pâtes', 2, 'Bassa', '2024-05-02 11:32:22', NULL),
(2, 'Magasin Pâtes', 1, 'Bassa', '2024-05-02 11:52:17', NULL),
(3, 'Magasin', 1, 'Ndogsimbi', '2024-05-03 15:40:27', NULL),
(4, 'Magasin Grand Hangar', 1, 'Bonabéri', '2024-05-03 15:47:14', NULL),
(5, 'Usine Minoterie Semoulerie (UMS)', 2, 'Carrefour Mitzig', '2024-05-18 11:22:36', NULL),
(6, 'Usine Minoteries', 2, 'Kribi', '2024-05-28 14:32:03', NULL),
(7, 'te', 2, 'sa', '2024-06-06 09:16:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `step` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`, `step`) VALUES
(1, 'Initialisation...', 1),
(2, 'Dysfonctionnement Identifier.', 2),
(3, 'Rejeter', NULL),
(4, 'En cours de traitement', NULL),
(5, 'En cours d\'évaluation', NULL),
(6, 'Terminé', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int UNSIGNED NOT NULL,
  `dysfunction` int DEFAULT NULL,
  `text` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL,
  `progress` double(8,2) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `parent` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sortorder` int NOT NULL DEFAULT '0',
  `unscheduled` tinyint(1) DEFAULT '0',
  `process` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `open` tinyint(1) DEFAULT '1',
  `proof` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `view_by` json DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `dysfunction`, `text`, `duration`, `progress`, `start_date`, `parent`, `created_at`, `updated_at`, `sortorder`, `unscheduled`, `process`, `description`, `open`, `proof`, `view_by`, `created_by`) VALUES
(9, 1, 'Dys 1', 6, 0.83, '2024-05-07 00:00:00', 0, '2024-05-10 09:43:43', '2024-05-21 14:50:14', 28, 0, '1', NULL, 1, NULL, NULL, NULL),
(10, 1, 'Nettoyage', 3, 0.50, '2024-05-08 00:00:00', 9, '2024-05-10 09:49:44', '2024-05-21 14:52:52', 5, 0, '2', 'Lundi Matin', 1, NULL, NULL, NULL),
(29, 1, 'Nouvelle tâche', 6, 1.00, '2024-05-07 00:00:00', 9, '2024-05-21 13:39:03', '2024-05-21 14:50:12', 9, 0, '1', NULL, 1, 'http://localhost:8001/uploads/tasks/1716306612_gantt.pdf', NULL, 'Demo User'),
(31, 2, 'Dysfonctionnement', 2, 0.00, '2024-05-22 00:00:00', 0, '2024-05-23 10:13:07', '2024-05-23 10:16:43', 0, 0, '4', NULL, 1, NULL, NULL, 'Demo User'),
(32, 2, 'Nouvelle tâche', 2, 0.41, '2024-05-22 00:00:00', 31, '2024-05-23 10:16:23', '2024-05-23 10:16:34', 29, 0, '1', 't2', 0, NULL, NULL, 'Demo User');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `matricule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enterprise` int NOT NULL,
  `department` int DEFAULT NULL,
  `role` int DEFAULT '2',
  `poste` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `deleted_at`, `phone`, `image`, `matricule`, `enterprise`, `department`, `role`, `poste`) VALUES
(1, 'Ardo', 'Alkassoum', 'tdrytan@yahoo.com', NULL, '$2y$12$WoODfnA58I2fvkMLl6mQi..W7wK6jAB.rEPngOquIJM9TUcMnHBQS', NULL, NULL, NULL, '673955909', NULL, 'PZN0001', 1, NULL, 1, 'DQ'),
(51, 'HINO', 'BRUNO', 'andersontchamba@gmail.com', NULL, '$2y$12$WoODfnA58I2fvkMLl6mQi..W7wK6jAB.rEPngOquIJM9TUcMnHBQS', NULL, NULL, NULL, '673955909', NULL, 'PZN0130', 1, 1, 2, 'OPERATEUR MOULE'),
(52, 'NINTCHEU', 'PAUL', 'paulnintcheu6@gmail.fr', NULL, '$2y$12$UrNq66gqknGh9HxFIDO/5.wRGn6uLJz6ooR2rBJ4X9LLyr.J4kDwC', NULL, NULL, NULL, '673955909', NULL, 'PZN0131', 1, 1, 2, 'SUPERVISEUR SILO'),
(53, 'TANZI TCHAPTCHET', 'ARMAND BOZARD', 'atanzib@yahoo.fr', NULL, '$2y$12$cHGRcBr9f.4XoYQugGvvZO/HYoJm1DzbF6yJ99SZuZ6kK5KDwVWjK', NULL, NULL, NULL, '673955909', NULL, 'PZN0137', 1, 2, 2, 'SUPERVISEUR MAGASIN'),
(54, 'NGOUNA', 'FLORENTIN B.', 'florentinbertrandn@gmail.com', NULL, '$2y$12$jt6qkRMnjLUSVCD45MhWt.w9V5ML9zOvzO617UvzCeWuTDrr5OxNO', NULL, NULL, NULL, '673955909', NULL, 'LPT0143', 2, 2, 2, 'MAGASINIER MPC & CARBURANT'),
(55, 'TJOMBE', 'HENRI MATHURIN', 'henrimathurintjombe@gmail.com', NULL, '$2y$12$q5chtXV3cYEn4ZzNuCxkaeCHgjKYLBDze0EWW5hq6iyiogHHZOvrO', NULL, NULL, NULL, '673955909', NULL, 'LPT0156', 2, 3, 2, 'Chef d\'Equipe Hygiène'),
(56, 'KESSENG', 'PIERRE', 'kinberlintchamba2003@gmail.com', NULL, '$2y$12$Zim8EcNlgDJc1mwe9Qeom.LvD7/Pgi8hpqQ.l9z806U9plY8fMHym', NULL, NULL, NULL, '673955909', NULL, 'LPT0174', 2, 3, 2, 'CARISTE'),
(57, 'THOMO', 'LYSETTE', 'thlysette@yahoo.fr', NULL, '$2y$12$7hByixry7kMFfNoWLX6q4.qZXTPGA.hmIS5QPZOroZYavhX.kbUy6', NULL, NULL, NULL, '673955909', NULL, 'SAE0186', 3, 4, 2, 'RESP. REMUNERATION/P- SOCIAL E'),
(58, 'YAKANA', 'LUC', 'drystantchamba@outlook.com', NULL, '$2y$12$LT8E2h8VLwL/7kI8Juv4tO66IjXUkTSrmQYS0lv4xhbj22htQaFxC', NULL, NULL, NULL, '673955909', NULL, 'SAE0187', 3, 4, 2, 'EMBALLEUR'),
(59, 'NZOGO', 'AUGUSTIN', 'anzogo@yahoo.com', NULL, '$2y$12$UBp.b6RPdUkjU2GkCOhdT.dwXaWoMR0QZghB3eiD0s7t9wokBdI2G', NULL, NULL, NULL, '673955909', NULL, 'SAE0191', 3, 1, 2, 'MAGASINIER'),
(60, 'MOTASSI DUME', 'BRUNOE', 'Bruno.motassidume@yahoo.com', NULL, '$2y$12$CVdoATZhDX9uURyA08tV0ON1Qwjqy4IUlRxYtjaC6W3M0OJ0dBhvu', NULL, NULL, NULL, '673955909', NULL, 'PZN0192', 1, 2, 2, 'CHEF FABRICATION'),
(61, 'SATEKE', 'FRANCIS', 'fsateke@gmail.com', NULL, '$2y$12$i04SUswCJ6kIS/ULeRhGOeb8dB.sFc93vSxW7/U5aENqjMApj5UR2', NULL, NULL, NULL, '673955909', NULL, 'LPT0193', 2, 3, 2, 'SUPERVISEUR CONDITIONNEMENT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorisation_pilote`
--
ALTER TABLE `authorisation_pilote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processus` (`process`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `authorisation_rq`
--
ALTER TABLE `authorisation_rq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `enterprise` (`enterprise`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enterprise` (`enterprise`);

--
-- Indexes for table `dysfunction`
--
ALTER TABLE `dysfunction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `enterprise`
--
ALTER TABLE `enterprise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `gravity`
--
ALTER TABLE `gravity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enterprise` (`enterprise`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD KEY `department` (`department`),
  ADD KEY `enterprise` (`enterprise`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authorisation_pilote`
--
ALTER TABLE `authorisation_pilote`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `authorisation_rq`
--
ALTER TABLE `authorisation_rq`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dysfunction`
--
ALTER TABLE `dysfunction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enterprise`
--
ALTER TABLE `enterprise`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gravity`
--
ALTER TABLE `gravity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `processes`
--
ALTER TABLE `processes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authorisation_pilote`
--
ALTER TABLE `authorisation_pilote`
  ADD CONSTRAINT `authorisation_pilote_ibfk_1` FOREIGN KEY (`process`) REFERENCES `processes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `authorisation_pilote_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `authorisation_rq`
--
ALTER TABLE `authorisation_rq`
  ADD CONSTRAINT `authorisation_rq_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `authorisation_rq_ibfk_2` FOREIGN KEY (`enterprise`) REFERENCES `enterprise` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `site`
--
ALTER TABLE `site`
  ADD CONSTRAINT `site_ibfk_1` FOREIGN KEY (`enterprise`) REFERENCES `enterprise` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`enterprise`) REFERENCES `enterprise` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

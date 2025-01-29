-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 29 jan. 2025 à 21:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `buztirut_harmora`
--

-- --------------------------------------------------------

--
-- Structure de la table `achats`
--

CREATE TABLE `achats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_achat` varchar(255) NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `status_payement` varchar(255) NOT NULL DEFAULT 'unpaid',
  `tva` decimal(5,2) DEFAULT 20.00,
  `facture` varchar(255) DEFAULT NULL,
  `date_paiement` date DEFAULT NULL,
  `date_limit` date DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `date_achat` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `montant_ht` decimal(15,2) DEFAULT NULL,
  `bon_achat` varchar(255) DEFAULT NULL,
  `montant_restant` decimal(15,2) DEFAULT NULL,
  `montant_payée` decimal(15,2) DEFAULT NULL,
  `devis` varchar(255) DEFAULT NULL,
  `payment_type` enum('Virement','Chèque','Espèce') NOT NULL DEFAULT 'Virement',
  `marge` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `achats`
--

INSERT INTO `achats` (`id`, `fournisseur_id`, `entreprise_id`, `type_achat`, `montant`, `status_payement`, `tva`, `facture`, `date_paiement`, `date_limit`, `reference`, `date_achat`, `created_at`, `updated_at`, `montant_ht`, `bon_achat`, `montant_restant`, `montant_payée`, `devis`, `payment_type`, `marge`) VALUES
(104, 48, 19, 'Matériel/Produits', 31191.60, 'unpaid', 20.00, 'achat/factures/Facture_Achat_00000001.jpg', NULL, NULL, 'Achat_00000001', '2025-01-29', '2025-01-29 19:12:15', '2025-01-29 19:13:47', 25993.00, NULL, 28072.60, 3119.00, 'achat/devis/Hsn619YbMlqErlnfyiUqHD5QroYrV9GZkDlW0fAX.jpg', 'Virement', 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `achat_product`
--

CREATE TABLE `achat_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `achat_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `depot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `achat_product`
--

INSERT INTO `achat_product` (`id`, `achat_id`, `product_id`, `depot_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(72, 104, 66, 10, 100, 109.93, '2025-01-29 19:12:15', '2025-01-29 19:12:15'),
(73, 104, 73, 13, 100, 150.00, '2025-01-29 19:12:16', '2025-01-29 19:12:16');

-- --------------------------------------------------------

--
-- Structure de la table `bon_commande_product`
--

CREATE TABLE `bon_commande_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bon_de_commande_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bon_commande_product`
--

INSERT INTO `bon_commande_product` (`id`, `bon_de_commande_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(32, 18, 64, 100, 120.00, '2025-01-29 18:46:02', '2025-01-29 18:46:02'),
(33, 18, 67, 100, 40.00, '2025-01-29 18:46:02', '2025-01-29 18:46:02'),
(34, 19, 66, 100, 109.93, '2025-01-29 19:11:00', '2025-01-29 19:11:00'),
(35, 19, 73, 100, 150.00, '2025-01-29 19:11:00', '2025-01-29 19:11:00');

-- --------------------------------------------------------

--
-- Structure de la table `bon_de_commande`
--

CREATE TABLE `bon_de_commande` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_achat` varchar(255) NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  `montant_ht` decimal(15,2) DEFAULT NULL,
  `tva` enum('0','7','10','14','16','20') NOT NULL DEFAULT '0',
  `bon` varchar(255) DEFAULT NULL,
  `status` enum('validated','pending','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `devis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bon_de_commande`
--

INSERT INTO `bon_de_commande` (`id`, `fournisseur_id`, `entreprise_id`, `type_achat`, `montant`, `reference`, `date_commande`, `montant_ht`, `tva`, `bon`, `status`, `created_at`, `updated_at`, `devis`) VALUES
(18, 48, 19, 'Matériel/Produits', 19200.00, 'BonCmd_00000001', '2025-01-29', 16000.00, '20', NULL, 'pending', '2025-01-29 18:46:02', '2025-01-29 18:46:02', NULL),
(19, 48, 19, 'Matériel/Produits', 31191.60, 'BonCmd_00000002', '2025-01-29', 25993.00, '20', NULL, 'validated', '2025-01-29 19:10:59', '2025-01-29 19:12:15', 'achat/devis/Hsn619YbMlqErlnfyiUqHD5QroYrV9GZkDlW0fAX.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `bon_livraisions`
--

CREATE TABLE `bon_livraisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_num` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bon_livraisions`
--

INSERT INTO `bon_livraisions` (`id`, `reference_num`, `client_id`, `user_id`, `invoice_id`, `entreprise_id`, `title`, `description`, `start_date`, `due_date`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(59, NULL, 88, 1, 135, 19, 'v1', NULL, '2025-01-29', '2025-01-29', 12000.00, 'total', '2025-01-29 01:06:12', '2025-01-29 01:06:12'),
(60, NULL, 88, 1, 137, 19, 'vente3', NULL, '2025-01-29', '2025-01-29', 10500.00, 'partial', '2025-01-29 19:06:29', '2025-01-29 19:06:29'),
(61, NULL, 88, 1, 137, 19, 'vente3', NULL, '2025-01-29', '2025-01-29', 9500.00, 'partial', '2025-01-29 19:06:56', '2025-01-29 19:06:56');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `ICE` bigint(20) UNSIGNED NOT NULL,
  `country_code` varchar(28) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `doj` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `lang` varchar(28) NOT NULL DEFAULT 'en',
  `remember_token` text DEFAULT NULL,
  `email_verification_mail_sent` tinyint(4) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `acct_create_mail_sent` tinyint(4) NOT NULL DEFAULT 1,
  `internal_purpose` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `denomenation` varchar(255) DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `IF` bigint(20) UNSIGNED DEFAULT NULL,
  `RC` bigint(20) UNSIGNED DEFAULT NULL,
  `tva` int(11) DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `first_name`, `last_name`, `email`, `ICE`, `country_code`, `phone`, `dob`, `doj`, `address`, `city`, `state`, `country`, `zip`, `photo`, `status`, `lang`, `remember_token`, `email_verification_mail_sent`, `email_verified_at`, `acct_create_mail_sent`, `internal_purpose`, `created_at`, `updated_at`, `denomenation`, `entreprise_id`, `IF`, `RC`, `tva`) VALUES
(88, 'azmi', 'zakaria', 'zakaria@gmail.com', 1234, '212', '666666666', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'photos/no-image.jpg', 0, 'en', NULL, NULL, NULL, 0, 0, '2024-11-13 18:16:23', '2024-11-13 18:16:23', 'A.Z.A', 19, NULL, 1563, 14),
(90, 'ZAKARIA', NULL, 'tst1@gmail.com', 152223, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'photos/no-image.jpg', 0, 'en', NULL, NULL, NULL, 0, 0, '2024-11-13 20:11:13', '2025-01-28 17:42:26', 'hermo', 19, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `client_meeting`
--

CREATE TABLE `client_meeting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meeting_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client_notifications`
--

CREATE TABLE `client_notifications` (
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `notification_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depots`
--

CREATE TABLE `depots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `depots`
--

INSERT INTO `depots` (`id`, `name`, `address`, `city`, `country`, `entreprise_id`, `created_at`, `updated_at`) VALUES
(9, 'Dépôt Marrakech', 'Quartier Industriel Sidi Ghanem', 'Marrakech', 'Maroc', 19, '2025-01-28 23:07:26', '2025-01-28 23:07:26'),
(10, 'Dépôt Rabat', 'Avenue Mohamed V, Hay Riad', 'Rabat', 'Maroc', 19, '2025-01-28 23:07:26', '2025-01-28 23:07:26'),
(11, 'Dépôt Tanger', 'Zone Industrielle Gzenaya', 'Tanger', 'Maroc', 19, '2025-01-28 23:07:26', '2025-01-28 23:07:26'),
(12, 'Dépôt Fès', 'Rue Talaa Sghira, Centre-Ville', 'Fès', 'Maroc', 19, '2025-01-28 23:07:26', '2025-01-28 23:07:26'),
(13, 'harmoservice', 'Technopark', 'casablanca', 'Morroco', 19, '2025-01-28 23:34:21', '2025-01-28 23:34:21');

-- --------------------------------------------------------

--
-- Structure de la table `depot_product`
--

CREATE TABLE `depot_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `depot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `depot_product`
--

INSERT INTO `depot_product` (`id`, `depot_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(19, 13, 64, 0, '2025-01-29 00:18:38', '2025-01-29 19:05:39'),
(20, 13, 65, 1000, '2025-01-29 00:18:39', '2025-01-29 00:44:00'),
(21, 13, 66, 200, '2025-01-29 00:18:39', '2025-01-29 19:05:38'),
(22, 13, 67, 600, '2025-01-29 00:18:40', '2025-01-29 00:44:01'),
(23, 13, 68, 200, '2025-01-29 00:18:40', '2025-01-29 00:44:01'),
(24, 10, 64, 200, '2025-01-29 00:45:12', '2025-01-29 00:45:12'),
(25, 10, 65, 500, '2025-01-29 00:45:13', '2025-01-29 00:45:13'),
(26, 10, 66, 200, '2025-01-29 00:45:13', '2025-01-29 19:12:15'),
(27, 10, 67, 300, '2025-01-29 00:45:13', '2025-01-29 00:45:13'),
(28, 10, 68, 100, '2025-01-29 00:45:13', '2025-01-29 00:45:13'),
(29, 13, 73, 100, '2025-01-29 19:12:16', '2025-01-29 19:12:16');

-- --------------------------------------------------------

--
-- Structure de la table `devises`
--

CREATE TABLE `devises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_num` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `devises`
--

INSERT INTO `devises` (`id`, `reference_num`, `client_id`, `user_id`, `entreprise_id`, `title`, `description`, `start_date`, `due_date`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(61, NULL, 88, 1, 19, 'v1', NULL, '2024-09-22', '2025-01-29', 12000.00, 'validated', '2025-01-29 01:00:32', '2025-01-29 01:05:28'),
(62, NULL, 88, 1, 19, 'vente2', NULL, '2025-01-28', '2025-01-29', 27500.00, 'validated', '2025-01-29 11:37:18', '2025-01-29 11:38:17'),
(63, NULL, 88, 1, 19, 'vente2', NULL, '2025-01-29', '2025-01-29', 20000.00, 'validated', '2025-01-29 19:03:28', '2025-01-29 19:05:38');

-- --------------------------------------------------------

--
-- Structure de la table `disponibilities`
--

CREATE TABLE `disponibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `start_date_time` timestamp NULL DEFAULT NULL,
  `end_date_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `facture` varchar(255) DEFAULT NULL,
  `devis` varchar(255) DEFAULT NULL,
  `bon_livraision` varchar(255) DEFAULT NULL,
  `bon_commande` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `remaining_amount` decimal(15,2) DEFAULT 0.00,
  `from_to` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `origin` enum('commande','achat') NOT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `reference`, `facture`, `devis`, `bon_livraision`, `bon_commande`, `description`, `type`, `user`, `total_amount`, `paid_amount`, `remaining_amount`, `from_to`, `created_at`, `updated_at`, `origin`, `entreprise_id`) VALUES
(239, '61-v1', NULL, 'devis/devis_61_1738116033.pdf', NULL, NULL, NULL, 'devis', 'ZAKARIA a', 12000.00, NULL, 0.00, 'client : 61-azmizakaria', '2025-01-29 01:00:34', '2025-01-29 01:00:34', 'commande', 19),
(240, '135-v1', 'facture/updated_facture_135_1738116328.pdf', NULL, NULL, NULL, NULL, 'facture', 'ZAKARIA a', 12000.00, NULL, 0.00, 'client : 135-azmizakaria', '2025-01-29 01:05:28', '2025-01-29 01:05:28', 'commande', 19),
(241, '59-v1', NULL, NULL, 'bon_livraision/bon_livraision_59_1738116372.pdf', NULL, NULL, 'bon_livraision', 'ZAKARIA a', 12000.00, NULL, 0.00, 'client : 88-azmi zakaria', '2025-01-29 01:06:13', '2025-01-29 01:06:13', 'commande', 19),
(242, '62-vente2', NULL, 'devis/devis_62_1738154243.pdf', NULL, NULL, NULL, 'devis', 'ZAKARIA a', 27500.00, NULL, 0.00, 'client : 62-azmizakaria', '2025-01-29 11:37:25', '2025-01-29 11:37:25', 'commande', 19),
(243, '136-vente2', 'facture/updated_facture_136_1738154297.pdf', NULL, NULL, NULL, NULL, 'facture', 'ZAKARIA a', 17500.00, NULL, 0.00, 'client : 136-azmizakaria', '2025-01-29 11:38:17', '2025-01-29 11:38:17', 'commande', 19),
(244, '63-vente2', NULL, 'devis/devis_63_1738181015.pdf', NULL, NULL, NULL, 'devis', 'ZAKARIA a', 20000.00, NULL, 0.00, 'client : 63-azmizakaria', '2025-01-29 19:03:37', '2025-01-29 19:03:37', 'commande', 19),
(245, '137-vente3', 'facture/updated_facture_137_1738181139.pdf', NULL, NULL, NULL, NULL, 'facture', 'ZAKARIA a', 20000.00, NULL, 0.00, 'client : 137-azmizakaria', '2025-01-29 19:05:40', '2025-01-29 19:05:40', 'commande', 19),
(246, '60-vente3', NULL, NULL, 'bon_livraision/bon_livraision_60_1738181190.pdf', NULL, NULL, 'bon_livraision', 'ZAKARIA a', 10500.00, NULL, 0.00, 'client : 88-azmi zakaria', '2025-01-29 19:06:30', '2025-01-29 19:06:30', 'commande', 19),
(247, '61-vente3', NULL, NULL, 'bon_livraision/bon_livraision_61_1738181217.pdf', NULL, NULL, 'bon_livraision', 'ZAKARIA a', 9500.00, NULL, 0.00, 'client : 88-azmi zakaria', '2025-01-29 19:06:57', '2025-01-29 19:06:57', 'commande', 19),
(248, '104-Matériel/Produits', 'achat/factures/Facture_Achat_00000001.jpg', NULL, NULL, NULL, NULL, 'facture', 'ZAKARIA a', NULL, NULL, 0.00, 'fournsisur : -Société Al-Baraka', '2025-01-29 19:12:16', '2025-01-29 19:12:16', 'achat', 19);

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `denomination` varchar(255) NOT NULL,
  `forme_juridique_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ICE` bigint(20) UNSIGNED DEFAULT NULL,
  `IF` bigint(20) UNSIGNED DEFAULT NULL,
  `RC` bigint(20) UNSIGNED DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `pack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rib` varchar(24) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `denomination`, `forme_juridique_id`, `ICE`, `IF`, `RC`, `address`, `photo`, `created_at`, `updated_at`, `city`, `state`, `country`, `pack_id`, `rib`) VALUES
(17, 'harmoservice', 1, 1234, NULL, NULL, 'Technopark', NULL, '2024-08-05 18:02:27', '2024-08-05 18:02:27', 'casa', 'casa-stat', 'Morroco', NULL, NULL),
(18, 'harmonasaba', 2, 1, NULL, NULL, 'Technopark', NULL, '2024-08-05 19:20:35', '2024-08-06 07:51:33', 'casa', 'casa-stat', 'Morroco', NULL, NULL),
(19, 'harmo', 1, 12345, NULL, NULL, 'techno', 'photos/CdwZAZpWMOodPwnDhFLvk5hyk0cR4RdYyYAt42JK.jpg', '2024-08-05 19:21:20', '2024-11-10 19:12:17', 'casa', 'casa-stat', 'Morroco', 4, NULL),
(20, 'harmotest', 4, 12, NULL, NULL, 'Technopark', NULL, '2024-09-01 21:11:58', '2024-09-01 21:11:58', 'casa', 'casa-stat', 'Morroco', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
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
-- Structure de la table `features`
--

CREATE TABLE `features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `feature_pack`
--

CREATE TABLE `feature_pack` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pack_id` bigint(20) UNSIGNED NOT NULL,
  `feature_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `forme_juridiques`
--

CREATE TABLE `forme_juridiques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `forme_juridiques`
--

INSERT INTO `forme_juridiques` (`id`, `label`, `created_at`, `updated_at`) VALUES
(1, 'SARL', NULL, NULL),
(2, 'SARL AU', NULL, NULL),
(3, 'SA', NULL, NULL),
(4, 'SNC', NULL, NULL),
(5, 'SCS', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `country_code` varchar(28) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `name`, `email`, `phone`, `city`, `country`, `created_at`, `updated_at`, `entreprise_id`, `photo`, `country_code`) VALUES
(48, 'Société Al-Baraka', 'contact@al-baraka.com', '661234567', 'Marrakech', 'Maroc', '2025-01-28 23:44:18', '2025-01-28 23:44:18', 19, NULL, NULL),
(49, 'Compagnie Al-Ameen', 'info@al-ameen.com', '531234567', 'Jeddah', 'Arabie Saoudite', '2025-01-28 23:44:18', '2025-01-28 23:44:18', 19, NULL, NULL),
(50, 'Groupe Al-Benaa', 'support@al-benaa.com', '569876543', 'Abu Dhabi', 'Émirats Arabes Unis', '2025-01-28 23:44:18', '2025-01-28 23:44:18', 19, NULL, NULL),
(51, 'Industries El-Hassan', 'industries@el-hassan.ma', '522345678', 'Tanger', 'Maroc', '2025-01-28 23:44:18', '2025-01-28 23:44:18', 19, NULL, NULL),
(52, 'Fournitures Al-Nour', 'contact@al-nour.com', '598765432', 'Tunis', 'Tunisie', '2025-01-28 23:44:18', '2025-01-28 23:44:18', 19, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_num` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `devise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_paid` decimal(20,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `invoices`
--

INSERT INTO `invoices` (`id`, `reference_num`, `client_id`, `user_id`, `devise_id`, `entreprise_id`, `title`, `description`, `start_date`, `due_date`, `total_amount`, `status`, `created_at`, `updated_at`, `total_paid`, `payment_status`) VALUES
(135, NULL, 88, 1, 61, 19, 'v1', NULL, '2024-09-22', '2025-01-29', 12000.00, 'completed', '2025-01-29 01:05:28', '2025-01-29 01:06:12', 0.00, 'unpaid'),
(136, NULL, 88, 1, 62, 19, 'vente2', NULL, '2025-01-28', '2025-01-29', 17500.00, 'validated', '2025-01-29 11:38:17', '2025-01-29 11:38:17', 0.00, 'unpaid'),
(137, NULL, 88, 1, 63, 19, 'vente3', NULL, '2025-01-29', '2025-01-29', 20000.00, 'partial', '2025-01-29 19:05:37', '2025-01-29 19:08:04', 1000.00, 'partial');

-- --------------------------------------------------------

--
-- Structure de la table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(4, 'English', 'en', '2023-06-29 07:37:47', '2023-06-29 07:37:47'),
(5, 'Français', 'fr', '2024-04-11 05:07:52', '2024-04-11 05:07:52'),
(6, 'Arabic', 'ar', '2024-04-11 05:37:27', '2024-04-11 05:37:27'),
(7, 'Español', 'es', '2024-04-11 05:03:18', '2024-04-11 05:03:18'),
(8, 'Italian', 'it', '2024-04-11 06:16:08', '2024-04-11 06:16:08'),
(30, 'Hindi', 'hn', '2024-04-09 04:12:36', '2024-04-09 04:12:36'),
(31, 'Amharic', 'am', '2024-04-10 01:32:04', '2024-04-10 01:32:04'),
(32, 'Korean', 'ko', '2024-04-10 04:00:08', '2024-04-10 04:00:08'),
(33, 'Vietnamese', 'vn', '2024-04-10 04:45:26', '2024-04-10 04:45:26'),
(34, 'Portuguese', 'pt', '2024-04-10 23:11:05', '2024-04-10 23:11:05'),
(38, 'Dutch', 'nl', '2024-04-11 05:44:31', '2024-04-11 05:44:31'),
(39, 'Turkish', 'tr', '2024-04-11 05:52:41', '2024-04-11 05:52:41'),
(40, 'Indonesia', 'Ina', '2024-04-11 05:57:20', '2024-04-11 05:57:20'),
(41, 'Thai', 'TH', '2024-04-11 06:01:59', '2024-04-11 06:01:59'),
(42, 'Hrvatski', 'hr', '2024-04-11 06:06:25', '2024-04-11 06:06:25');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `meetings`
--

CREATE TABLE `meetings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date_time` timestamp NULL DEFAULT NULL,
  `end_date_time` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `meeting_user`
--

CREATE TABLE `meeting_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meeting_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_01_04_085224_create_todos_table', 1),
(6, '2023_01_05_044027_create_clients_table', 1),
(7, '2023_03_06_043616_create_status_table', 1),
(8, '2023_03_14_045106_create_permission_tables', 1),
(9, '2023_06_14_074411_create_settings_table', 1),
(10, '2023_06_15_111007_create_meetings_table', 1),
(11, '2023_06_15_121717_create_meeting_user_table', 1),
(12, '2023_06_15_121752_create_client_meeting_table', 1),
(13, '2023_06_29_105758_create_languages_table', 1),
(14, '2023_09_29_131131_create_notes_table', 1),
(15, '2023_10_03_115438_create_updates_table', 1),
(16, '2023_12_19_065522_create_time_trackers_table', 1),
(17, '2024_01_17_121328_create_media_table', 1),
(18, '2024_03_05_074505_create_notifications_table', 1),
(19, '2024_03_05_092120_create_client_notifications_table', 1),
(20, '2024_03_05_092139_create_notification_user_table', 1),
(21, '2024_03_11_120312_create_templates_table', 1),
(22, '2024_04_13_142557_create_priorities_table', 1),
(23, '2024_04_29_122911_create_role_status_table', 1),
(24, '2024_05_17_054155_create_user_client_preferences_table', 1),
(25, '2024_07_22_170906_update_clients_table', 1),
(26, '2024_07_22_220722_modify_clients_table_nullable_columns', 1),
(27, '2024_07_22_222758_create_disponibilities_table', 1),
(28, '2024_07_23_152535_modify_disponibilities_table', 1),
(29, '2024_07_25_091131_create_forme_juridiques_table', 1),
(30, '2024_07_25_091136_create_entreprises_table', 1),
(31, '2024_07_25_093202_add_entreprise__to_users_table', 1),
(32, '2024_07_25_093642_add_entreprise__to_clients_table', 1),
(33, '2024_07_25_100553_add_entreprise__to_disponibility__table', 1),
(40, '2024_07_25_102326_add_iceif_to_client__table', 2),
(41, '2024_07_29_104230_create_prod_categories_table', 2),
(42, '2024_07_29_104231_create_products_table', 2),
(43, '2024_07_31_103212_add_columns_entreprise_table', 2),
(44, '2024_07_31_120847_make_product_category_id_nullable', 3),
(45, '2024_07_31_121700_add_photo_to_product', 3),
(46, '2024_08_05_084607_create_commande', 4),
(47, '2024_08_05_095610_create_commande_products_table', 4),
(48, '2024_08_05_192553_update_foreign_key_on_users_table', 5),
(49, '2024_08_06_122208_create_fournisseurs_table', 6),
(50, '2024_08_07_103934_create_achats_table', 7),
(52, '2024_08_09_172807_add_product_id_to_achats_table', 8),
(53, '2024_08_13_131019_create_mouvements_stocks_table', 8),
(54, '2024_08_19_084133_add_montant_ht_to_achat', 9),
(58, '2024_08_19_095945_removeproduct_id_from_achat', 10),
(59, '2024_08_19_102601_create_achat_products_table', 10),
(60, '2024_08_18_235025_create_rolesAuth_table', 11),
(61, '2024_08_19_000945_add_role_id_to_users_table', 11),
(63, '2024_08_19_203849_add_bon_achat_to_achat', 12),
(64, '2024_08_20_135942_achat_table', 13),
(67, '2024_08_20_230154_create_documents_table', 14),
(71, '2024_08_22_143323_create_packs_table', 15),
(72, '2024_08_22_143608_add_pack_id_to_users_table', 15),
(73, '2024_08_23_100035_add_devis_to_achat', 15),
(76, '2024_08_25_145054_create_factures_table', 16),
(77, '2024_08_29_153059_update_factures_table_add_client_id', 17),
(78, '2024_08_30_221723_modify_stock_defection_product_table', 18),
(79, '2024_08_30_235249_update_factures_table', 19),
(80, '2024_08_31_004657_update_factures_table_for_commandes_unique', 19),
(81, '2024_08_25_145055_create_factures_table', 20),
(82, '2024_09_04_023845_add_photo_to_entreprises_table', 21),
(83, '2024_09_05_013444_add_type_commande_achat_todocuments', 22),
(84, '2024_09_04_171014_add_tva_to_commandes_table', 23),
(85, '2024_09_04_221451_make_due_date_nullable_in_commandes_table', 24),
(86, '2024_09_07_182240_add_photo_to_fournisseur', 25),
(87, '2024_09_07_204007_add_entreprise_too_document', 26),
(88, '2024_09_07_234939_add_entreprise_too_products', 27),
(102, '2024_09_08_005539_drop_foreign_keys_from_commande_products', 28),
(103, '2024_09_08_005613_drop_unique_index_from_commande_products', 28),
(104, '2024_09_08_005641_add_foreign_keys_to_commande_products', 28),
(105, '2024_09_08_040039_add_entreprise_id_to_commandes_table', 28),
(106, '2024_09_08_045142_add_entreprise_id_to_meetings_table', 28),
(107, '2024_09_12_093657_add_amount_total_to_products', 28),
(125, '2024_08_25_175055_create_factures_table', 29),
(126, '2024_09_14_173139_create_devises_table', 29),
(127, '2024_09_14_173713_create_vente_products_table', 29),
(128, '2024_09_17_114221_create_invoices_table', 29),
(129, '2024_09_17_123829_create_regelements_table', 29),
(130, '2024_09_17_124500_create_bon_livraisions_table', 29),
(131, '2024_09_11_044956_add_reference_num_to_commandes_table', 30),
(132, '2024_09_13_144517_create_features_table', 30),
(133, '2024_09_17_005843_add_pack_id_to_entreprises_table', 31),
(134, '2024_09_17_005856_add_pack_id_to_entreprises_table2', 31),
(135, '2024_09_23_234544_add_tva_to_clients_table', 31),
(136, '2024_10_02_132345_add_rib_to_entreprises_table', 31),
(137, '2024_10_08_203917_modify_bon_de_commande_table', 31),
(138, '2024_10_08_203918_create_bon_commande_product_table', 31),
(139, '2024_10_10_004033_add_tva_to_bon_de_commande_table', 31),
(145, '2024_10_19_002922_add_bon_liv_to_doc_table', 32),
(147, '2024_10_20_011839_create_depots_table', 33),
(149, '2024_10_20_045828_add_depot_to_vente_products_table', 34),
(150, '2024_10_18_183844_add_date_achat_to_achats_table', 35),
(151, '2024_10_18_224642_add_payment_method_to_achats_table', 35),
(152, '2024_10_19_231437_add_marge_to_achats_table', 35),
(153, '2024_10_20_165700_add_reference_to_products_table', 35),
(154, '2024_10_20_211123_add_depot_to_mouvement_stock', 36),
(164, '2024_10_21_000624_create_regelements_table', 37),
(165, '2024_10_21_002756_add_devis_bon_de_commande_table', 37),
(168, '2024_10_21_175657_add_total_amount_invoice_table', 38),
(169, '2024_10_22_033201_add_depot_to_achat_product', 39),
(170, '2024_10_22_034306_add_bon_commande_to_documents', 40),
(172, '2024_11_06_160811_add_reference_to_orders_tables', 41);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 3),
(1, 'App\\Models\\User', 29),
(1, 'App\\Models\\User', 30),
(1, 'App\\Models\\User', 32),
(1, 'App\\Models\\User', 33),
(1, 'App\\Models\\User', 34),
(1, 'App\\Models\\User', 36),
(1, 'App\\Models\\User', 46),
(9, 'App\\Models\\User', 13),
(9, 'App\\Models\\User', 37),
(9, 'App\\Models\\User', 47),
(9, 'App\\Models\\User', 48);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stocks`
--

CREATE TABLE `mouvements_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `depot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `achat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `commande_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type_mouvement` enum('entrée','sortie') NOT NULL,
  `reference` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantitéajoutée` int(11) NOT NULL,
  `quantitéprecedente` int(11) NOT NULL,
  `date_mouvement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mouvements_stocks`
--

INSERT INTO `mouvements_stocks` (`id`, `product_id`, `depot_id`, `achat_id`, `commande_id`, `created_at`, `updated_at`, `type_mouvement`, `reference`, `description`, `quantitéajoutée`, `quantitéprecedente`, `date_mouvement`) VALUES
(193, 64, 13, NULL, NULL, '2025-01-29 00:18:38', '2025-01-29 00:18:38', 'entrée', 'Huile d\'olive premium-Product_00000001', NULL, 200, 0, '2025-01-29 01:18:38'),
(194, 65, 13, NULL, NULL, '2025-01-29 00:18:39', '2025-01-29 00:18:39', 'entrée', 'Farine complète bio-Product_00000002', NULL, 500, 0, '2025-01-29 01:18:39'),
(195, 66, 13, NULL, NULL, '2025-01-29 00:18:39', '2025-01-29 00:18:39', 'entrée', 'Dattes Medjool-Product_00000003', NULL, 150, 0, '2025-01-29 01:18:39'),
(196, 67, 13, NULL, NULL, '2025-01-29 00:18:40', '2025-01-29 00:18:40', 'entrée', 'Riz Basmati long grain-Product_00000004', NULL, 300, 0, '2025-01-29 01:18:40'),
(197, 68, 13, NULL, NULL, '2025-01-29 00:18:40', '2025-01-29 00:18:40', 'entrée', 'Épices marocaines-Product_00000005', NULL, 100, 0, '2025-01-29 01:18:40'),
(198, 64, 13, NULL, NULL, '2025-01-29 00:44:00', '2025-01-29 00:44:00', 'entrée', 'Huile d\'olive premium-Product_00000006', NULL, 200, 200, '2025-01-29 01:44:00'),
(199, 65, 13, NULL, NULL, '2025-01-29 00:44:01', '2025-01-29 00:44:01', 'entrée', 'Farine complète bio-Product_00000007', NULL, 500, 500, '2025-01-29 01:44:01'),
(200, 66, 13, NULL, NULL, '2025-01-29 00:44:01', '2025-01-29 00:44:01', 'entrée', 'Dattes Medjool-Product_00000008', NULL, 150, 150, '2025-01-29 01:44:01'),
(201, 67, 13, NULL, NULL, '2025-01-29 00:44:01', '2025-01-29 00:44:01', 'entrée', 'Riz Basmati long grain-Product_00000009', NULL, 300, 300, '2025-01-29 01:44:01'),
(202, 68, 13, NULL, NULL, '2025-01-29 00:44:01', '2025-01-29 00:44:01', 'entrée', 'Épices marocaines-Product_0000000A', NULL, 100, 100, '2025-01-29 01:44:01'),
(203, 64, 10, NULL, NULL, '2025-01-29 00:45:13', '2025-01-29 00:45:13', 'entrée', 'Huile d\'olive premium-Product_0000000B', NULL, 200, 400, '2025-01-29 01:45:13'),
(204, 65, 10, NULL, NULL, '2025-01-29 00:45:13', '2025-01-29 00:45:13', 'entrée', 'Farine complète bio-Product_0000000C', NULL, 500, 1000, '2025-01-29 01:45:13'),
(205, 66, 10, NULL, NULL, '2025-01-29 00:45:13', '2025-01-29 00:45:13', 'entrée', 'Dattes Medjool-Product_0000000D', NULL, 150, 300, '2025-01-29 01:45:13'),
(206, 67, 10, NULL, NULL, '2025-01-29 00:45:13', '2025-01-29 00:45:13', 'entrée', 'Riz Basmati long grain-Product_0000000E', NULL, 300, 600, '2025-01-29 01:45:13'),
(207, 68, 10, NULL, NULL, '2025-01-29 00:45:13', '2025-01-29 00:45:13', 'entrée', 'Épices marocaines-Product_0000000F', NULL, 100, 200, '2025-01-29 01:45:13'),
(208, 64, 13, NULL, NULL, '2025-01-29 01:05:28', '2025-01-29 01:05:28', 'sortie', '61-v1', NULL, 200, 600, '2025-01-29 02:05:28'),
(209, 66, 10, NULL, NULL, '2025-01-29 11:38:17', '2025-01-29 11:38:17', 'sortie', '62-vente2', NULL, 50, 450, '2025-01-29 12:38:17'),
(210, 64, 13, NULL, NULL, '2025-01-29 11:38:17', '2025-01-29 11:38:17', 'sortie', '62-vente2', NULL, 50, 400, '2025-01-29 12:38:17'),
(211, 66, 13, NULL, NULL, '2025-01-29 19:05:39', '2025-01-29 19:05:39', 'sortie', '63-vente2', NULL, 100, 400, '2025-01-29 20:05:39'),
(212, 64, 13, NULL, NULL, '2025-01-29 19:05:39', '2025-01-29 19:05:39', 'sortie', '63-vente2', NULL, 150, 350, '2025-01-29 20:05:39'),
(213, 66, 10, 104, NULL, '2025-01-29 19:12:15', '2025-01-29 19:12:15', 'entrée', 'Achat_00000001', NULL, 100, 300, '2025-01-29 20:12:15'),
(214, 73, 13, 104, NULL, '2025-01-29 19:12:16', '2025-01-29 19:12:16', 'entrée', 'Achat_00000001', NULL, 100, 0, '2025-01-29 20:12:16');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_id` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `type_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `from_id`, `type`, `type_id`, `action`, `title`, `message`, `created_at`, `updated_at`) VALUES
(1, 'u_1', 'meeting', 15, 'assigned', 'New Meeting Assigned', 'John Doe added you in a new meeting test meetings, ID:#15.', '2024-07-29 10:24:32', '2024-07-29 10:24:32'),
(2, 'u_1', 'meeting', 16, 'assigned', 'New Meeting Assigned', 'John Doe added you in a new meeting test, ID:#16.', '2024-08-09 16:53:10', '2024-08-09 16:53:10');

-- --------------------------------------------------------

--
-- Structure de la table `notification_user`
--

CREATE TABLE `notification_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notification_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notification_user`
--

INSERT INTO `notification_user` (`user_id`, `notification_id`, `read_at`) VALUES
(36, 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `packs`
--

CREATE TABLE `packs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number_of_accounts` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `packs`
--

INSERT INTO `packs` (`id`, `name`, `number_of_accounts`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'startup', 2, 'datail1', 'packs/lqq5xIOCqbLE4kB3bFILlkNKg49DBBFVF7rY5BZa.jpg', '2024-08-23 13:03:16', '2024-08-23 13:03:16'),
(3, 'Premimum', 3, 'detail2', 'packs/vAC27omXDgSM4fDJ0e5ePx9GX55Be3AiaqTWTASn.jpg', '2024-08-23 13:04:57', '2024-08-23 13:04:57'),
(4, 'Business', 5, 'detail3', 'packs/8TUIK3V74siuEQNnhU0DBK0gGCvafchCoN2Js9rX.jpg', '2024-08-23 13:05:20', '2024-08-23 13:05:20');

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage_system_notifications', 'web', '2024-07-19 07:13:04', '2024-07-19 07:13:04'),
(2, 'delete_system_notifications', 'web', '2024-07-19 07:13:04', '2024-07-19 07:13:04');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
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
-- Structure de la table `priorities`
--

CREATE TABLE `priorities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) UNSIGNED NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `stock_defective` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` int(10) UNSIGNED DEFAULT NULL,
  `prev_price` int(10) UNSIGNED DEFAULT NULL,
  `prev_stock` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `reference`, `description`, `product_category_id`, `price`, `stock`, `stock_defective`, `created_at`, `updated_at`, `photo`, `entreprise_id`, `total_amount`, `prev_price`, `prev_stock`) VALUES
(64, 'Huile d\'olive premium', 'Product_0000000B', 'Huile d\'olive extra vierge', NULL, 50.00, 200, 0, '2025-01-29 00:18:38', '2025-01-29 19:05:39', NULL, 19, NULL, NULL, NULL),
(65, 'Farine complète bio', 'Product_0000000C', 'Farine complète de blé bio', NULL, 20.00, 1500, 0, '2025-01-29 00:18:39', '2025-01-29 00:45:13', NULL, 19, NULL, NULL, NULL),
(66, 'Dattes Medjool', 'Product_0000000D', 'Dattes de qualité supérieure', NULL, 95.73, 400, 0, '2025-01-29 00:18:39', '2025-01-29 19:12:16', NULL, 19, NULL, 80, NULL),
(67, 'Riz Basmati long grain', 'Product_0000000E', 'Riz basmati parfumé', NULL, 30.00, 900, 0, '2025-01-29 00:18:39', '2025-01-29 00:45:13', NULL, 19, NULL, NULL, NULL),
(68, 'Épices marocaines', 'Product_0000000F', 'Mélange d\'épices traditionnelles', NULL, 25.00, 300, 0, '2025-01-29 00:18:40', '2025-01-29 00:45:13', NULL, 19, NULL, NULL, NULL),
(73, 'camera', 'Product_00000010', NULL, 2, 195.00, 100, 0, '2025-01-29 19:11:00', '2025-01-29 19:12:16', NULL, 19, NULL, 195, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `prod_categories`
--

CREATE TABLE `prod_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_cat` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `prod_categories`
--

INSERT INTO `prod_categories` (`id`, `name_cat`, `created_at`, `updated_at`) VALUES
(1, 'food', NULL, NULL),
(2, 'Electronics', '2024-11-13 09:00:00', '2024-11-13 09:00:00'),
(3, 'Furniture', '2024-11-13 09:05:00', '2024-11-13 09:05:00'),
(4, 'Books', '2024-11-13 09:10:00', '2024-11-13 09:10:00'),
(5, 'Clothing', '2024-11-13 09:15:00', '2024-11-13 09:15:00'),
(6, 'Appliances', '2024-11-13 09:20:00', '2024-11-13 09:20:00'),
(7, 'Toys', '2024-11-13 09:25:00', '2024-11-13 09:25:00'),
(8, 'Sports', '2024-11-13 09:30:00', '2024-11-13 09:30:00'),
(9, 'Beauty', '2024-11-13 09:35:00', '2024-11-13 09:35:00'),
(10, 'Automotive', '2024-11-13 09:40:00', '2024-11-13 09:40:00'),
(11, 'Groceries', '2024-11-13 09:45:00', '2024-11-13 09:45:00');

-- --------------------------------------------------------

--
-- Structure de la table `regelements`
--

CREATE TABLE `regelements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `achat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_vente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mode_virement` enum('espece','cheque','virement') NOT NULL DEFAULT 'espece',
  `amount_payed` decimal(20,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `origin` enum('commande','achat') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `regelements`
--

INSERT INTO `regelements` (`id`, `achat_id`, `entreprise_id`, `invoice_vente_id`, `mode_virement`, `amount_payed`, `remaining_amount`, `date`, `origin`, `created_at`, `updated_at`) VALUES
(19, NULL, 19, 137, 'virement', 1000.00, 19000.00, '2025-01-29', 'commande', '2025-01-29 19:08:04', '2025-01-29 19:08:04'),
(20, 104, 19, NULL, 'virement', 3119.00, 28072.60, '2025-01-29', 'achat', '2025-01-29 19:13:47', '2025-01-29 19:13:47');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2023-03-13 23:28:49', '2023-09-24 04:53:41'),
(9, 'member', 'web', '2023-03-31 06:21:47', '2023-03-31 06:21:47'),
(21, 'client', 'client', '2023-12-22 05:45:49', '2023-12-22 05:45:49');

-- --------------------------------------------------------

--
-- Structure de la table `rolesauth`
--

CREATE TABLE `rolesauth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rolename` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rolesauth`
--

INSERT INTO `rolesauth` (`id`, `rolename`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator role with full access', NULL, NULL),
(2, 'user', 'user role with its Entreprise access', NULL, NULL),
(3, 'underuser', 'under user that works for an entreprise', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role_status`
--

CREATE TABLE `role_status` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `variable` varchar(255) DEFAULT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `variable`, `value`, `created_at`, `updated_at`) VALUES
(1, 'email_settings', '{\"dnr\":null,\"email\":\"contact1@admin.mounassabat.ma\",\"password\":\"A2fD@F_weRws\",\"smtp_host\":\"admin.mounassabat.ma\",\"smtp_port\":\"465\",\"email_content_type\":\"text\",\"smtp_encryption\":\"tls\"}', '2024-07-19 17:07:00', '2024-07-19 17:19:55'),
(5, 'general_settings', '{\"company_title\":\"Taskify\",\"currency_full_form\":\"Indian Rupee\",\"currency_symbol\":\"\\u20b9\",\"currency_code\":\"INR\",\"currency_symbol_position\":\"before\",\"currency_formate\":\"comma_separated\",\"decimal_points_in_currency\":\"2\",\"timezone\":\"Africa\\/Kinshasa\",\"date_format\":\"DD-MM-YYYY|d-m-Y\",\"time_format\":\"H:i:s\",\"allowSignup\":1,\"footer_text\":\"<p>made with \\u2764\\ufe0f by <a href=\\\"https:\\/\\/www.infinitietech.com\\/\\\" target=\\\"_blank\\\" rel=\\\"noopener\\\">Infinitie Technologies<\\/a><\\/p>\",\"full_logo\":\"\",\"half_logo\":\"\",\"favicon\":\"\"}', '2023-06-14 10:48:25', '2024-07-24 07:43:52'),
(9, 'pusher_settings', NULL, '2023-06-21 08:33:13', '2023-10-09 04:09:20'),
(10, 'email_settings', '{\"dnr\":null,\"email\":\"contact1@admin.mounassabat.ma\",\"password\":\"A2fD@F_weRws\",\"smtp_host\":\"admin.mounassabat.ma\",\"smtp_port\":\"465\",\"email_content_type\":\"text\",\"smtp_encryption\":\"tls\"}', '2023-06-21 11:43:07', '2024-07-19 17:19:55'),
(11, 'media_storage_settings', '{\"dnr\":null,\"media_storage_type\":\"local\",\"s3_key\":null,\"s3_secret\":null,\"s3_region\":null,\"s3_bucket\":null}', '2024-01-22 11:03:48', '2024-07-24 13:10:59'),
(12, 'sms_gateway_settings', NULL, '2024-03-29 05:21:39', '2024-04-02 08:45:13');

-- --------------------------------------------------------

--
-- Structure de la table `statuses`
--

CREATE TABLE `statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `templates`
--

CREATE TABLE `templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` text DEFAULT NULL,
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `time_trackers`
--

CREATE TABLE `time_trackers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `todos`
--

CREATE TABLE `todos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` varchar(255) NOT NULL,
  `is_completed` tinyint(4) NOT NULL DEFAULT 0,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `creator_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `updates`
--

CREATE TABLE `updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `updates`
--

INSERT INTO `updates` (`id`, `version`, `created_at`, `updated_at`) VALUES
(1, '1.0.0', '2024-07-29 08:15:42', '2024-07-29 08:15:42');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `country_code` varchar(28) DEFAULT NULL,
  `phone` varchar(56) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(56) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `doj` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'avatar.png',
  `active_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'notsure',
  `dark_mode` tinyint(4) NOT NULL DEFAULT 0,
  `messenger_color` varchar(255) DEFAULT NULL,
  `lang` varchar(28) NOT NULL DEFAULT 'en',
  `remember_token` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `entreprise_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `country_code`, `phone`, `email`, `role_id`, `address`, `city`, `state`, `country`, `zip`, `password`, `dob`, `doj`, `photo`, `avatar`, `active_status`, `dark_mode`, `messenger_color`, `lang`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`, `status`, `entreprise_id`) VALUES
(1, 'ZAKARIA', 'a', '+1', '123456789', 'lartiste7756@gmail.com', 2, '123 Main St', 'Anytown', 'CA', 'USA', NULL, '$2y$10$doh60yTQRIWeLOG/N1ByluQwEbHUOOC8dpitEbVexEDf6jWcGsdxe', '1990-01-01', '2020-01-01', 'photos/7I3rrgf7ALJHIS0alSFN3zlLIBukoIIkSClpqxDc.jpg', 'avatar.png', 1, 1, '#336699', 'en', NULL, '2024-07-19 06:16:32', '2024-07-19 06:16:32', '2025-01-27 19:51:20', 1, 19),
(2, 'raabi', 'cina', '+1', '123456789', 'someone@gmail.com', 1, '123 Main St', 'Anytown', 'CA', 'USA', NULL, '$2y$10$IV0ksduTEzcec5JhkvyoVeVW/xSl1XdSFSuG6ohqOqLUQ6efHlM7C', '1990-01-01', '2020-01-01', NULL, 'avatar.png', 1, 1, '#336699', 'en', NULL, '2024-07-19 07:27:27', '2024-07-19 07:27:27', '2024-07-19 07:27:27', 1, 17),
(3, 'admin', 'anas', NULL, NULL, 'anassbenabbi137@gmail.com', 1, NULL, NULL, NULL, NULL, NULL, '$2y$10$J5BabarBRrFMWllISsAS8usPPC2jGUk.l4PPdgAd5UHCsWg1bwJf6', NULL, NULL, NULL, 'avatar.png', 0, 0, NULL, 'en', NULL, '2024-07-19 11:52:26', '2024-07-19 11:52:30', '2024-08-05 20:22:02', 1, 17),
(13, 'admin', 'test', NULL, NULL, 'admin@gmail.com', 2, NULL, NULL, NULL, NULL, NULL, '$2y$10$O80qL02efHzzaP0oqzUCr.iNEZNgR9U26yz6DilWlY1FDmEbSoo3S', NULL, NULL, 'photos/B5WDgH7ee8utyaMdjw6Vb6w0hJaKXe8E9zHh0ZGC.webp', 'avatar.png', 0, 0, NULL, 'en', NULL, NULL, '2024-07-25 08:10:02', '2024-08-05 07:31:28', 0, 17),
(36, 'ZAKARIA', 'test', NULL, NULL, 'zakaria@gmail.com', 2, NULL, NULL, NULL, NULL, NULL, '$2y$10$wM8Qeuy9qarcYgPnAWHeqOOVTJvY0tiaJqTXkhV16PLZmyIFK0F7i', NULL, NULL, 'photos/no-image.jpg', 'avatar.png', 0, 0, NULL, 'en', NULL, NULL, '2024-08-05 20:01:35', '2024-08-05 20:01:35', 0, 17),
(37, 'testname', 'testlast', NULL, NULL, 'testnl@gmail.com', 2, NULL, NULL, NULL, NULL, NULL, '$2y$10$0RQvEcpnNnAGMNe.3kJHVOAjpxiRLLbGJUcmtGIBFOpAQPAycOeTi', NULL, NULL, 'photos/no-image.jpg', 'avatar.png', 0, 0, NULL, 'en', NULL, NULL, '2024-08-23 11:24:36', '2024-08-23 11:24:36', 0, 17),
(46, 'testlast', 'lasttest', NULL, NULL, 'tstgs@gmail.com', 1, NULL, NULL, NULL, NULL, NULL, '$2y$10$dfMvFwL68JkMCvDwTMYYpORI3vaO6J/j/9ClKQlYW2iVyaAhjAk9i', NULL, NULL, 'photos/no-image.jpg', 'avatar.png', 0, 0, NULL, 'en', NULL, NULL, '2024-08-23 12:51:20', '2024-08-23 12:51:20', 0, 17),
(47, 'test', 'tstz', NULL, NULL, 'szu@gmail.com', 2, NULL, NULL, NULL, NULL, NULL, '$2y$10$od/w/kAuHNiU.Nf5WncVYezlpE/rq.zW9D20loWF/5S6mhyhlXXGC', NULL, NULL, 'photos/no-image.jpg', 'avatar.png', 0, 0, NULL, 'en', NULL, NULL, '2024-08-23 13:31:29', '2024-08-23 13:31:29', 0, 19),
(48, 'decs', 'ezfcsdkc', NULL, NULL, 'efed@gmail.com', 3, NULL, NULL, NULL, NULL, NULL, '$2y$10$IIW0XdJJs5cV0eFBqswMJO8Hze.fm9nKLqa3D9kwMMgJdCgO6iNyW', NULL, NULL, 'photos/no-image.jpg', 'avatar.png', 0, 0, NULL, 'en', NULL, NULL, '2024-08-23 13:37:35', '2024-08-23 13:37:35', 0, 18);

-- --------------------------------------------------------

--
-- Structure de la table `user_client_preferences`
--

CREATE TABLE `user_client_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(56) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `visible_columns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`visible_columns`)),
  `default_view` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_client_preferences`
--

INSERT INTO `user_client_preferences` (`id`, `user_id`, `table_name`, `visible_columns`, `default_view`) VALUES
(1, 'u_1', 'users', '[\"id\",\"profile\",\"role\",\"phone\",\"created_at\",\"actions\"]', NULL),
(2, 'u_1', 'clients', '[\"id\",\"profile\",\"denomenation\",\"phone\",\"ice\",\"rc\",\"if\",\"actions\"]', NULL),
(3, 'u_1', 'entreprises', '[\"id\",\"profile\",\"formej\",\"city\",\"country\",\"created_at\",\"updated_at\",\"actions\"]', NULL),
(4, 'u_1', 'fournisseurs', '[\"id\",\"profile\",\"phone\",\"actions\"]', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vente_products`
--

CREATE TABLE `vente_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `depot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED NOT NULL,
  `related_type` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vente_products`
--

INSERT INTO `vente_products` (`id`, `product_id`, `depot_id`, `related_id`, `related_type`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(376, 64, NULL, 61, 'App\\Models\\devise', 200, 60.00, NULL, NULL),
(377, 64, 13, 135, 'App\\Models\\invoice', 200, 60.00, NULL, NULL),
(378, 64, NULL, 59, 'App\\Models\\bon_livraision', 200, 60.00, NULL, NULL),
(379, 66, NULL, 62, 'App\\Models\\devise', 100, 200.00, NULL, NULL),
(380, 64, NULL, 62, 'App\\Models\\devise', 50, 150.00, NULL, NULL),
(381, 66, 10, 136, 'App\\Models\\invoice', 50, 200.00, NULL, NULL),
(382, 64, 13, 136, 'App\\Models\\invoice', 50, 150.00, NULL, NULL),
(383, 66, NULL, 63, 'App\\Models\\devise', 100, 50.00, NULL, NULL),
(384, 64, NULL, 63, 'App\\Models\\devise', 150, 100.00, NULL, NULL),
(385, 66, 13, 137, 'App\\Models\\invoice', 100, 50.00, NULL, NULL),
(386, 64, 13, 137, 'App\\Models\\invoice', 150, 100.00, NULL, NULL),
(387, 66, NULL, 60, 'App\\Models\\bon_livraision', 50, 50.00, NULL, NULL),
(388, 64, NULL, 60, 'App\\Models\\bon_livraision', 80, 100.00, NULL, NULL),
(389, 66, NULL, 61, 'App\\Models\\bon_livraision', 50, 50.00, NULL, NULL),
(390, 64, NULL, 61, 'App\\Models\\bon_livraision', 70, 100.00, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achats`
--
ALTER TABLE `achats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achats_entreprise_id_foreign` (`entreprise_id`),
  ADD KEY `achats_fournisseur_id_foreign` (`fournisseur_id`);

--
-- Index pour la table `achat_product`
--
ALTER TABLE `achat_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achat_product_achat_id_foreign` (`achat_id`),
  ADD KEY `achat_product_product_id_foreign` (`product_id`),
  ADD KEY `achat_product_depot_id_foreign` (`depot_id`);

--
-- Index pour la table `bon_commande_product`
--
ALTER TABLE `bon_commande_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bon_commande_product_bon_de_commande_id_foreign` (`bon_de_commande_id`),
  ADD KEY `bon_commande_product_product_id_foreign` (`product_id`);

--
-- Index pour la table `bon_de_commande`
--
ALTER TABLE `bon_de_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bon_de_commande_fournisseur_id_foreign` (`fournisseur_id`),
  ADD KEY `bon_de_commande_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `bon_livraisions`
--
ALTER TABLE `bon_livraisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bon_livraisions_client_id_foreign` (`client_id`),
  ADD KEY `bon_livraisions_user_id_foreign` (`user_id`),
  ADD KEY `bon_livraisions_invoice_id_foreign` (`invoice_id`),
  ADD KEY `bon_livraisions_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD KEY `clients_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `client_meeting`
--
ALTER TABLE `client_meeting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_meeting_meeting_id_client_id_unique` (`meeting_id`,`client_id`),
  ADD KEY `client_meeting_client_id_foreign` (`client_id`);

--
-- Index pour la table `client_notifications`
--
ALTER TABLE `client_notifications`
  ADD PRIMARY KEY (`client_id`,`notification_id`),
  ADD KEY `client_notifications_notification_id_foreign` (`notification_id`);

--
-- Index pour la table `depots`
--
ALTER TABLE `depots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `depots_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `depot_product`
--
ALTER TABLE `depot_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `depot_product_depot_id_product_id_unique` (`depot_id`,`product_id`),
  ADD KEY `depot_product_product_id_foreign` (`product_id`);

--
-- Index pour la table `devises`
--
ALTER TABLE `devises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devises_client_id_foreign` (`client_id`),
  ADD KEY `devises_user_id_foreign` (`user_id`),
  ADD KEY `devises_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `disponibilities`
--
ALTER TABLE `disponibilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disponibilities_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprises_forme_juridique_id_foreign` (`forme_juridique_id`),
  ADD KEY `entreprises_pack_id_foreign` (`pack_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `feature_pack`
--
ALTER TABLE `feature_pack`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feature_pack_pack_id_foreign` (`pack_id`),
  ADD KEY `feature_pack_feature_id_foreign` (`feature_id`);

--
-- Index pour la table `forme_juridiques`
--
ALTER TABLE `forme_juridiques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fournisseurs_email_unique` (`email`),
  ADD KEY `fournisseurs_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_client_id_foreign` (`client_id`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `invoices_devise_id_foreign` (`devise_id`),
  ADD KEY `invoices_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Index pour la table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meetings_created_by_foreign` (`created_by`),
  ADD KEY `meetings_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `meeting_user`
--
ALTER TABLE `meeting_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meeting_user_meeting_id_user_id_unique` (`meeting_id`,`user_id`),
  ADD KEY `meeting_user_user_id_foreign` (`user_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mouvements_stocks_product_id_foreign` (`product_id`),
  ADD KEY `mouvements_stocks_achat_id_foreign` (`achat_id`),
  ADD KEY `mouvements_stocks_depot_id_foreign` (`depot_id`),
  ADD KEY `mouvements_stocks_commande_id_foreign` (`commande_id`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_creator_id_foreign` (`creator_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notification_user`
--
ALTER TABLE `notification_user`
  ADD PRIMARY KEY (`user_id`,`notification_id`),
  ADD KEY `notification_user_notification_id_foreign` (`notification_id`);

--
-- Index pour la table `packs`
--
ALTER TABLE `packs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_product_category_id_foreign` (`product_category_id`),
  ADD KEY `products_entreprise_id_foreign` (`entreprise_id`);

--
-- Index pour la table `prod_categories`
--
ALTER TABLE `prod_categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `regelements`
--
ALTER TABLE `regelements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regelements_entreprise_id_foreign` (`entreprise_id`),
  ADD KEY `regelements_achat_id_foreign` (`achat_id`),
  ADD KEY `regelements_invoice_vente_id_foreign` (`invoice_vente_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `rolesauth`
--
ALTER TABLE `rolesauth`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `role_status`
--
ALTER TABLE `role_status`
  ADD PRIMARY KEY (`role_id`,`status_id`),
  ADD KEY `role_status_status_id_foreign` (`status_id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `templates_type_name_unique` (`type`,`name`);

--
-- Index pour la table `time_trackers`
--
ALTER TABLE `time_trackers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_trackers_user_id_foreign` (`user_id`);

--
-- Index pour la table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todos_creator_id_foreign` (`creator_id`);

--
-- Index pour la table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_entreprise_id_foreign` (`entreprise_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Index pour la table `user_client_preferences`
--
ALTER TABLE `user_client_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vente_products`
--
ALTER TABLE `vente_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vente_products_product_id_foreign` (`product_id`),
  ADD KEY `vente_products_related_id_related_type_index` (`related_id`,`related_type`),
  ADD KEY `vente_products_depot_id_foreign` (`depot_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achats`
--
ALTER TABLE `achats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT pour la table `achat_product`
--
ALTER TABLE `achat_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `bon_commande_product`
--
ALTER TABLE `bon_commande_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `bon_de_commande`
--
ALTER TABLE `bon_de_commande`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `bon_livraisions`
--
ALTER TABLE `bon_livraisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT pour la table `client_meeting`
--
ALTER TABLE `client_meeting`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `depots`
--
ALTER TABLE `depots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `depot_product`
--
ALTER TABLE `depot_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `devises`
--
ALTER TABLE `devises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pour la table `disponibilities`
--
ALTER TABLE `disponibilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `feature_pack`
--
ALTER TABLE `feature_pack`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `forme_juridiques`
--
ALTER TABLE `forme_juridiques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT pour la table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `meeting_user`
--
ALTER TABLE `meeting_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `packs`
--
ALTER TABLE `packs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `prod_categories`
--
ALTER TABLE `prod_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `regelements`
--
ALTER TABLE `regelements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `rolesauth`
--
ALTER TABLE `rolesauth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `time_trackers`
--
ALTER TABLE `time_trackers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `user_client_preferences`
--
ALTER TABLE `user_client_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `vente_products`
--
ALTER TABLE `vente_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `achats_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `achats_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `achat_product`
--
ALTER TABLE `achat_product`
  ADD CONSTRAINT `achat_product_achat_id_foreign` FOREIGN KEY (`achat_id`) REFERENCES `achats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `achat_product_depot_id_foreign` FOREIGN KEY (`depot_id`) REFERENCES `depots` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `achat_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bon_commande_product`
--
ALTER TABLE `bon_commande_product`
  ADD CONSTRAINT `bon_commande_product_bon_de_commande_id_foreign` FOREIGN KEY (`bon_de_commande_id`) REFERENCES `bon_de_commande` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bon_commande_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bon_de_commande`
--
ALTER TABLE `bon_de_commande`
  ADD CONSTRAINT `bon_de_commande_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bon_de_commande_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `bon_livraisions`
--
ALTER TABLE `bon_livraisions`
  ADD CONSTRAINT `bon_livraisions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bon_livraisions_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bon_livraisions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bon_livraisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `client_meeting`
--
ALTER TABLE `client_meeting`
  ADD CONSTRAINT `client_meeting_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_meeting_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client_notifications`
--
ALTER TABLE `client_notifications`
  ADD CONSTRAINT `client_notifications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_notifications_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `depots`
--
ALTER TABLE `depots`
  ADD CONSTRAINT `depots_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `depot_product`
--
ALTER TABLE `depot_product`
  ADD CONSTRAINT `depot_product_depot_id_foreign` FOREIGN KEY (`depot_id`) REFERENCES `depots` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `depot_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `devises`
--
ALTER TABLE `devises`
  ADD CONSTRAINT `devises_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devises_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devises_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `disponibilities`
--
ALTER TABLE `disponibilities`
  ADD CONSTRAINT `disponibilities_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `entreprises_forme_juridique_id_foreign` FOREIGN KEY (`forme_juridique_id`) REFERENCES `forme_juridiques` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `entreprises_pack_id_foreign` FOREIGN KEY (`pack_id`) REFERENCES `packs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `feature_pack`
--
ALTER TABLE `feature_pack`
  ADD CONSTRAINT `feature_pack_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feature_pack_pack_id_foreign` FOREIGN KEY (`pack_id`) REFERENCES `packs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD CONSTRAINT `fournisseurs_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_devise_id_foreign` FOREIGN KEY (`devise_id`) REFERENCES `devises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meetings_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `meeting_user`
--
ALTER TABLE `meeting_user`
  ADD CONSTRAINT `meeting_user_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meeting_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  ADD CONSTRAINT `mouvements_stocks_achat_id_foreign` FOREIGN KEY (`achat_id`) REFERENCES `achats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mouvements_stocks_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mouvements_stocks_depot_id_foreign` FOREIGN KEY (`depot_id`) REFERENCES `depots` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mouvements_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notification_user`
--
ALTER TABLE `notification_user`
  ADD CONSTRAINT `notification_user_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `prod_categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `regelements`
--
ALTER TABLE `regelements`
  ADD CONSTRAINT `regelements_achat_id_foreign` FOREIGN KEY (`achat_id`) REFERENCES `achats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `regelements_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `regelements_invoice_vente_id_foreign` FOREIGN KEY (`invoice_vente_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_status`
--
ALTER TABLE `role_status`
  ADD CONSTRAINT `role_status_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_status_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `time_trackers`
--
ALTER TABLE `time_trackers`
  ADD CONSTRAINT `time_trackers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `todos_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `rolesauth` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `vente_products`
--
ALTER TABLE `vente_products`
  ADD CONSTRAINT `vente_products_depot_id_foreign` FOREIGN KEY (`depot_id`) REFERENCES `depots` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vente_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

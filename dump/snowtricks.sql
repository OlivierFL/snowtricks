-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jan 09, 2021 at 07:37 AM
-- Server version: 8.0.20
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snowtricks`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
                              `id` int NOT NULL,
                              `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                              `created_at` datetime NOT NULL,
                              `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'grab', '2020-12-20 11:10:53', '2020-12-20 11:10:53'),
(2, 'rotation', '2020-12-20 11:10:53', '2020-12-20 11:10:53'),
(3, 'flip', '2020-12-20 11:11:51', '2020-12-20 11:11:51'),
(4, 'slide', '2020-12-20 11:11:51', '2020-12-20 11:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
                            `id` int NOT NULL,
                            `trick_id` int NOT NULL,
                            `content` longtext COLLATE utf8mb4_general_ci NOT NULL,
                            `created_at` datetime NOT NULL,
                            `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
                                               `version` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
                                               `executed_at` datetime DEFAULT NULL,
                                               `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201219164339', '2020-12-19 17:47:57', 576),
('DoctrineMigrations\\Version20201222101056', '2020-12-22 11:13:30', 232),
('DoctrineMigrations\\Version20210107115727', '2021-01-07 12:59:14', 66);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
                         `id` int NOT NULL,
                         `trick_id` int NOT NULL,
                         `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                         `alt_text` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                         `created_at` datetime NOT NULL,
                         `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `trick_id`, `url`, `alt_text`, `created_at`, `updated_at`) VALUES
(1, 1, 'trick_example.jpg', 'grab', '2020-12-20 11:36:57', '2020-12-20 11:36:57'),
(2, 2, 'snowboard_trick.jpg', 'rotation 360', '2020-12-20 15:47:10', '2020-12-20 15:47:10'),
(3, 3, 'snowboard_trick_02.jpg', 'backflip', '2020-12-20 15:47:10', '2020-12-20 15:47:10'),
(4, 4, 'snowboard_trick_03.jpg', 'sad trick', '2020-12-20 15:47:36', '2020-12-20 15:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `tricks`
--

CREATE TABLE `tricks` (
                          `id` int NOT NULL,
                          `category_id` int NOT NULL,
                          `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                          `description` longtext COLLATE utf8mb4_general_ci NOT NULL,
                          `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                          `created_at` datetime NOT NULL,
                          `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tricks`
--

INSERT INTO `tricks` (`id`, `category_id`, `name`, `description`, `slug`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mute', 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant', 'mute', '2020-12-20 11:15:16', '2020-12-20 11:15:16'),
(2, 2, '360', 'Un tour complet', '360', '2020-12-20 11:16:08', '2020-12-20 11:16:08'),
(3, 3, 'Backflip', 'Rotation verticale en arrière', 'backflip', '2020-12-20 11:17:28', '2020-12-20 11:17:28'),
(4, 1, 'Sad', 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant', 'sad', '2020-12-20 11:39:43', '2020-12-20 11:39:43'),
(5, 1, 'Indy', 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière', 'indy', '2020-12-27 11:01:22', '2020-12-27 11:01:22'),
(6, 2, '540', 'Un tour et demi', '540', '2020-12-27 11:01:22', '2020-12-27 11:01:22'),
(7, 3, 'Frontflip', 'Rotation en avant', 'frontflip', '2020-12-27 11:03:53', '2020-12-27 11:03:53'),
(8, 4, 'Tail slide', 'Arrière de la planche sur la barre de slide', 'tail-slide', '2020-12-27 11:03:53', '2020-12-27 11:03:53');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
                        `id` int NOT NULL,
                        `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `username` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `roles` json NOT NULL,
                        `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `username`, `roles`, `password`, `email`, `is_verified`) VALUES
(1, 'Admin', 'Admin', 'admin', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$hNfHSuvPkNVAJAVEN+8yQg$+tzBNCwb6vVgYCqQp7GyfCp2c8OjNjHhqW4xUrHaXTk', 'admin@example.com', 1),
(2, 'Test', 'Test', 'test', '[]', '$argon2id$v=19$m=65536,t=4,p=1$1jrNgVW8forfOKXDBOjgHg$kyxj8zwrwnbH5SZBkscjZuMIUuf1X6WVAmwShJGI/PU', 'test@example.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_trick`
--

CREATE TABLE `user_trick` (
                              `user_id` int NOT NULL,
                              `trick_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_trick`
--

INSERT INTO `user_trick` (`user_id`, `trick_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(2, 1),
(2, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_5F9E962AB281BE2E` (`trick_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
    ADD PRIMARY KEY (`version`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_6A2CA10CB281BE2E` (`trick_id`);

--
-- Indexes for table `tricks`
--
ALTER TABLE `tricks`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_E1D902C112469DE2` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
    ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexes for table `user_trick`
--
ALTER TABLE `user_trick`
    ADD PRIMARY KEY (`user_id`,`trick_id`),
    ADD KEY `IDX_3A325246A76ED395` (`user_id`),
    ADD KEY `IDX_3A325246B281BE2E` (`trick_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tricks`
--
ALTER TABLE `tricks`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
    ADD CONSTRAINT `FK_5F9E962AB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
    ADD CONSTRAINT `FK_6A2CA10CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`);

--
-- Constraints for table `tricks`
--
ALTER TABLE `tricks`
    ADD CONSTRAINT `FK_E1D902C112469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `user_trick`
--
ALTER TABLE `user_trick`
    ADD CONSTRAINT `FK_3A325246A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_3A325246B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

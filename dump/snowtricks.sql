-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 16, 2021 at 12:11 PM
-- Server version: 8.0.20
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snowtricks`
--
CREATE DATABASE IF NOT EXISTS `snowtricks` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `snowtricks`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories`
(
    `id`         int                                     NOT NULL,
    `name`       varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `created_at` datetime                                NOT NULL,
    `updated_at` datetime                                NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`)
VALUES (1, 'grab', '2021-04-12 10:53:34', '2021-04-12 10:53:34'),
       (2, 'rotation', '2021-04-12 10:53:34', '2021-04-12 10:53:34'),
       (3, 'flip', '2021-04-12 10:54:30', '2021-04-12 10:54:30'),
       (4, 'slide', '2021-04-12 10:54:30', '2021-04-12 10:54:30');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments`
(
    `id`         int                                 NOT NULL,
    `trick_id`   int                                 NOT NULL,
    `author_id`  int                                 NOT NULL,
    `content`    longtext COLLATE utf8mb4_general_ci NOT NULL,
    `is_valid`   tinyint(1)                          NOT NULL,
    `created_at` datetime                            NOT NULL,
    `updated_at` datetime                            NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `trick_id`, `author_id`, `content`, `is_valid`, `created_at`, `updated_at`)
VALUES (1, 13, 1, 'Super explication !', 1, '2021-04-13 17:53:26', '2021-04-13 18:04:36'),
       (2, 13, 1, 'Merci pour les explications, à tester cet hiver.', 1, '2021-04-13 17:58:38', '2021-04-13 18:04:30'),
       (3, 12, 2, 'Le trick est sympa à faire, idéal pour les débutants', 1, '2021-04-13 17:59:39',
        '2021-04-13 18:04:22'),
       (4, 12, 1, 'Ce trick est super à faire, il existe plein de variations en plus !', 1, '2021-04-13 18:03:53',
        '2021-04-13 18:04:16'),
       (5, 11, 2, 'Trop bien ce tuto, ça m\'a fait progresser', 1, '2021-04-13 18:06:15', '2021-04-13 18:06:40'),
       (15, 13, 2, 'Bonne explication, j\'approuve ! Le snowboard donne d\'autres sensations que le ski', 1,
        '2021-04-14 12:19:02', '2021-04-14 12:21:31'),
       (16, 13, 2, 'Grâce à cette vidéo, en 2 jours j\'ai pu faire le trick comme il faut', 1, '2021-04-14 12:19:57',
        '2021-04-14 12:21:23'),
       (17, 13, 2, 'Merci pour ce tuto très intéressant :)', 1, '2021-04-14 12:22:27', '2021-04-14 12:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media`
(
    `id`         int                                     NOT NULL,
    `url`        varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `alt_text`   varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `type`       varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `created_at` datetime                                NOT NULL,
    `updated_at` datetime                                NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `url`, `alt_text`, `type`, `created_at`, `updated_at`)
VALUES (3, 'Flip-Back-flip-01-60747d4369128.jpg', 'Backflip', 'image', '2021-04-12 19:03:00', '2021-04-12 19:03:00'),
       (4, 'Flip-Back-flip-02-60747d43692f5.jpg', 'Backflip 2', 'image', '2021-04-12 19:03:00', '2021-04-12 19:03:00'),
       (5, 'SlhGVnFPTDE', 'Comment faire un BackFlip en snowboard', 'youtube', '2021-04-12 19:03:00',
        '2021-04-12 19:03:00'),
       (6, 'Flip-Front-flip-01-60748054df73e.jpg', 'Frontflip', 'image', '2021-04-12 19:16:05', '2021-04-12 19:16:05'),
       (7, 'xhvqu2XBvI0', 'How To Tame Dog (Front Flip) On A Snowboard (Regular)', 'youtube', '2021-04-12 19:16:05',
        '2021-04-12 19:16:05'),
       (8, 'Flip-Front-flip-02-60748055392fc.jpg', 'Front flip texte alternatif', 'image', '2021-04-12 19:16:05',
        '2021-04-12 19:16:05'),
       (9, 'GS9MMT_bNn8', 'How To Front 360 a Small Jump - Snowboard Tricks', 'youtube', '2021-04-12 19:18:11',
        '2021-04-12 19:18:11'),
       (10, 'Rotation-360-01-607480d378cda.jpg', '360', 'image', '2021-04-12 19:18:11', '2021-04-12 19:18:11'),
       (11, 'Slide-Tail-slide-01-607481572f83b.jpg', 'Tail slide', 'image', '2021-04-12 19:20:23',
        '2021-04-12 19:20:23'),
       (12, 'HRNXjMBakwM', 'How To Tail Slide 270 Out On A Snowboard', 'youtube', '2021-04-12 19:20:23',
        '2021-04-12 19:20:23'),
       (13, '4JfBfQpG77o', '720 Snowboard Trick Progression with TJ', 'youtube', '2021-04-12 19:24:02',
        '2021-04-12 19:24:02'),
       (14, 'Rotation-720-01-60748232a4ae0.jpg', '720', 'image', '2021-04-12 19:24:02', '2021-04-12 19:24:02'),
       (15, 'Grab-Indy-01-6074829f05b32.jpg', 'Indy', 'image', '2021-04-12 19:25:51', '2021-04-12 19:25:51'),
       (16, 'Grab-Indy-02-6074829f060cb.jpg', 'Comment réaliser un indy', 'image', '2021-04-12 19:25:51',
        '2021-04-12 19:25:51'),
       (17, '6yA3XqjTh_w', 'How To Indy Grab - Snowboarding Tricks', 'youtube', '2021-04-12 19:25:51',
        '2021-04-12 19:25:51'),
       (18, '51sQRIK-TEI', 'How To Indy, Melon, Mute & Stalefish Grab On A Snowboard (Regular)', 'youtube',
        '2021-04-12 19:27:11', '2021-04-12 19:27:11'),
       (19, 'Opg5g4zsiGY', 'Backside 360 w/ Mute Grab Snowboard Trick Session', 'youtube', '2021-04-12 19:27:11',
        '2021-04-12 19:27:11'),
       (20, 'Grab-Sad-01-60748353e6cbd.jpg', 'Sad', 'image', '2021-04-12 19:28:52', '2021-04-12 19:28:52'),
       (21, 'KEdFwJ4SWq4', 'How to Grab Sad Air | TransWorld SNOWboarding Grab Directory', 'youtube',
        '2021-04-12 19:28:52', '2021-04-12 19:28:52'),
       (22, 'f9FjhCt_w2U', 'How to Grab Stalefish | TransWorld SNOWboarding Grab Directory', 'youtube',
        '2021-04-12 19:31:07', '2021-04-12 19:31:07'),
       (23, 'Grab-Stalefish-01-607483db28890.jpg', 'Stalefish', 'image', '2021-04-12 19:31:07', '2021-04-12 19:31:07'),
       (24, 'Grab-Stalefish-02-607483db28a52.jpg', 'Comment réaliser un stalefish', 'image', '2021-04-12 19:31:07',
        '2021-04-12 19:31:07'),
       (25, 'Grab-Tail-01-6074843415735.jpg', 'Tail grab', 'image', '2021-04-12 19:32:36', '2021-04-12 19:32:36'),
       (26, 'Grab-Tail-03-6074843415874.jpg', 'Tail grab explication', 'image', '2021-04-12 19:32:36',
        '2021-04-12 19:32:36'),
       (27, 'Grab-Tail-02-6074843415be7.jpg', 'Tail grab texte alternatif', 'image', '2021-04-12 19:32:36',
        '2021-04-12 19:32:36'),
       (28, 'id8VKl9RVQw', 'How to Tail Grab - Snowboarding Tricks', 'youtube', '2021-04-12 19:32:59',
        '2021-04-12 19:32:59'),
       (29, '15747966',
        'Snowboard tricks - Nose Roll 180 Frontside, switch noseroll180 FS, freestyle triky na snb, S-KP', 'vimeo',
        '2021-04-12 19:34:59', '2021-04-12 19:34:59'),
       (30, 'Grab-Nose-01-607484c2c1940.jpg', 'Nose grab', 'image', '2021-04-12 19:34:59', '2021-04-12 19:34:59'),
       (31, 'M-W7Pmo-YMY', 'How to Nose Grab Snowboard - Snowboarding Tricks', 'youtube', '2021-04-12 19:34:59',
        '2021-04-12 19:34:59'),
       (32, 'Grab-Nose-02-607484c321bf0.jpg', 'Nose', 'image', '2021-04-12 19:34:59', '2021-04-12 19:34:59'),
       (33, 'Snowboarder-in-Flight-Colorado-By-Mark-Thiessen-6075c5734e4ec.jpg', 'Test', 'image', '2021-04-13 18:23:15',
        '2021-04-13 18:23:15'),
       (35, 'trick-example-6076d2537f903.jpg', 'Mute', 'image', '2021-04-14 13:30:27', '2021-04-14 13:30:27');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_request`
--

CREATE TABLE `reset_password_request`
(
    `id`           int                                     NOT NULL,
    `user_id`      int                                     NOT NULL,
    `selector`     varchar(20) COLLATE utf8mb4_general_ci  NOT NULL,
    `hashed_token` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
    `requested_at` datetime                                NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    `expires_at`   datetime                                NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tricks`
--

CREATE TABLE `tricks`
(
    `id`          int                                     NOT NULL,
    `category_id` int                                     NOT NULL,
    `author_id`   int                                     NOT NULL,
    `name`        varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `description` longtext COLLATE utf8mb4_general_ci     NOT NULL,
    `slug`        varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `created_at`  datetime                                NOT NULL,
    `updated_at`  datetime                                NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `tricks`
--

INSERT INTO `tricks` (`id`, `category_id`, `author_id`, `name`, `description`, `slug`, `created_at`, `updated_at`)
VALUES (1, 1, 1, 'Mute', 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant', 'mute',
        '2021-04-12 12:55:18', '2021-04-12 12:55:18'),
       (2, 3, 2, 'Backflip', 'Rotation en arrière', 'backflip', '2021-04-12 19:03:00', '2021-04-12 19:03:00'),
       (5, 3, 1, 'Frontflip', 'Rotation en avant', 'frontflip', '2021-04-12 19:16:05', '2021-04-12 19:16:05'),
       (6, 2, 1, '360', 'Rotation horizontale, un tour complet', '360', '2021-04-12 19:18:11', '2021-04-12 19:18:11'),
       (7, 4, 1, 'Tail slide',
        'Le tail slide consiste à glisser sur une barre de slide, avec l\'arrière de la planche sur la barre.',
        'tail-slide', '2021-04-12 19:20:23', '2021-04-12 19:20:23'),
       (8, 2, 1, '720', 'Rotation horizontale, 2 tours', '720', '2021-04-12 19:24:02', '2021-04-12 19:24:02'),
       (9, 1, 1, 'Indy', 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
        'indy', '2021-04-12 19:25:51', '2021-04-12 19:25:51'),
       (10, 1, 1, 'Sad', 'saisie de la carre backside de la planche, entre les deux pieds, avec la main avant', 'sad',
        '2021-04-12 19:28:52', '2021-04-12 19:28:52'),
       (11, 1, 1, 'Stalefish', 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière',
        'stalefish', '2021-04-12 19:31:07', '2021-04-12 19:31:07'),
       (12, 1, 1, 'Tail grab', 'Ssaisie de la partie arrière de la planche, avec la main arrière.', 'tail-grab',
        '2021-04-12 19:32:36', '2021-04-12 19:32:36'),
       (13, 1, 1, 'Nose', 'Saisie de la partie avant de la planche, avec la main avant', 'nose', '2021-04-12 19:34:59',
        '2021-04-12 19:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `tricks_media`
--

CREATE TABLE `tricks_media`
(
    `id`             int        NOT NULL,
    `trick_id`       int        NOT NULL,
    `media_id`       int        NOT NULL,
    `is_cover_image` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `tricks_media`
--

INSERT INTO `tricks_media` (`id`, `trick_id`, `media_id`, `is_cover_image`)
VALUES (3, 2, 3, 1),
       (4, 2, 4, 0),
       (5, 2, 5, 0),
       (6, 5, 6, 1),
       (7, 5, 7, 0),
       (8, 5, 8, 0),
       (9, 6, 9, 0),
       (10, 6, 10, 1),
       (11, 7, 11, 1),
       (12, 7, 12, 0),
       (13, 8, 13, 0),
       (14, 8, 14, 1),
       (15, 9, 15, 1),
       (16, 9, 16, 0),
       (17, 9, 17, 0),
       (18, 1, 18, 0),
       (19, 1, 19, 0),
       (20, 10, 20, 1),
       (21, 10, 21, 0),
       (22, 11, 22, 0),
       (23, 11, 23, 1),
       (24, 11, 24, 0),
       (25, 12, 25, 1),
       (26, 12, 26, 0),
       (27, 12, 27, 0),
       (28, 12, 28, 0),
       (29, 13, 29, 0),
       (30, 13, 30, 1),
       (31, 13, 31, 0),
       (32, 13, 32, 0),
       (35, 1, 35, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user`
(
    `id`          int                                     NOT NULL,
    `first_name`  varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `last_name`   varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `username`    varchar(180) COLLATE utf8mb4_general_ci NOT NULL,
    `email`       varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `roles`       json                                    NOT NULL,
    `password`    varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `avatar`      varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `is_verified` tinyint(1)                              NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `username`, `email`, `roles`, `password`, `avatar`, `is_verified`)
VALUES (1, 'Admin', 'Admin', 'admin', 'admin@example.com', '[
  \"ROLE_ADMIN\"
]', '$argon2id$v=19$m=65536,t=4,p=1$QfWziQU2/O25EBKwHbDX0g$qGIo8DA/m29w+IEUjy7Tf121VGVsFKWPHH0EV4hqqpo', NULL, 1),
       (2, 'Test', 'Test', 'test', 'test@example.com', '[]',
        '$argon2id$v=19$m=65536,t=4,p=1$LkTKgFqOaioNvSqYljfVFQ$6KrVqS7IpEY4bAxtNAcVf8h6GW/Fk2mkSCARt6AxU48', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_trick`
--

CREATE TABLE `user_trick`
(
    `user_id`  int NOT NULL,
    `trick_id` int NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `user_trick`
--

INSERT INTO `user_trick` (`user_id`, `trick_id`)
VALUES (2, 1);

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
    ADD KEY `IDX_5F9E962AB281BE2E` (`trick_id`),
    ADD KEY `IDX_5F9E962AF675F31B` (`author_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Indexes for table `tricks`
--
ALTER TABLE `tricks`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_E1D902C112469DE2` (`category_id`),
    ADD KEY `IDX_E1D902C1F675F31B` (`author_id`);

--
-- Indexes for table `tricks_media`
--
ALTER TABLE `tricks_media`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_B31C9F65B281BE2E` (`trick_id`),
    ADD KEY `IDX_B31C9F65EA9FDD75` (`media_id`);

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
    ADD PRIMARY KEY (`user_id`, `trick_id`),
    ADD KEY `IDX_3A325246A76ED395` (`user_id`),
    ADD KEY `IDX_3A325246B281BE2E` (`trick_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
    MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
    MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 21;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
    MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 36;

--
-- AUTO_INCREMENT for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tricks`
--
ALTER TABLE `tricks`
    MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 27;

--
-- AUTO_INCREMENT for table `tricks_media`
--
ALTER TABLE `tricks_media`
    MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 36;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
    MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
    ADD CONSTRAINT `FK_5F9E962AB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`),
    ADD CONSTRAINT `FK_5F9E962AF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
    ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `tricks`
--
ALTER TABLE `tricks`
    ADD CONSTRAINT `FK_E1D902C112469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
    ADD CONSTRAINT `FK_E1D902C1F675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `tricks_media`
--
ALTER TABLE `tricks_media`
    ADD CONSTRAINT `FK_B31C9F65B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`),
    ADD CONSTRAINT `FK_B31C9F65EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`);

--
-- Constraints for table `user_trick`
--
ALTER TABLE `user_trick`
    ADD CONSTRAINT `FK_3A325246A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_3A325246B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;

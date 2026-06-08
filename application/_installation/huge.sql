-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Jun 2026 um 20:40
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `huge`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat_groups`
--

CREATE TABLE `chat_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `chat_groups`
--

INSERT INTO `chat_groups` (`group_id`, `group_name`, `created_by`, `created_at`) VALUES
(1, 'goooooofyyy', 1, '2026-06-08 20:38:16');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat_group_members`
--

CREATE TABLE `chat_group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `chat_group_members`
--

INSERT INTO `chat_group_members` (`group_id`, `user_id`) VALUES
(1, 1),
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `group_messages`
--

CREATE TABLE `group_messages` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `recipient_id`, `message_content`, `created_at`, `is_read`) VALUES
(1, 1, 2, 'Hallo das ist eine Testnachricht', '2026-05-28 08:09:25', 0),
(2, 2, 1, 'Hey, ich habe deine Nachricht erhalten!', '2026-05-28 08:09:36', 0),
(3, 1, 3, 'test', '2026-06-08 17:53:37', 0),
(4, 1, 2, 'hi', '2026-06-08 17:53:44', 0),
(5, 1, 2, 'ASa', '2026-06-08 18:28:10', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) UNSIGNED NOT NULL,
  `note_text` text NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user notes';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `session_id` varchar(48) DEFAULT NULL COMMENT 'stores session cookie id to prevent session concurrency',
  `user_name` varchar(64) NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) DEFAULT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(254) NOT NULL COMMENT 'user''s email, unique',
  `user_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'user''s activation status',
  `user_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'user''s deletion status',
  `user_account_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'user''s account type (basic, premium, etc)',
  `user_has_avatar` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 if user has a local avatar, 0 if not',
  `user_remember_me_token` varchar(64) DEFAULT NULL COMMENT 'user''s remember-me cookie token',
  `user_creation_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the creation of user''s account',
  `user_suspension_timestamp` bigint(20) DEFAULT NULL COMMENT 'Timestamp till the end of a user suspension',
  `user_last_login_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of user''s last login',
  `user_failed_logins` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'user''s failed login attempts',
  `user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
  `user_activation_hash` varchar(80) DEFAULT NULL COMMENT 'user''s email verification hash string',
  `user_password_reset_hash` char(80) DEFAULT NULL COMMENT 'user''s password reset code',
  `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
  `user_provider_type` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`user_id`, `session_id`, `user_name`, `user_password_hash`, `user_email`, `user_active`, `user_deleted`, `user_account_type`, `user_has_avatar`, `user_remember_me_token`, `user_creation_timestamp`, `user_suspension_timestamp`, `user_last_login_timestamp`, `user_failed_logins`, `user_last_failed_login`, `user_activation_hash`, `user_password_reset_hash`, `user_password_reset_timestamp`, `user_provider_type`) VALUES
(1, '91hrjhk3oul6kal81js55138a7', 'demo', '$2y$10$OvprunjvKOOhM1h9bzMPs.vuwGIsOqZbw88rzSyGCTJTcE61g5WXi', 'demo@demo.com', 1, 0, 7, 0, '02ade8c578a4157d8813b045d10461d515b259eaad23c91844efd5a4c1f5b3a1', 1422205178, NULL, 1780934687, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(2, NULL, 'demo2', '$2y$10$OvprunjvKOOhM1h9bzMPs.vuwGIsOqZbw88rzSyGCTJTcE61g5WXi', 'demo2@demo.com', 1, 0, 2, 0, NULL, 1422205178, NULL, 1422209189, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(3, NULL, 'dave', '$2y$10$FatWlGgSqvalFFMb03SayePy63nL2frSMvu/XiZYtSrbM23sJ78qa', 'dave.d@gmail.com', 1, 0, 2, 0, NULL, 1779953667, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(4, NULL, 'clive', '$2y$10$FYalK3qTg3keJ5vjqaYvEOo8VRkQJfHoK62NNtPLWH9ozY12V9Ete', 'clive.clive@gmail.com', 1, 0, 2, 0, NULL, 1779953713, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'DEFAULT');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_groups`
--

CREATE TABLE `user_groups` (
  `group_id` tinyint(11) NOT NULL,
  `group_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_groups`
--

INSERT INTO `user_groups` (`group_id`, `group_name`) VALUES
(1, 'Gast'),
(2, 'User'),
(7, 'Admin');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `chat_groups`
--
ALTER TABLE `chat_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indizes für die Tabelle `chat_group_members`
--
ALTER TABLE `chat_group_members`
  ADD PRIMARY KEY (`group_id`,`user_id`);

--
-- Indizes für die Tabelle `group_messages`
--
ALTER TABLE `group_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_recipient` (`recipient_id`);

--
-- Indizes für die Tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `fk_user_group` (`user_account_type`);

--
-- Indizes für die Tabelle `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `chat_groups`
--
ALTER TABLE `chat_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `group_messages`
--
ALTER TABLE `group_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index', AUTO_INCREMENT=5;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_message_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_group` FOREIGN KEY (`user_account_type`) REFERENCES `user_groups` (`group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

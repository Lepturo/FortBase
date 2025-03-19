-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 19. Mrz 2025 um 13:48
-- Server-Version: 10.5.19-MariaDB-0+deb11u2
-- PHP-Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `FortiBase`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Theme`
--

CREATE TABLE `Theme` (
  `p_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Training`
--

CREATE TABLE `Training` (
  `p_id` int(11) NOT NULL,
  `f_theme_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `trainer` varchar(250) NOT NULL,
  `startDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `endDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Training_User`
--

CREATE TABLE `Training_User` (
  `pf_training_id` int(11) NOT NULL,
  `pf_username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE `User` (
  `p_username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Theme`
--
ALTER TABLE `Theme`
  ADD PRIMARY KEY (`p_id`);

--
-- Indizes für die Tabelle `Training`
--
ALTER TABLE `Training`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `f_theme_id` (`f_theme_id`);

--
-- Indizes für die Tabelle `Training_User`
--
ALTER TABLE `Training_User`
  ADD PRIMARY KEY (`pf_training_id`,`pf_username`),
  ADD KEY `fk_username` (`pf_username`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`p_username`) USING BTREE;

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Theme`
--
ALTER TABLE `Theme`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `Training`
--
ALTER TABLE `Training`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Training`
--
ALTER TABLE `Training`
  ADD CONSTRAINT `f_theme_id` FOREIGN KEY (`f_theme_id`) REFERENCES `Theme` (`p_id`);

--
-- Constraints der Tabelle `Training_User`
--
ALTER TABLE `Training_User`
  ADD CONSTRAINT `fk_training` FOREIGN KEY (`pf_training_id`) REFERENCES `Training` (`p_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_username` FOREIGN KEY (`pf_username`) REFERENCES `User` (`p_username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

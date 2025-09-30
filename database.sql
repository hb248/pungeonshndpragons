-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 30. Sep 2025 um 17:17
-- Server-Version: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- PHP-Version: 8.3.6
-- User- und Gamedaten bereinigte Version

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `php_dbname_rndnumber`
--
CREATE DATABASE IF NOT EXISTS `php_maindb_98367` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `php_maindb_98367`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datChampion`
--

CREATE TABLE `datChampion` (
  `champID` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `hp` int(11) NOT NULL,
  `armor` int(11) NOT NULL,
  `dmg` int(11) NOT NULL,
  `hit` int(11) NOT NULL,
  `icon_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `datChampion`
--

INSERT INTO `datChampion` (`champID`, `name`, `hp`, `armor`, `dmg`, `hit`, `icon_path`) VALUES
(1, 'Der Auserwählte', 46, 22, 12, 11, '/images/champions/thechosen.png'),
(2, 'Die Verdammte', 41, 20, 16, 12, '/images/champions/thedamned.png'),
(3, 'Das Verbrechen', 36, 21, 14, 13, '/images/champions/thecrime.png'),
(24, 'Thalgrim der Stählerne', 28, 18, 7, 6, '/images/champions/thalgrim.png'),
(25, 'Eldoria die Flammenruferin', 22, 12, 9, 8, '/images/champions/eldoria.png'),
(26, 'Gorvok der Berserker', 30, 14, 10, 4, '/images/champions/gorvok.png'),
(27, 'Sylvara die Schattenklinge', 19, 10, 6, 9, '/images/champions/sylvara.png'),
(28, 'Drakthar der Drachentöter', 27, 17, 8, 5, '/images/champions/drakthar.png'),
(29, 'Malakar der Verfluchte', 25, 15, 9, 7, '/images/champions/malakar.png'),
(30, 'Zeraphine die Mondpriesterin', 23, 11, 5, 10, '/images/champions/zeraphine.png'),
(31, 'Borgrim die Eisenfaust', 31, 20, 4, 3, '/images/champions/borgrim.png'),
(32, 'Vaelith die Windläuferin', 18, 13, 7, 9, '/images/champions/vaelith.png'),
(33, 'Nargul der Knochenbrecher', 29, 16, 8, 4, '/images/champions/nargul.png'),
(34, 'Gromm Eisenhaut', 31, 20, 4, 3, '/images/champions/gromm.png'),
(35, 'Baldor das Bollwerk', 30, 19, 5, 4, '/images/champions/baldor.png'),
(36, 'Therak der Unzerstörbare', 29, 18, 4, 3, '/images/champions/therak.png'),
(37, 'Kragor der Turm', 31, 20, 3, 2, '/images/champions/kragor.png'),
(38, 'Ulfgar Schildbrecher', 28, 17, 6, 4, '/images/champions/ulfgar.png'),
(39, 'Morvok der Fels', 30, 18, 5, 3, '/images/champions/morvok.png'),
(40, 'Durim der Wachsame', 29, 16, 5, 5, '/images/champions/durim.png'),
(41, 'Ragnir der Titan', 31, 19, 4, 3, '/images/champions/ragnir.png'),
(42, 'Ogramm der Feste', 30, 20, 3, 2, '/images/champions/ogramm.png'),
(43, 'Thorgrimm der Standhafte', 29, 18, 6, 4, '/images/champions/thorgrimm.png'),
(44, 'Azralon der Feuerweber', 21, 12, 9, 9, '/images/champions/azralon.png'),
(45, 'Elria die Runenmeisterin', 20, 11, 10, 10, '/images/champions/elria.png'),
(46, 'Malakos der Schattenflüsterer', 22, 10, 9, 8, '/images/champions/malakos.png'),
(47, 'Zephyros der Sturmbändiger', 23, 12, 8, 9, '/images/champions/zephyros.png'),
(48, 'Sylphara die Frostkönigin', 21, 11, 10, 10, '/images/champions/sylphara.png'),
(49, 'Dravon der Nekromant', 20, 10, 9, 9, '/images/champions/dravon.png'),
(50, 'Valtheris der Sternenseher', 22, 12, 8, 10, '/images/champions/valtheris.png'),
(51, 'Ignarion der Feuergeist', 19, 10, 10, 10, '/images/champions/ignarion.png'),
(52, 'Kaelthas der Fluchwirker', 23, 11, 9, 9, '/images/champions/kaelthas.png'),
(53, 'Orvan der Zeitmagier', 22, 12, 8, 8, '/images/champions/orvan.png'),
(54, 'Silas Nachtklinge', 18, 13, 7, 10, '/images/champions/silas.png'),
(55, 'Vaelora die Klingenwisperin', 19, 12, 8, 9, '/images/champions/vaelora.png'),
(56, 'Ravok der Blutjäger', 17, 14, 7, 10, '/images/champions/ravok.png'),
(57, 'Xelara die Unsichtbare', 18, 11, 8, 10, '/images/champions/xelara.png'),
(58, 'Zyphor der Klingenwirbel', 19, 13, 7, 9, '/images/champions/zyphor.png'),
(59, 'Malekith der Schattenwolf', 18, 12, 7, 10, '/images/champions/malekith.png'),
(60, 'Tariq der Dolchmeister', 19, 13, 8, 9, '/images/champions/tariq.png'),
(61, 'Nyx die Nachtfalkin', 17, 11, 8, 10, '/images/champions/nyx.png'),
(62, 'Kain Blutstachel', 18, 14, 6, 10, '/images/champions/kain.png'),
(63, 'Lirien die Schattenbraut', 19, 12, 7, 9, '/images/champions/lirien.png'),
(64, 'Niemand', 11, 10, 1, 1, '/images/champions/nobody.png'),
(65, 'Die erste Legende', 11, 10, 30, 1, '/images/champions/legend1.png'),
(66, 'Die zweite Legende', 11, 28, 1, 1, '/images/champions/legend2.png'),
(67, 'Die dritte Legende', 56, 10, 1, 1, '/images/champions/legend3.png'),
(68, 'Die vierte Legende', 11, 10, 1, 22, '/images/champions/legend4.png'),
(69, 'Fluch des Auserwählten', 1, 1, 46, 3, '/images/champions/baneofthechosen.png'),
(70, 'Der Gaukler', 1, 1, 1, 1, '/images/champions/thejoker.png'),
(71, 'Nummer Zwei', 2, 2, 2, 2, '/images/champions/numbertwo.png'),
(72, 'Der Mönch', 21, 15, 5, 5, '/images/champions/themonk.png'),
(73, 'Ignaz der Bischoff', 23, 13, 8, 2, '/images/champions/ignaz.png'),
(74, 'Franz der Bauer', 13, 15, 3, 4, '/images/champions/franz.png'),
(75, 'Gerti die Müllerin', 15, 14, 2, 3, '/images/champions/gerti.png'),
(76, 'Hans Crapfon von Pürksdein', 28, 18, 7, 9, '/images/champions/hans.png'),
(77, 'Heinrich der Schmiedesohn', 21, 19, 10, 7, '/images/champions/heinrich.png'),
(78, 'Gerald von Ramsau', 30, 17, 9, 9, '/images/champions/gerald.png'),
(79, 'Sankt Attila', 3, 3, 3, 3, '/images/champions/attila.png'),
(80, 'Franz Dietrich', 22, 15, 5, 4, '/images/champions/dietrich.png'),
(81, 'Die Magd', 23, 16, 6, 6, '/images/champions/magd.png'),
(82, 'Die Lichkönigin', 34, 22, 12, 12, '/images/champions/lichking.png'),
(83, 'Wilhelm Liston', 17, 14, 3, 10, '/images/champions/wilhelm.png'),
(84, 'Eldariel Eldorian', 11, 10, 9, 10, '/images/champions/eldorian.png'),
(85, 'Plötze Shadowfax Epona', 26, 16, 8, 4, '/images/champions/fert.png'),
(86, 'Harald Polter', 5, 5, 1, 2, '/images/champions/harald.png'),
(87, 'Der Vogt', 24, 16, 7, 6, '/images/champions/vogt.png'),
(88, 'Klaus von Nikolstein', 14, 12, 6, 4, '/images/champions/klaus.png'),
(89, 'Die Maus', 27, 19, 3, 4, '/images/champions/maus.png'),
(90, 'Der Fuchs', 13, 14, 7, 7, '/images/champions/fuchs.png'),
(91, 'Die Kuh', 19, 15, 6, 5, '/images/champions/kuh.png'),
(92, 'Der Löwe', 22, 18, 8, 7, '/images/champions/loewe.png'),
(93, 'Lady Gloria', 18, 14, 5, 6, '/images/champions/gloria.png'),
(94, 'Diwisch der Barde', 18, 14, 4, 5, '/images/champions/diwisch.png'),
(95, 'Ulfrich der Hänker', 13, 12, 8, 5, '/images/champions/ulfrich.png'),
(96, 'Vossl der Krämer', 16, 13, 3, 4, '/images/champions/vossl.png'),
(97, 'Freiknecht Fridolin', 15, 14, 4, 7, '/images/champions/fridolin.png'),
(98, 'Abdecker Kurti', 17, 12, 3, 6, '/images/champions/kurti.png'),
(99, 'Karl der Wirt', 22, 13, 4, 3, '/images/champions/karl.png'),
(100, 'Sir Uli von Unteroberndorf', 21, 16, 5, 7, '/images/champions/uli.png'),
(101, 'Fischer Vinzenz', 21, 11, 2, 6, '/images/champions/vinzenz.png'),
(102, 'Bettler Mönch', 24, 15, 6, 4, '/images/champions/bettler.png'),
(103, 'Garg der Söldner', 18, 14, 5, 6, '/images/champions/soeldner.png'),
(104, 'Der Kämmerer', 13, 12, 4, 3, '/images/champions/kaemmerer.png'),
(105, 'Jarl Brøknröc', 19, 16, 8, 6, '/images/champions/jarl.png'),
(106, 'Trunkenbold Willy', 22, 11, 9, 3, '/images/champions/willy.png'),
(107, 'Margaret von Rattei', 20, 17, 6, 5, '/images/champions/margaret.png'),
(108, 'Polly das Hausschwein', 15, 10, 4, 6, '/images/champions/polly.png'),
(109, 'Seneschall Benedictio', 21, 15, 8, 8, '/images/champions/benedictio.png'),
(110, 'Bruder Clesgine', 17, 13, 6, 5, '/images/champions/clesgine.png'),
(111, 'Großmeister Francesco', 16, 11, 10, 7, '/images/champions/francesco.png'),
(164, 'Kunesch der Unsterbliche', 62, 10, 4, 6, '/images/champions/kunesch.png'),
(165, 'Graf Duckular', 28, 15, 7, 7, '/images/champions/duckular.png'),
(166, 'Der letzte Samurai', 24, 17, 8, 9, '/images/champions/samurai.png'),
(167, 'Pfarrer Clemens', 20, 12, 4, 6, '/images/champions/clemens.png'),
(168, 'Die (H)Exe', 26, 14, 10, 9, '/images/champions/hexe.png'),
(169, 'Der Majordomus', 31, 15, 5, 8, '/images/champions/majordomus.png'),
(170, 'Professor für Göttlichkeit', 42, 20, 4, 7, '/images/champions/professor.png'),
(171, 'Seppl der Tollpatsch', 9, 14, 9, 3, '/images/champions/seppl.png'),
(172, 'Mücke', 31, 20, 10, 10, '/images/champions/muecke.png'),
(173, 'Jemand', 30, 19, 9, 9, '/images/champions/jemand.png'),
(174, 'Elfenkönigin Elaine', 28, 19, 7, 10, '/images/champions/elaine.png'),
(175, 'Der Verlassene', 27, 13, 9, 7, '/images/champions/verlassene.png'),
(176, 'Die Schönheit', 14, 12, 2, 4, '/images/champions/schoenheit.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datGame`
--

CREATE TABLE `datGame` (
  `gameID` int(11) NOT NULL,
  `hostID` int(11) NOT NULL,
  `oppoID` int(11) DEFAULT NULL,
  `step` int(11) DEFAULT 1,
  `winnerID` int(11) DEFAULT NULL,
  `status` enum('waiting','ongoing','finished') NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datGameChampions`
--

CREATE TABLE `datGameChampions` (
  `gameChampID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `playerID` int(11) NOT NULL,
  `champID` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `hasAttacked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datLog`
--

CREATE TABLE `datLog` (
  `logID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datMessages`
--

CREATE TABLE `datMessages` (
  `messID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `playerID` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datPlayer`
--

CREATE TABLE `datPlayer` (
  `playerID` int(11) NOT NULL,
  `name` varchar(13) NOT NULL,
  `pw_hash` varchar(255) NOT NULL,
  `win` int(11) DEFAULT 0,
  `lose` int(11) DEFAULT 0,
  `rating` int(11) DEFAULT 1000,
  `dmgdealt` int(11) DEFAULT 0,
  `dmgtaken` int(11) DEFAULT 0,
  `evade` int(11) DEFAULT 0,
  `miss` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datSplash`
--

CREATE TABLE `datSplash` (
  `splashID` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `type` enum('hub','dyn') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `datSplash`
--

INSERT INTO `datSplash` (`splashID`, `text`, `type`) VALUES
(1, 'Los geht’s', 'hub'),
(2, 'Heute ist dein Tag', 'hub'),
(3, 'Mach dich bereit für den nächsten Kampf', 'hub'),
(4, 'Dein Abenteuer beginnt jetzt', 'hub'),
(5, 'Jede Schlacht bringt dich weiter', 'hub'),
(6, 'Gib dein Bestes und dominiere das Spiel', 'hub'),
(7, 'Der Weg zum Sieg beginnt mit dem ersten Schritt', 'hub'),
(8, 'Setze dein Können ein und zeig, was du drauf hast', 'hub'),
(9, 'Strategie schlägt Stärke – sei schlau', 'hub'),
(10, 'Deine Legende beginnt genau jetzt', 'hub'),
(11, 'Du kannst es schaffen', 'dyn'),
(12, 'Bleib fokussiert und greif an', 'dyn'),
(13, 'Setze den entscheidenden Schlag', 'dyn'),
(14, 'Lass dich nicht unterkriegen', 'dyn'),
(15, 'Jeder Treffer zählt – mach ihn perfekt', 'dyn'),
(16, 'Ziele genau und sei präzise', 'dyn'),
(17, 'Nur noch ein bisschen – bleib dran', 'dyn'),
(18, 'Die Schlacht ist noch nicht vorbei', 'dyn'),
(19, 'Ein Champion gibt niemals auf', 'dyn'),
(20, 'Nutze deine Stärken und dominiere den Kampf', 'dyn');

-- --------------------------------------------------------

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `datChampion`
--
ALTER TABLE `datChampion`
  ADD PRIMARY KEY (`champID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `datGame`
--
ALTER TABLE `datGame`
  ADD PRIMARY KEY (`gameID`),
  ADD KEY `winnerID` (`winnerID`),
  ADD KEY `idx_game_host` (`hostID`),
  ADD KEY `idx_game_oppo` (`oppoID`),
  ADD KEY `idx_game_step` (`step`);

--
-- Indizes für die Tabelle `datGameChampions`
--
ALTER TABLE `datGameChampions`
  ADD PRIMARY KEY (`gameChampID`),
  ADD KEY `playerID` (`playerID`),
  ADD KEY `champID` (`champID`),
  ADD KEY `idx_gamechamp_game` (`gameID`);

--
-- Indizes für die Tabelle `datLog`
--
ALTER TABLE `datLog`
  ADD PRIMARY KEY (`logID`),
  ADD KEY `idx_log_game` (`gameID`);

--
-- Indizes für die Tabelle `datMessages`
--
ALTER TABLE `datMessages`
  ADD PRIMARY KEY (`messID`),
  ADD KEY `idx_message_game` (`gameID`),
  ADD KEY `fk_datMessages_playerID` (`playerID`);

--
-- Indizes für die Tabelle `datPlayer`
--
ALTER TABLE `datPlayer`
  ADD PRIMARY KEY (`playerID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `datSplash`
--
ALTER TABLE `datSplash`
  ADD PRIMARY KEY (`splashID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `datChampion`
--
ALTER TABLE `datChampion`
  MODIFY `champID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT für Tabelle `datGame`
--
ALTER TABLE `datGame`
  MODIFY `gameID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT für Tabelle `datGameChampions`
--
ALTER TABLE `datGameChampions`
  MODIFY `gameChampID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT für Tabelle `datLog`
--
ALTER TABLE `datLog`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1747;

--
-- AUTO_INCREMENT für Tabelle `datMessages`
--
ALTER TABLE `datMessages`
  MODIFY `messID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT für Tabelle `datPlayer`
--
ALTER TABLE `datPlayer`
  MODIFY `playerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `datSplash`
--
ALTER TABLE `datSplash`
  MODIFY `splashID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `datGame`
--
ALTER TABLE `datGame`
  ADD CONSTRAINT `datGame_ibfk_1` FOREIGN KEY (`hostID`) REFERENCES `datPlayer` (`playerID`),
  ADD CONSTRAINT `datGame_ibfk_2` FOREIGN KEY (`oppoID`) REFERENCES `datPlayer` (`playerID`),
  ADD CONSTRAINT `datGame_ibfk_3` FOREIGN KEY (`winnerID`) REFERENCES `datPlayer` (`playerID`);

--
-- Constraints der Tabelle `datGameChampions`
--
ALTER TABLE `datGameChampions`
  ADD CONSTRAINT `datGameChampions_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `datGame` (`gameID`),
  ADD CONSTRAINT `datGameChampions_ibfk_2` FOREIGN KEY (`playerID`) REFERENCES `datPlayer` (`playerID`),
  ADD CONSTRAINT `datGameChampions_ibfk_3` FOREIGN KEY (`champID`) REFERENCES `datChampion` (`champID`);

--
-- Constraints der Tabelle `datLog`
--
ALTER TABLE `datLog`
  ADD CONSTRAINT `datLog_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `datGame` (`gameID`);

--
-- Constraints der Tabelle `datMessages`
--
ALTER TABLE `datMessages`
  ADD CONSTRAINT `datMessages_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `datGame` (`gameID`),
  ADD CONSTRAINT `fk_datMessages_gameID` FOREIGN KEY (`gameID`) REFERENCES `datGame` (`gameID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_datMessages_playerID` FOREIGN KEY (`playerID`) REFERENCES `datPlayer` (`playerID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

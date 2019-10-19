-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 28. Apr 2019 um 21:11
-- Server-Version: 10.1.38-MariaDB
-- PHP-Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `pizzaservice`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Angebot`
--

CREATE TABLE `Angebot` (
  `PizzaNummer` int(11) NOT NULL,
  `PizzaName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Bilddatei` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Preis` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `Angebot`
--

INSERT INTO `Angebot` (`PizzaNummer`, `PizzaName`, `Bilddatei`, `Preis`) VALUES
(1, 'Pizza Hühnchen', 'pizzaimage.jpeg', 3.17),
(2, 'Pizza Salami', 'pizzaimage.jpeg', 3.7),
(3, 'Pizza Margherita', 'pizzaimage.jpeg', 2.99),
(4, 'Pizza Hawaii', 'pizzaimage.jpeg', 3.5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `BestelltePizza`
--

CREATE TABLE `BestelltePizza` (
  `PizzaID` int(11) NOT NULL,
  `fBestellungID` int(11) NOT NULL,
  `fPizzaNummer` int(11) NOT NULL,
  `Status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'bestellt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `BestelltePizza`
--

INSERT INTO `BestelltePizza` (`PizzaID`, `fBestellungID`, `fPizzaNummer`, `Status`) VALUES
(7, 3, 2, 'bestellt'),
(8, 3, 3, 'bestellt'),
(9, 3, 4, 'bestellt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Bestellung`
--

CREATE TABLE `Bestellung` (
  `BestellungID` int(11) NOT NULL,
  `Adresse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Bestellzeitpunkt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `Bestellung`
--

INSERT INTO `Bestellung` (`BestellungID`, `Adresse`, `Bestellzeitpunkt`) VALUES
(3, 'HirschhÃ¶rnerweg 22', '2019-04-28 18:55:37');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Angebot`
--
ALTER TABLE `Angebot`
  ADD PRIMARY KEY (`PizzaNummer`);

--
-- Indizes für die Tabelle `BestelltePizza`
--
ALTER TABLE `BestelltePizza`
  ADD PRIMARY KEY (`PizzaID`);

--
-- Indizes für die Tabelle `Bestellung`
--
ALTER TABLE `Bestellung`
  ADD PRIMARY KEY (`BestellungID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Angebot`
--
ALTER TABLE `Angebot`
  MODIFY `PizzaNummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `BestelltePizza`
--
ALTER TABLE `BestelltePizza`
  MODIFY `PizzaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `Bestellung`
--
ALTER TABLE `Bestellung`
  MODIFY `BestellungID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

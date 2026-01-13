-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2023 at 06:46 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fryzjer`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `godzinypracy`
--

CREATE TABLE `godzinypracy` (
  `ID` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Od` time NOT NULL,
  `Do` time NOT NULL,
  `IDPracownika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `godzinypracy`
--

INSERT INTO `godzinypracy` (`ID`, `Data`, `Od`, `Do`, `IDPracownika`) VALUES
(1, '2024-02-14', '08:00:00', '16:00:00', 3),
(2, '2024-02-14', '08:00:00', '12:00:00', 2),
(3, '2024-02-15', '10:00:00', '14:00:00', 2),
(4, '2024-02-15', '08:00:00', '16:00:00', 3),
(5, '2024-02-13', '08:00:00', '15:00:00', 3),
(8, '2023-12-12', '08:00:00', '16:00:00', 2),
(9, '2023-12-12', '08:00:00', '16:00:00', 3),
(10, '2023-12-13', '08:00:00', '16:00:00', 2),
(11, '2023-12-13', '08:00:00', '16:00:00', 3),
(12, '2023-12-14', '08:00:00', '16:00:00', 2),
(13, '2023-12-14', '08:00:00', '16:00:00', 3),
(14, '2023-12-15', '08:00:00', '16:00:00', 2),
(15, '2023-12-15', '08:00:00', '16:00:00', 3),
(16, '2024-02-12', '08:00:00', '15:00:00', 2),
(17, '2024-02-12', '08:00:00', '15:00:00', 3),
(18, '2024-02-13', '08:00:00', '15:00:00', 2),
(19, '2023-11-13', '00:00:00', '00:00:00', 2),
(20, '2023-11-13', '00:00:00', '00:00:00', 3),
(21, '2023-12-11', '08:00:00', '16:00:00', 2),
(22, '2023-12-11', '08:00:00', '16:00:00', 3),
(23, '2023-11-25', '08:00:00', '19:04:00', 2),
(24, '2023-11-24', '08:00:00', '16:00:00', 3),
(29, '2024-01-08', '08:00:00', '16:00:00', 2),
(30, '2024-01-08', '08:00:00', '16:00:00', 3),
(31, '2024-01-09', '08:00:00', '16:00:00', 2),
(32, '2024-01-09', '08:00:00', '16:00:00', 3),
(33, '2024-01-10', '08:00:00', '16:00:00', 2),
(34, '2024-01-10', '08:00:00', '16:00:00', 3),
(35, '2024-01-11', '08:00:00', '16:00:00', 2),
(36, '2024-01-11', '08:00:00', '16:00:00', 3),
(37, '2024-01-12', '08:00:00', '16:00:00', 2),
(38, '2024-01-12', '08:00:00', '16:00:00', 3),
(39, '2024-01-15', '08:00:00', '16:00:00', 2),
(40, '2024-01-15', '08:00:00', '16:00:00', 3),
(41, '2024-01-16', '08:00:00', '16:00:00', 2),
(42, '2024-01-16', '08:00:00', '16:00:00', 3),
(43, '2024-01-17', '08:00:00', '16:00:00', 2),
(44, '2024-01-17', '08:00:00', '16:00:00', 3),
(45, '2024-01-18', '08:00:00', '16:00:00', 2),
(46, '2024-01-18', '08:00:00', '16:00:00', 3),
(47, '2024-01-19', '08:00:00', '16:00:00', 2),
(48, '2024-01-19', '08:00:00', '16:00:00', 3),
(49, '2024-01-22', '08:00:00', '16:00:00', 2),
(50, '2024-01-22', '08:00:00', '16:00:00', 3),
(51, '2024-01-23', '08:00:00', '16:00:00', 2),
(52, '2024-01-23', '08:00:00', '16:00:00', 3),
(53, '2024-01-24', '08:00:00', '16:00:00', 2),
(54, '2024-01-24', '08:00:00', '16:00:00', 3),
(55, '2024-01-25', '08:00:00', '16:00:00', 2),
(56, '2024-01-25', '08:00:00', '16:00:00', 3),
(57, '2024-01-26', '08:00:00', '16:00:00', 2),
(58, '2024-01-26', '08:00:00', '16:00:00', 3),
(59, '2024-01-29', '08:00:00', '16:00:00', 2),
(60, '2024-01-29', '08:00:00', '16:00:00', 3),
(61, '2024-01-30', '08:00:00', '16:00:00', 2),
(62, '2024-01-30', '08:00:00', '16:00:00', 3),
(63, '2024-01-31', '08:00:00', '16:00:00', 2),
(64, '2024-01-31', '08:00:00', '16:00:00', 3),
(65, '2024-01-02', '08:00:00', '16:00:00', 2),
(66, '2024-01-02', '08:00:00', '16:00:00', 3),
(67, '2024-01-03', '08:00:00', '16:00:00', 2),
(68, '2024-01-03', '08:00:00', '16:00:00', 3),
(69, '2024-01-04', '08:00:00', '16:00:00', 2),
(70, '2024-01-04', '08:00:00', '16:00:00', 3),
(71, '2024-01-05', '08:00:00', '16:00:00', 2),
(72, '2024-01-05', '08:00:00', '16:00:00', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacja`
--

CREATE TABLE `rezerwacja` (
  `ID` int(11) NOT NULL,
  `Pracownik` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Godzina` time NOT NULL,
  `Klient` int(11) NOT NULL,
  `Usluga` int(11) NOT NULL,
  `Potwierdzona` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `rezerwacja`
--

INSERT INTO `rezerwacja` (`ID`, `Pracownik`, `Data`, `Godzina`, `Klient`, `Usluga`, `Potwierdzona`) VALUES
(6, 2, '2024-02-15', '10:00:00', 4, 4, 1),
(30, 3, '2023-12-15', '09:20:00', 1, 1, 0),
(31, 3, '2023-12-14', '09:20:00', 1, 1, 1),
(45, 3, '2023-12-11', '08:20:00', 4, 1, 1),
(47, 3, '2023-12-15', '08:20:00', 2, 1, 1),
(48, 3, '2023-12-15', '08:40:00', 4, 1, 1),
(49, 3, '2023-12-15', '09:00:00', 1, 1, 0),
(50, 2, '2023-12-15', '09:20:00', 1, 17, 1),
(52, 3, '2023-12-15', '09:40:00', 22, 2, 1),
(53, 2, '2023-12-15', '09:40:00', 4, 1, 1),
(54, 2, '2024-01-02', '08:00:00', 4, 1, 1),
(55, 2, '2024-01-02', '08:20:00', 4, 1, 1),
(56, 2, '2024-01-02', '08:40:00', 4, 1, 1),
(57, 2, '2024-01-02', '09:00:00', 4, 1, 1),
(58, 2, '2023-12-15', '08:00:00', 22, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uslugi`
--

CREATE TABLE `uslugi` (
  `ID` int(11) NOT NULL,
  `NazwaUslugi` varchar(50) NOT NULL,
  `CzasTrwania` time NOT NULL,
  `Koszt` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `uslugi`
--

INSERT INTO `uslugi` (`ID`, `NazwaUslugi`, `CzasTrwania`, `Koszt`) VALUES
(1, 'Strzyżenie męskie klasyczne włosów', '00:20:00', 50.00),
(2, 'Strzyżenie i pielęgnacja brody', '00:20:00', 35.00),
(3, 'Strzyżenie włosów i brody', '00:40:00', 80.00),
(4, 'Koloryzacja włosów', '01:00:00', 150.00),
(17, 'Strzyżenie męskie maszynką', '00:20:00', 40.00),
(18, 'Modelowanie włosów', '00:20:00', 15.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `ID` int(11) NOT NULL,
  `Imie` varchar(25) NOT NULL,
  `Nazwisko` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Haslo` varchar(25) DEFAULT NULL,
  `Telefon` varchar(12) NOT NULL,
  `Adres` varchar(50) DEFAULT NULL,
  `KodPocztowy` varchar(6) DEFAULT NULL,
  `Miejscowosc` varchar(60) DEFAULT NULL,
  `Pesel` varchar(11) DEFAULT NULL,
  `Rola` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`ID`, `Imie`, `Nazwisko`, `Email`, `Haslo`, `Telefon`, `Adres`, `KodPocztowy`, `Miejscowosc`, `Pesel`, `Rola`) VALUES
(1, 'Janusz', 'Paleta', 'jpaleta@poczta.pl', '1234', '123456789', 'Królewiecka 25/12', '09-400', 'Płock', '12345678910', 0),
(2, 'Alicja', 'Dzwonek', 'adzwonek@poczta.pl', '1234', '+48156346565', 'Sezamkowa 14/21', '09-400', 'Płock', '12346565949', 1),
(3, 'Bartłomiej', 'Górzyński', 'bgorzynski@poczta.pl', '1234', '+48654561633', 'Miodowa 9/3', '09-400', 'Płock', '65498456131', 1),
(4, 'Marek', 'Marucha', 'mmarucha@poczta.pl', '1234', '+48549964616', NULL, NULL, NULL, NULL, 2),
(22, 'Andrzej', 'Ciesielski', 'aciesielski@poczta.pl', '1234', '986534721', NULL, NULL, NULL, NULL, 2),
(23, 'A', 'B', 'a@a.pl', '1234', '55', NULL, NULL, NULL, NULL, 2);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `godzinypracy`
--
ALTER TABLE `godzinypracy`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDPracownika` (`IDPracownika`);

--
-- Indeksy dla tabeli `rezerwacja`
--
ALTER TABLE `rezerwacja`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Pracownik` (`Pracownik`,`Klient`,`Usluga`),
  ADD KEY `Usluga` (`Usluga`),
  ADD KEY `Klient` (`Klient`);

--
-- Indeksy dla tabeli `uslugi`
--
ALTER TABLE `uslugi`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `godzinypracy`
--
ALTER TABLE `godzinypracy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `rezerwacja`
--
ALTER TABLE `rezerwacja`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `uslugi`
--
ALTER TABLE `uslugi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `godzinypracy`
--
ALTER TABLE `godzinypracy`
  ADD CONSTRAINT `godzinypracy_ibfk_1` FOREIGN KEY (`IDPracownika`) REFERENCES `uzytkownicy` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `rezerwacja`
--
ALTER TABLE `rezerwacja`
  ADD CONSTRAINT `rezerwacja_ibfk_1` FOREIGN KEY (`Pracownik`) REFERENCES `uzytkownicy` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `rezerwacja_ibfk_3` FOREIGN KEY (`Usluga`) REFERENCES `uslugi` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `rezerwacja_ibfk_4` FOREIGN KEY (`Klient`) REFERENCES `uzytkownicy` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

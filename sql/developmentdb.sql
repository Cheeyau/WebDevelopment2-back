-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jun 19, 2023 at 11:17 AM
-- Server version: 11.0.2-MariaDB-1:11.0.2+maria~ubu2204
-- PHP Version: 8.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Jwt_token`
--

CREATE TABLE `Jwt_token` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE `Order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Order`
--

INSERT INTO `Order` (`id`, `user_id`, `transaction_id`, `created`, `status`) VALUES
(1, 1, 1, '2021-03-20 15:05:00', 'Shipped'),
(2, 1, 2, '2021-03-21 15:05:00', 'Pending'),
(3, 2, 3, '2021-03-21 15:05:00', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `Order_detail`
--

CREATE TABLE `Order_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Order_detail`
--

INSERT INTO `Order_detail` (`id`, `order_id`, `product_id`, `amount`) VALUES
(1, 1, 1, 2),
(2, 1, 3, 1),
(3, 1, 4, 2),
(4, 2, 1, 5),
(5, 2, 5, 10),
(6, 3, 2, 2),
(7, 3, 3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `id` int(11) NOT NULL,
  `price` float NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`id`, `price`, `name`, `image`, `description`) VALUES
(1, 30, 'Zadel', 'leren-zadel', 'Heb je ook wel eens last van schurende benen of een harde zit na een lange rit? Zit je zadel weer vol water of ben je constant aan het zweten? Daar heeft Deso het Comfortabel Gel Fietszadel op bedacht! Hiermee ben je verzekerd van een lekkere zachte en brede zit. Bij dit model draait het allemaal om comfortabel fietsen, daarom kun jij met dit zadel voortaan altijd de fiets pakken!'),
(2, 200, 'Oma fiets', 'grote-oma-fiets', 'Deze prachtige 28 inch omafiets heeft een framemaat van 57 cm. Het stuur en de zadel zijn in hoogte verstelbaar. De fiets is voorzien van dubbelwandige aluminium velgen, een bagagedrager en een terugtraprem. De kettingkast is gesloten met een lakdoek. Inclusief jasbeschermers, zijstandaard en bel. De fiets is voorzien van LED-verlichting (d.m.v. batterij). '),
(3, 40, 'Fiets stuur groot', 'fiets-stuur-klein', 'Zeer comfortabel trekking stuur met een 25,4 mm stuurpenklemdiameter. Specificaties: Stuurklem diameter: 25,4 mm Breedte: 550 mm Dikte: 1,5 mm Handvatlengte: 140 mm Verhoging: 72 mm Terugliggend: 68° Materiaal: Staal Veiligheidslevel: 3 Gewicht: 615 gram '),
(4, 15, 'Fiets bel klein', 'fiets-bel-groot', 'Met onze fietsbel kunt u in een paar seconden uw fiets of e-step opsporen dankzij de Apple airtag. Die u makkelijk kunt verbergen in de fietsbel. Niemand zal het merken dat uw fiets/ e-step getrackt kan worden. Het monteren van de fietsbel doet u in 1 minuut. Met onze meegeleverde schroevendraaier. U moet zich ook geen zorgen maken om uw Airtag omdat onze fietsbel waterdicht is. Dus fietsen in de regen is geen enkel probleem.'),
(5, 25, 'Fiets ketting groot', 'fiets-ketting', 'Corrosiebestendige coating voor een langere levensduur Productspecificaties: Groep: NEXUS Model: CN-NX10 Type: 1/2 \"x 1/8\" Versnellingen voor: 1-speed Versnellingen achter: 1-speed SIL-TEC-coating: Nee. Toepassingsgebied: Stad / Comfort Gewicht: ca. 364 g (114 schakels) Verbinding: kettingpen.');

-- --------------------------------------------------------

--
-- Table structure for table `Transaction`
--

CREATE TABLE `Transaction` (
  `id` int(11) NOT NULL,
  `total` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Transaction`
--

INSERT INTO `Transaction` (`id`, `total`, `user_id`, `created`, `status`) VALUES
(1, 130, 1, '2021-03-20 15:05:00', 'Completed'),
(2, 400, 1, '2021-03-21 15:05:00', 'Refunded'),
(3, 680, 2, '2021-03-20 15:05:00', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` char(128) NOT NULL,
  `user_roll` int(11) NOT NULL,
  `registration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `name`, `email_address`, `password`, `user_roll`, `registration`) VALUES
(1, 'Bob', 'bob@mail.com', '$2y$10$Zmo87y9iVspKhB7uUgjfhOLZvZ0HEeG3VzHNNKwpPcqrOTlgbQdgu', 1, '2021-03-20 15:00:00'),
(2, 'James', 'james@mail.com', '$2y$10$Zmo87y9iVspKhB7uUgjfhOLZvZ0HEeG3VzHNNKwpPcqrOTlgbQdgu', 2, '2021-04-20 15:00:00'),
(3, 'Elon', 'Elon@mail.com', '$2y$10$Zmo87y9iVspKhB7uUgjfhOLZvZ0HEeG3VzHNNKwpPcqrOTlgbQdgu', 0, '2021-04-20 15:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Jwt_token`
--
ALTER TABLE `Jwt_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Order`
--
ALTER TABLE `Order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Jwt_token`
--
ALTER TABLE `Jwt_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Order`
--
ALTER TABLE `Order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Order_detail`
--
ALTER TABLE `Order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Transaction`
--
ALTER TABLE `Transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

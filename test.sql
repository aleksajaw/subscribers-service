-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 16 Cze 2023, 21:48
-- Wersja serwera: 10.4.18-MariaDB
-- Wersja PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `test`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `audit_subscribers`
--

CREATE TABLE `audit_subscribers` (
  `action_id` int(11) NOT NULL,
  `subscriber_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `action_performed` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `audit_subscribers`
--

INSERT INTO `audit_subscribers` (`action_id`, `subscriber_name`, `action_performed`, `date_added`) VALUES
(522, 'aaaaaaaaaaaaaaaaa', 'Deleted a subscriber', '2023-06-16 18:36:25'),
(523, 'dfgdfgf', 'Insert a new subscriber', '2023-06-16 18:37:41'),
(524, 'dfgdfgf', 'Deleted a subscriber', '2023-06-16 18:41:06'),
(525, 'hdfghdfg', 'Insert a new subscriber', '2023-06-16 18:41:16'),
(526, 'hdfghdfg', 'Updated a subscriber', '2023-06-16 18:53:57'),
(527, 'hdfghdfg', 'Deleted a subscriber', '2023-06-16 18:56:29'),
(528, 'fghdfg', 'Insert a new subscriber', '2023-06-16 18:57:01'),
(529, 'fghdfg', 'Deleted a subscriber', '2023-06-16 19:16:01'),
(530, 'dyudfg', 'Insert a new subscriber', '2023-06-16 19:16:12'),
(531, 'fgbfghf', 'Insert a new subscriber', '2023-06-16 19:16:42'),
(532, 'fgbfghf', 'Updated a subscriber', '2023-06-16 19:16:48'),
(533, 'fgbfghf', 'Updated a subscriber', '2023-06-16 19:16:55'),
(534, 'fgbfghf', 'Updated a subscriber', '2023-06-16 19:17:20');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subscribers`
--

CREATE TABLE `subscribers` (
  `number` int(11) NOT NULL,
  `fname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `subscribers`
--

INSERT INTO `subscribers` (`number`, `fname`, `email`) VALUES
(6, 'dyudfg', 'hdfghgfhdgf'),
(7, 'fgbfghf', 'fghfghfghffghfghkhjkhjklhljkl ghjghjjkjh');

--
-- Wyzwalacze `subscribers`
--
DELIMITER $$
CREATE TRIGGER `after_subscriber_delete` AFTER DELETE ON `subscribers` FOR EACH ROW BEGIN
    INSERT INTO audit_subscribers(
        subscriber_name,
        action_performed
    )
VALUES(
    OLD.fname,
    'Deleted a subscriber'
) ;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_subscriber_edit` AFTER UPDATE ON `subscribers` FOR EACH ROW BEGIN
    INSERT INTO audit_subscribers(subscriber_name, action_performed)
VALUES(
    NEW.fname,
    'Updated a subscriber'
) ;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_subscriber_insert` BEFORE INSERT ON `subscribers` FOR EACH ROW BEGIN
    INSERT INTO audit_subscribers (subscriber_name, action_performed)
    VALUES (NEW.fname, 'Insert a new subscriber');
END
$$
DELIMITER ;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `audit_subscribers`
--
ALTER TABLE `audit_subscribers`
  ADD PRIMARY KEY (`action_id`);

--
-- Indeksy dla tabeli `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`number`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `audit_subscribers`
--
ALTER TABLE `audit_subscribers`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=535;

--
-- AUTO_INCREMENT dla tabeli `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

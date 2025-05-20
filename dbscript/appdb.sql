-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 25 2025 г., 03:38
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `appdb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `client`
--

CREATE TABLE `client` (
  `ID_client` int(4) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Tel` varchar(11) NOT NULL,
  `Email` varchar(64) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `Data_BD` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `client`
--

INSERT INTO `client` (`ID_client`, `Name`, `Tel`, `Email`, `Password`, `Data_BD`) VALUES
(1, 'Мария', '+7894561230', 'Maria@gmail.com', '123', '2005-03-19');

-- --------------------------------------------------------

--
-- Структура таблицы `element`
--

CREATE TABLE `element` (
  `ID_Element` int(4) NOT NULL,
  `ID_Product` int(4) NOT NULL,
  `ID_Order` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `ID_Order` int(4) NOT NULL,
  `ID_client` int(4) NOT NULL,
  `ID_Staff` int(4) NOT NULL,
  `Adress` varchar(6) NOT NULL,
  `Payment` tinyint(1) NOT NULL,
  `Sum` double NOT NULL,
  `Data_Order` date NOT NULL,
  `Status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `ID_Product` int(4) NOT NULL,
  `Produser` varchar(64) NOT NULL,
  `Name_Product` varchar(64) NOT NULL,
  `Data_End` date NOT NULL,
  `Price` double NOT NULL,
  `weight` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `staff`
--

CREATE TABLE `staff` (
  `ID_Staff` int(4) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Tel_Staff` varchar(11) NOT NULL,
  `title` tinyint(1) NOT NULL,
  `Password_Staff` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ID_client`);

--
-- Индексы таблицы `element`
--
ALTER TABLE `element`
  ADD PRIMARY KEY (`ID_Element`),
  ADD KEY `ID_Order` (`ID_Order`),
  ADD KEY `ID_Product` (`ID_Product`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID_Order`),
  ADD KEY `ID_client` (`ID_client`),
  ADD KEY `ID_Staff` (`ID_Staff`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ID_Product`);

--
-- Индексы таблицы `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`ID_Staff`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `client`
--
ALTER TABLE `client`
  MODIFY `ID_client` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `element`
--
ALTER TABLE `element`
  MODIFY `ID_Element` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `ID_Order` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `ID_Product` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `staff`
--
ALTER TABLE `staff`
  MODIFY `ID_Staff` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `element`
--
ALTER TABLE `element`
  ADD CONSTRAINT `element_ibfk_1` FOREIGN KEY (`ID_Order`) REFERENCES `orders` (`ID_Order`) ON DELETE CASCADE,
  ADD CONSTRAINT `element_ibfk_2` FOREIGN KEY (`ID_Product`) REFERENCES `product` (`ID_Product`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ID_client`) REFERENCES `client` (`ID_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`ID_Staff`) REFERENCES `staff` (`ID_Staff`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

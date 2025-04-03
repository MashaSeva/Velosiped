-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3307
-- Время создания: Апр 03 2025 г., 15:48
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
-- База данных: `велосипед`
--

-- --------------------------------------------------------

--
-- Структура таблицы `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_bd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `client`
--

INSERT INTO `client` (`id_client`, `name`, `tel`, `email`, `password`, `data_bd`) VALUES
(1, 'Никита', '+79451024779', 'nikk@gmail.com', '123', '2000-02-03'),
(2, 'Виктория', '+7894561230', 'Vikiki@mail.ru', '789', '2005-12-12'),
(3, 'Марина', '+7589632140', 'Maria@gmail.com', '456', '2001-05-11');

-- --------------------------------------------------------

--
-- Структура таблицы `element`
--

CREATE TABLE `element` (
  `ID_Element` int(11) NOT NULL,
  `ID_Product` int(11) NOT NULL,
  `ID_Order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `element`
--

INSERT INTO `element` (`ID_Element`, `ID_Product`, `ID_Order`) VALUES
(4, 1, 4),
(5, 3, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `ID_Order` int(11) NOT NULL,
  `ID_client` int(11) NOT NULL,
  `ID_Staff` int(11) NOT NULL,
  `Adress` varchar(255) NOT NULL,
  `Payment` tinyint(1) NOT NULL,
  `Sum` decimal(10,2) NOT NULL,
  `Data_Order` date NOT NULL,
  `Status` enum('сформирован','в пути','доставлен') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`ID_Order`, `ID_client`, `ID_Staff`, `Adress`, `Payment`, `Sum`, `Data_Order`, `Status`) VALUES
(4, 1, 1, 'Кемерово', 1, 100.00, '0000-00-00', 'в пути'),
(5, 2, 1, 'Кемерово', 2, 99.20, '0000-00-00', 'доставлен');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `ID_Product` int(11) NOT NULL,
  `Produser` varchar(255) NOT NULL,
  `Name_Product` varchar(255) NOT NULL,
  `Data_End` date NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `weight` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`ID_Product`, `Produser`, `Name_Product`, `Data_End`, `Price`, `weight`) VALUES
(1, 'ООО\"Село\"', 'Молоко 2,5%', '2025-04-30', 100.22, 1.000),
(3, 'ООО\"СельхозМарт\"', 'Хлеб', '2025-04-15', 90.20, 0.400);

-- --------------------------------------------------------

--
-- Структура таблицы `staff`
--

CREATE TABLE `staff` (
  `ID_Staff` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Tel_Staff` varchar(15) NOT NULL,
  `Title` enum('','') NOT NULL,
  `Password_Staff` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `staff`
--

INSERT INTO `staff` (`ID_Staff`, `Name`, `Tel_Staff`, `Title`, `Password_Staff`) VALUES
(1, 'Мария', '+7145201441', '', '444');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- Индексы таблицы `element`
--
ALTER TABLE `element`
  ADD PRIMARY KEY (`ID_Element`),
  ADD KEY `ID_Product` (`ID_Product`),
  ADD KEY `ID_Order` (`ID_Order`);

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
  ADD PRIMARY KEY (`ID_Staff`),
  ADD UNIQUE KEY `Tel` (`Tel_Staff`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `element`
--
ALTER TABLE `element`
  MODIFY `ID_Element` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `ID_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `ID_Product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `staff`
--
ALTER TABLE `staff`
  MODIFY `ID_Staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `element`
--
ALTER TABLE `element`
  ADD CONSTRAINT `element_ibfk_1` FOREIGN KEY (`ID_Product`) REFERENCES `product` (`ID_Product`),
  ADD CONSTRAINT `element_ibfk_2` FOREIGN KEY (`ID_Order`) REFERENCES `orders` (`ID_Order`);

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ID_client`) REFERENCES `client` (`ID_client`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`ID_Staff`) REFERENCES `staff` (`ID_Staff`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

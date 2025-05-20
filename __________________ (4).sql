-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3307
-- Время создания: Май 13 2025 г., 10:55
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
(7, 'Максим', '+7954120144', 'maxmax@mail.ru', '444', '2009-02-22'),
(10, 'Валерия', '+79049618844', 'vvkartashyan@gmail.com', '$2y$10$gE3qFc34hzXF6Xmrb1ddyen3f0W/JVNQuTejsnn.PGOvE9ZM3MFGO', '2025-04-01'),
(11, 'Никита', '+794510244477', 'nikiik@gmail.com', '$2y$10$N6vncQfaq5.MmzNfT1spNuBTQJTcI7b.J.Ib7BUKVxvtJMiFCo4H.', '2025-04-03'),
(12, 'Вероника', '+7777', 'nk@gmail.com', '$2y$10$eGK32NnLiHgFMsmxmfqNBuu5VAQcUa5w9sU5wOwFyqMADeEn64krq', '2025-04-01');

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
(5, 2, 1, 'Кемерово', 2, 99.20, '0000-00-00', 'доставлен'),
(6, 11, 5, 'г.Москва пр.Мира д.3 кв.234', 1, 125.00, '2025-04-07', 'доставлен'),
(7, 11, 12, 'г.Кемерово пр.Ленина д.2 кв.33', 0, 255.00, '2025-04-16', 'доставлен'),
(8, 10, 8, 'пр. Московский д.990 кв.11', 0, 1145.00, '2025-04-21', 'доставлен'),
(9, 11, 12, '0', 1, 1458.00, '2025-04-21', 'доставлен');

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
(1, 'ООО\"Село\"', 'Молоко 2,5%', '2025-04-30', 100.25, 0.900),
(3, 'ООО\"СельхозМарт\"', 'Хлеб', '2025-04-15', 90.20, 0.400),
(4, 'ООО\"Село\"', 'Сметана 15%', '2025-05-23', 155.26, 0.200),
(6, 'ООО\"СельхозМарт\"', 'Булочка с маком', '2025-04-24', 66.22, 0.100);

-- --------------------------------------------------------

--
-- Структура таблицы `staff`
--

CREATE TABLE `staff` (
  `ID_Staff` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Tel_Staff` varchar(15) NOT NULL,
  `Title` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - администратор, 0 - курьер',
  `Password_Staff` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `staff`
--

INSERT INTO `staff` (`ID_Staff`, `Name`, `Tel_Staff`, `Title`, `Password_Staff`) VALUES
(1, 'Инга', '+7145201441', 0, '444'),
(5, 'Виктория', '+79584102574', 2, '444'),
(6, 'Валентина', '+7958417896', 2, '111'),
(7, 'Пётр', '+796320145687', 1, '111'),
(8, 'Никита', '+74568120365', 0, '111'),
(10, 'Валерия', '+7999', 1, '111'),
(11, 'Валерия', '+7989', 0, '111'),
(12, 'Валерия', '+7987', 1, '$2y$10$yQHpGTuy2tfuBwAD1N99w.bbQpQMWPhUlhNHKGfRCsERKgfjY9WN2'),
(14, 'VVV', '+7', 0, '$2y$10$yQHpGTuy2tfuBwAD1N99w.bbQpQMWPhUlhNHKGfRCsERKgfjY9WN2');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

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
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `element`
--
ALTER TABLE `element`
  MODIFY `ID_Element` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `ID_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `ID_Product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `staff`
--
ALTER TABLE `staff`
  MODIFY `ID_Staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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

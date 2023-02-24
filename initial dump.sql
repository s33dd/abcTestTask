-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 24 2023 г., 18:09
-- Версия сервера: 10.1.44-MariaDB
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `paneltest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remaining` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `item`
--

INSERT INTO `item` (`item_id`, `name`, `remaining`) VALUES
(1, 'Колбаса', 300),
(2, 'Пармезан', 10),
(3, 'Левый носок', 150);

-- --------------------------------------------------------

--
-- Структура таблицы `preorder`
--

CREATE TABLE `preorder` (
  `item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `preorder`
--

INSERT INTO `preorder` (`item`, `quantity`, `date`) VALUES
(3, 1, '2021-01-13'),
(3, 1, '2021-01-14');

-- --------------------------------------------------------

--
-- Структура таблицы `supply`
--

CREATE TABLE `supply` (
  `supply_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `supply`
--

INSERT INTO `supply` (`supply_number`, `item`, `quantity`, `price`, `date`) VALUES
('1', 1, 300, 5000, '2021-01-01'),
('12-TP-777', 3, 100, 500, '2021-01-13'),
('12-TP-778', 3, 50, 300, '2021-01-14'),
('12-TP-779', 3, 77, 539, '2021-01-20'),
('12-TP-877', 3, 32, 176, '2021-01-30'),
('12-TP-977', 3, 32, 554, '2021-02-01'),
('12-TP-979', 3, 200, 1000, '2021-02-05'),
('t-500', 2, 10, 6000, '2021-01-02');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Индексы таблицы `preorder`
--
ALTER TABLE `preorder`
  ADD PRIMARY KEY (`item`,`date`);

--
-- Индексы таблицы `supply`
--
ALTER TABLE `supply`
  ADD PRIMARY KEY (`supply_number`,`item`),
  ADD KEY `item` (`item`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `preorder`
--
ALTER TABLE `preorder`
  ADD CONSTRAINT `preorder_ibfk_1` FOREIGN KEY (`item`) REFERENCES `item` (`item_id`);

--
-- Ограничения внешнего ключа таблицы `supply`
--
ALTER TABLE `supply`
  ADD CONSTRAINT `supply_ibfk_1` FOREIGN KEY (`item`) REFERENCES `item` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

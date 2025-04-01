-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 04 2024 г., 05:55
-- Версия сервера: 10.8.4-MariaDB
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `SystemBase`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Practice`
--

CREATE TABLE `Practice` (
  `practice_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `year` year(4) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `practice_date` date DEFAULT NULL,
  `practice_period` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `practice_address` varchar(255) DEFAULT NULL,
  `practice_type` varchar(11) DEFAULT NULL,
  `practice_place` varchar(11) DEFAULT NULL,
  `order_number_and_date` varchar(255) DEFAULT NULL,
  `institute` varchar(255) DEFAULT NULL,
  `paid_practice` varchar(5) DEFAULT NULL,
  `grade` int(1) DEFAULT NULL,
  `handling_difficulties` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `supervisor_position` varchar(255) DEFAULT NULL,
  `contract_type` varchar(255) DEFAULT NULL,
  `ysu_practice_supervisor` varchar(255) DEFAULT NULL,
  `organization_practice_supervisor` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Practice`
--

--
-- Структура таблицы `PracticeAdmin`
--

CREATE TABLE `PracticeAdmin` (
  `institute` varchar(255) NOT NULL,
  `direction` varchar(255) NOT NULL,
  `course` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `supervisor_name` varchar(255) NOT NULL,
  `supervisor_position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `PracticeDirector`
--

CREATE TABLE `PracticeDirector` (
  `group_name` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `practice_date_from` date NOT NULL,
  `practice_date_to` date NOT NULL,
  `practice_name` varchar(255) NOT NULL,
  `order_number_and_date` varchar(255) NOT NULL,
  `practice_type` varchar(255) NOT NULL,
  `practice_location` varchar(255) NOT NULL,
  `practice_supervisor_name` varchar(255) NOT NULL,
  `practice_supervisor_position` varchar(255) NOT NULL,
  `production_tasks_name` varchar(255) DEFAULT NULL,
  `production_tasks_date` date DEFAULT NULL,
  `practice_from` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `PracticeDirector`
--

INSERT INTO `PracticeDirector` (`group_name`, `year`, `practice_date_from`, `practice_date_to`, `practice_name`, `order_number_and_date`, `practice_type`, `practice_location`, `practice_supervisor_name`, `practice_supervisor_position`, `production_tasks_name`, `production_tasks_date`, `practice_from`) VALUES
('1121б', 2024, '2024-04-21', '2024-05-04', 'югу', '1 от 01.01.2022', 'учебная практика', 'югу', 'авы', 'аыв', NULL, NULL, 'аыв');

-- --------------------------------------------------------

--
-- Структура таблицы `Students`
--

CREATE TABLE `Students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `grade` decimal(3,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Students`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','director_opop') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'director_opop', 'director_opop', 'director_opop');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Students`
--
ALTER TABLE `Students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

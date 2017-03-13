-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 10 2017 г., 20:02
-- Версия сервера: 5.5.50
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `edu`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `bio` text,
  `photo` varchar(255) DEFAULT NULL,
  `photo_medium` varchar(255) DEFAULT NULL,
  `photo_big` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `twitter_name` varchar(255) DEFAULT NULL,
  `twitter_username` varchar(255) DEFAULT NULL,
  `vk` varchar(255) DEFAULT NULL,
  `vk_name` varchar(255) DEFAULT NULL,
  `vk_uri` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `facebook_name` varchar(255) DEFAULT NULL,
  `facebook_username` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - register, 2 - teacher , 3 - admin',
  `dt_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  `telegram_chat_id` int(11) DEFAULT NULL,
  `isConfirmed` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

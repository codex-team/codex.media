-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2016 at 09:13 AM
-- Server version: 5.7.16
-- PHP Version: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

-- DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `text` text NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `root_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `dt_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

-- DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` int(10) UNSIGNED DEFAULT NULL,
  `filename` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `size` float UNSIGNED NOT NULL,
  `extension` varchar(5) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `type` tinyint(13) NOT NULL COMMENT 'Тип файла из контроллера Transport',
  `file_hash` binary(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

-- DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - show, 1 - hide , 2 - removed',
  `id_parent` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `uri` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `html_content` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(10) UNSIGNED NOT NULL,
  `is_menu_item` tinyint(1) NOT NULL DEFAULT '0',
  `rich_view` tinyint(1) DEFAULT '0',
  `dt_pin` timestamp NULL DEFAULT NULL,
  `source_link` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`id_parent`),
  KEY `id_parent` (`id_parent`),
  KEY `type_2` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=283 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(50) NOT NULL,
  `value` varchar(150) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`, `label`) VALUES
('address', '193312 Санкт-Петербург, Товарищеский пр., д. 10, корп. 2', 'site_info'),
('city', 'Санкт-Петербург', 'site_info'),
('coordinates', '59.919041, 30.4875325', 'site_info'),
('description', 'Приветствуем на официальном сайте школы №332 Санкт-Петербурга', 'site_info'),
('email', 'spb_school332@mail.ru', 'site_info'),
('fax', '580-82-49', 'site_info'),
('full_name', 'ГБОУ СОШ №332 Невского района', 'site_info'),
('phone', '580-89-08; 584-54-98', 'site_info'),
('title', 'Школа 332', 'site_info');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

-- DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `dt_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  `telegram_chat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

-- DROP TABLE IF EXISTS `users_sessions`;
CREATE TABLE IF NOT EXISTS `users_sessions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `cookie` varchar(100) NOT NULL,
  `dt_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_access` timestamp NULL DEFAULT NULL,
  `dt_close` timestamp NULL DEFAULT NULL,
  `useragent` text NOT NULL,
  `social_provider` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '1 - vk, 2 - fb , 3- tw',
  `ip` bigint(11) NOT NULL DEFAULT '0',
  `autologin` smallint(4) DEFAULT NULL COMMENT 'autologin type',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/* Remove `site_info` table */
DROP TABLE IF EXISTS `site_info`;

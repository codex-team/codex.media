/*

Author: Taly

Current datetime: 2015-12-18 19-12

DB updates:
- added table 'comments'
- added table 'site_info' with values
- added column 'users.phone'

 */

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `text` text NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `root_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `dt_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `site_info`
--

CREATE TABLE `site_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `full_name` varchar(70) DEFAULT NULL,
  `description` varchar(140) DEFAULT NULL,
  `address` varchar(140) DEFAULT NULL,
  `coordinates` varchar(30) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `fax` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `logo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` (`id`, `title`, `city`, `full_name`, `description`, `address`, `coordinates`, `phone`, `fax`, `email`, `logo`) VALUES
(1, '332 школа', 'Санкт-Петербург', 'ГБОУ СОШ №332 Невского района', 'Приветствуем на официальном сайте школы №332 Санкт-Петербурга', '193312 Санкт-Петербург, Товарищеский пр., д. 10, корп. 2', '59.919041, 30.4875325', '580-89-08; 584-54-98', '580-82-49', 'spb_school332@mail.ru', ''),

-- --------------------------------------------------------

--
-- Updates for table `users`
--

ALTER TABLE `users` ADD `phone` varchar(50) DEFAULT NULL AFTER `email`;

-- --------------------------------------------------------

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_info`
--
ALTER TABLE `site_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `site_info`
--
ALTER TABLE `site_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2015 at 03:41 AM
-- Server version: 5.6.27
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `332`
--

-- --------------------------------------------------------

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `page` int(10) UNSIGNED NOT NULL,
  `filename` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `size` int(10) UNSIGNED NOT NULL,
  `extension` varchar(5) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - show, 1 - hide , 2 - removed',
  `id_parent` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `uri` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `html_content` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(10) UNSIGNED NOT NULL,
  `is_menu_item` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `type`, `status`, `id_parent`, `uri`, `title`, `content`, `html_content`, `date`, `author`, `is_menu_item`) VALUES
(12, 1, 0, 0, '', 'Общественные наблюдатели', '		Уважаемые родители, зарегистрированные в качестве общественных наблюдаталей на экзаменах! просим вас ознакомиться с презентацией "Общественный наблюдатель" и подойти к Александре Васильевне Ивановой для заключительной консультации.	', '					', '2014-04-30 15:39:03', 1, 0),
(13, 1, 0, 0, 'about', 'О школе', '<h3>Директор школы, Красюк Светлана Ивановна:</h3>Мы стремимся к тому, чтобы наша школа была 	<ul><li> школой, отношения в которой складываются на взаимном уважении учащихся и учителей, на уважении личности</li><li>школой без опасности, ориентированной на сохранение и укрепление здоровья детей, развитие их личностного потенциала</li><li>школой воспитания петербуржца, который является носителем традиций нашего города</li><li>школой,  которая дает учащимся фундаментальное образование;  учителя  школы  отличаются высокой культурой и высоким уровнем  профессионализма</li><li>школой, в которой всем - и взрослым, и детям - тепло и уютно, потому что у этой школы добрая душа.</li></ul><br />', '', '2014-05-05 09:39:08', 1, 1),
(14, 1, 0, 0, 'parents', 'Родителям', 'adad', '', '2014-05-05 09:49:13', 1, 1),
(15, 1, 0, 0, 'learners', 'Ученикам', '', '																				', '2014-05-05 11:51:37', 1, 1),
(16, 1, 0, 15, '', 'Проверка', '															', '																									', '2014-05-05 12:01:00', 1, 0),
(17, 1, 0, 0, 'teachers', 'Учителям', '', '										', '2014-05-05 15:21:12', 1, 1),
(18, 1, 0, 13, 'school-info', 'Информация о школе', '<h3>Краткая историческая справка</h3>Школа открыта в 1976 году. Директорами школы были Николай Арсентьевич Краев (участник Великой Отечественной войны, орденоносец; Анастасия Степановна Фадеева. Четверть века возглавляла школу Маргарита Николаевна Хабибулина.<br /><br /> 	Ежегодные мероприятия школы - туристические слёты,  литературные кафе, спортивные праздники, День Матери, «Посвящение в  пятиклассники», «Посвящение в десятиклассники», январский цикл  мероприятий «Памяти Блокады», «День Семьи и Школы», театрализованный  «Последний Звонок» и многое другое.<br /><br /> 	Добрая традиция школы – тесная связь с родителями, их  регулярное дежурство по школе, участие в органах родительского  соуправления. Партнёрским, дружеским отношениям способствует тот факт,  что значительная часть родителей оканчивала нашу школу.<br /><br /> 	Материалы Школьной Летописи, собираемые Долорес Ефимовной Будак, стали  основой экспозиции выставочных залов «Мы в истории России и  Санкт-Петербурга».<br /><br /> 	Школа может по праву гордиться своими выпускниками –  учёными, военными, спортсменами, журналистами, врачами, педагогами,  деятелями искусства. В числе известных питомцев нашей школы: Алла  Довлатова – телеведущая, Евгений Мачнев – журналист, Светлана Кузьмина –  оперная певица, Диана Вишнёва – балерина, народная артистка России.<br /><br />  	В настоящее время в школе работает 75 педагогов. Из них 13 учителей имеют звание "Почетный работник общего образования РФ", 7 учителей награждены значком "Отличник народного просвещения", 6 учителей - Грамотой Министерства образования РФ, 1 учитель – знаком «За гуманизацию образования». Кроме того, 11 человек прошли обучение и имеют удостоверение эксперта Единого Государственного Экзамена.  (русский язык - 2 человека, биология -1 человек, английский язык – 4  человека, физика - 1 человек, химия - 1 человек, математика - 1 человек,  история – 1 человек).<br /><br /><br /><h3>Общая информация о школе</h3>  	ГБОУ школа №332 Невского района Санкт-Петербурга находится по адресу Товарищеский проспект, д.10, корп.2, литер А.  <br /><br />Директор школы - Светлана Ивановна Красюк. <br />Телефон, факс: 584-54-98. Электронная почта: spb_school332@mail.ru.<br /><br /><strong>Режим работы школы</strong>: понедельник - пятница, 08.00-18.00; суббота, 08.00-16.00.<br /><br />  	Форма обучения: дневная, первая смена;  надомное, дистанционное обучение (при наличии соответствующих  документов). Начальная школа, общее среднее образование, полное среднее  образование.<br /><br />  	Проектная мощность образовательного учреждения - 880 человек.<br />В настоящее время в школе обучается 930 человек, в том числе:<br /><ul><li> 		в 1 классах - 95 человек,</li><li> 		во 2 классах - 85 человек,</li><li> 		в 3 классах - 108 человек,</li><li> 		в 4 классах - 91 человек,</li><li> 		в 5 классах - 116 человек,</li><li> 		в 6 классах - 87 человек,</li><li> 		в 7 классах - 111 человек,</li><li> 		в 8 классах - 70 человек,</li><li> 		в 9 классах - 78 человек,</li><li> 		в 10 классе - 36 человек,</li><li> 		в 11 классах - 51 человек.</li></ul>  	В настоящее время вакантные места имеются в параллели 1-х классов и в параллели 8-х классов.<br /><br />  	Язык обучения в образовательном учреждении - русский.<br /><br />Общежитие, проживание в интернате не предоставляется. Стипендии не предоставляются.  <br /><br />Учащиеся, окончившие учебный год только с отличными отметками,  награждаются премией И.В. Высоцкого, депутата Законодательного Собрания  Санкт-Петербурга, председателя Санкт-Петербургского городского и  Ленинградского областного отделений Всероссийской общественной  организации ветеранов "Боевое Братство". <br /><br />О мерах социальной поддержки -  на страницах "<a href="http://school332.ru/pages/food.html">Школьное питание</a>" и "<a href="http://school332.ru/pages/help.html">Помощь</a>".<br /><br /><h3></h3><h3>Материально-техническая база школы</h3><ol><li> 		16 интерактивных досок</li><li> 		31 мультимедиа-комплекс в учебных классах (компьютер, проектор, экран)</li><li> 		2 компьютерных класса (с выходом в Интернет)</li><li> 		8 административных компьютеров</li><li> 		Единая локальная сеть</li><li> 		2 документ-камеры</li><li> 		3 кабинета с современным оборудованием: физика, химия, биология</li><li> 		конференц-зал</li><li> 		библиотека</li><li> 		спортивный зал в здании школы, спортивный стадион при школе</li></ol>  	На официальном сайте школы работает файлообменник, используемый, в том  числе, как электронный образовательный ресурс. Также на сайте создана  страница <a href="http://school332.ru/pages/lib.html">библиотеки</a>. На странице, посвященной дистанционному обучению, перечислены некоторые электронные образовательные ресурсы.<br /><br />  	В кабинете информатики №48 по пятницам, с 14.30 до 16.30 открыт доступ к  компьютерам и ресурсам Интернет. Учащиеся могут работать за  компьютерами после прохождения инструктажа по технике безопасности, в  присутствии преподавателя.<br /><br /><br /><br /><h3>Информация об образовательных программах и средствах обучения.</h3>  	<br />Школа реализует следующие образовательные программы:<br /><ol><li>Образовательная программа начального общего образования (1-4 классы), нормативный срок изучения - 4 года;</li><li>Образовательная программа основного общего образования (5-9 классы), нормативный срок изучения - 5 лет;</li><li>Общеобразовательные программы среднего (полного) общего образования  (10-11 классы; социально-гуманитарный, химико-биологический,  универсальный профиль), нормативный срок изучения - 2 года.</li></ol>  	Программы дополнительного образования различной направленности, в том числе: научно-технической, художественно-эстетической, культурологической.<br />  	После уроков учащихся школы приглашают на занятия по интересам<br /><ul><li> 		ИЗО-студия (А.П.Бабичев);</li><li> 		Театр Миниатюр (Г.Н.Чекарёва);</li><li> 		Студия спортивного бального танца "Соната" (О.В.Неволина);</li><li> 		Спортивное краеведение и туристское многоборье (И.Л.Бахтина, А.А.Новиков);</li><li> 		ОФП (И.Л. Бахтина, А.Г.Просолова);</li><li> 		Волейбол (от СДЮШОР)</li><li> 		Каратэ (А.С.Дмитриев)</li></ul>  	Выпускники школы успешно поступают в различные высшие  учебные заведения и заведения среднего специального образования  Санкт-Петербурга и Ленинградской области, в том числе: Российский  колледж традиционной культуры, СПбГЭТУ, СПбГУТ, РГПУ, СПбНИУ ИТМО, НМСУ  "Горный", СПбГАСУ и другие.<br /><br /><br /><h3>Дополнительная информация</h3>Учредителем школы на данный момент официально является Администрация Невского района Санкт-Петербурга. Глава Администрации - Серов Константин Николаевич.  	192131 Санкт-Петербург, пр. Обуховской обороны д.163, каб. 410<br /><br /> 	Телефон: 576-98-32<br /> 	Факс: 576-98-31<br />  	Сайт Администрации Невского района - <a href="http://gov.spb.ru/gov/admin/terr/nevsky">http://gov.spb.ru/gov/admin/terr/nevsky</a><br /><br />В соответствии с действующим законодательством, а также пожеланиями родителей учащихся, в настоящее время в школе<br /><ul><li>Работает вахта</li><li>Установлена "тревожная кнопка"</li><li>Установлена противопожарная сигнализация</li></ul>', '																														', '2014-05-05 15:44:16', 1, 0),
(19, 2, 0, 0, '', 'Электронный дневник', 'Уважаемые родители! Обращаем ваше внимание:<ol><li>Заполнения онлайн-заявления на подключение функции "Электронный  дневник" недостаточно! Вам необходимо завершить процедуру подключения  лично у школьного ответственного - Ястребовой Алины Владимировны. При  себе нужно иметь паспорт.</li><li>Онлайн-заявление действительно в течение 90 дней. По окончании этого срока заявление на сайте автоматически удаляется.</li></ol>', '					', '2014-05-06 08:24:49', 1, 0),
(20, 1, 0, 13, 'structure', 'Структура управления ОУ', '', '					', '2014-05-06 10:17:56', 1, 0),
(21, 1, 0, 13, 'teachers', 'Педагогический коллектив', '<a href="https://docs.google.com/spreadsheet/ccc?key=0AsYwVeF8VOI_dG9pODRoSkIyWDlLWm5tVUhvRXBqa1E&amp;usp=sharing#gid=0">Общие сведения о сотрудниках</a>', '					', '2014-05-06 10:23:38', 1, 0),
(22, 1, 0, 0, 'files', 'Файлообменник', '', '', '2014-05-07 07:58:32', 1, 0),
(28, 1, 0, 0, '', 'Моя страница', 'Ура! У меня теперь есть своя собственная страница!', '					', '2015-12-14 20:48:36', 1, 0);

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
(1, '332 школа', 'Санкт-Петербург', 'ГБОУ СОШ №332 Невского района', 'Приветствуем на официальном сайте школы №332 Санкт-Петербурга', '193312 Санкт-Петербург, Товарищеский пр., д. 10, корп. 2', '59.919041, 30.4875325', '580-89-08; 584-54-98', '580-82-49', 'spb_school332@mail.ru', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
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
  `telegram_chat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `bio`, `photo`, `photo_medium`, `photo_big`, `twitter`, `twitter_name`, `twitter_username`, `vk`, `vk_name`, `vk_uri`, `facebook`, `facebook_name`, `facebook_username`, `status`, `dt_reg`, `is_removed`, `telegram_chat_id`) VALUES
(5, 'Demyashev', NULL, NULL, NULL, 'https://pbs.twimg.com/profile_images/538315265373532160/kDAPAuNz_normal.jpeg', 'https://pbs.twimg.com/profile_images/538315265373532160/kDAPAuNz_normal.jpeg', 'https://pbs.twimg.com/profile_images/538315265373532160/kDAPAuNz_normal.jpeg', '449259184', 'Demyashev', 'demyashev', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2015-12-14 20:40:21', 0, NULL),
(6, 'Guryn Vitaly', 'Vitalik7tv@yandex.ru', NULL, NULL, 'https://pp.vk.me/c629122/v629122994/14a9b/EqtekBp6tic.jpg', 'https://pp.vk.me/c629122/v629122994/14a9a/GjUYZRbsLks.jpg', 'https://pp.vk.me/c629122/v629122994/14a99/xHeuB_fBDF0.jpg', NULL, NULL, NULL, '10336994', 'Guryn Vitaly', 'guryn', NULL, NULL, NULL, 2, '2015-12-14 20:41:49', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

CREATE TABLE `users_sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `cookie` varchar(100) NOT NULL,
  `dt_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_access` timestamp NULL DEFAULT NULL,
  `dt_close` timestamp NULL DEFAULT NULL,
  `useragent` text NOT NULL,
  `social_provider` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '1 - vk, 2 - fb , 3- tw',
  `ip` bigint(11) NOT NULL DEFAULT '0',
  `autologin` smallint(4) DEFAULT NULL COMMENT 'autologin type'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_sessions`
--

INSERT INTO `users_sessions` (`id`, `uid`, `cookie`, `dt_start`, `dt_access`, `dt_close`, `useragent`, `social_provider`, `ip`, `autologin`) VALUES
(19, 6, '58ec67d691e429b13a3d3e4fa03ee95dc6f61ad52ea287d1ca5c342a5e8ff5d8', '2015-12-14 20:43:54', '2015-12-14 20:43:54', NULL, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9', 1, 2130706433, NULL),
(20, 6, '3f9592fc16422d4d7340b794290c3e627b63f9d23e882d1b0a99bf34a8dc4d33', '2015-12-14 20:44:20', '2015-12-14 20:44:20', NULL, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9', 1, 2130706433, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`,`id_parent`),
  ADD KEY `id_parent` (`id_parent`),
  ADD KEY `type_2` (`type`);

--
-- Indexes for table `site_info`
--
ALTER TABLE `site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_sessions`
--
ALTER TABLE `users_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `site_info`
--
ALTER TABLE `site_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users_sessions`
--
ALTER TABLE `users_sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

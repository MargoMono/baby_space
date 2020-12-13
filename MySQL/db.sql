

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `file_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `alias`, `file_id`, `created_at`) VALUES
(1, 'tut-tipa-umnyy-zagolovok', 182, '2020-12-08 20:25:15'),
(2, 'a-etot-zagolovok-ochen-veselyy', 187, '2020-12-09 15:15:40'),
(3, 'prodajnyy-zagolovok', 188, '2020-12-09 15:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `blog_description`
--

CREATE TABLE IF NOT EXISTS `blog_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` text,
  `tag` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `blog_description_blog_id_fk` (`blog_id`),
  KEY `blog_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blog_description`
--

INSERT INTO `blog_description` (`id`, `blog_id`, `language_id`, `name`, `short_description`, `description`, `tag`, `meta_title`, `meta_description`, `meta_keyword`, `created_at`) VALUES
(1, 1, 1, 'Тут типа умный заголовок', 'А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка', '<p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка&nbsp;</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка<br></p>', 'sdf', 'sfsd', 'fsdf', 'sf', '2020-12-08 20:25:15'),
(2, 1, 3, 'Тут типа умный заголовок на англ', 'А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл', '<p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл</p><hr><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл</p><p>А тут ой какой интересный и завлекающий текст, прям не текст, а конфетка и все на агл<br></p>', 'sfsdf', 'sf', 'sfs', 'fsf', '2020-12-08 20:25:15'),
(3, 2, 1, 'А этот заголовок очень веселый', 'Веселый текст, веселый заголовок, веселые дети и прочая красота ', '<p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Веселый текст, веселый заголовок, веселые дети и прочая красота</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Веселый текст, веселый заголовок, веселые дети и прочая красота</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Веселый текст, веселый заголовок, веселые дети и прочая красота</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Веселый текст, веселый заголовок, веселые дети и прочая красота</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Веселый текст, веселый заголовок, веселые дети и прочая красота</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Веселый текст, веселый заголовок, веселые дети и прочая красота</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><br></p>', '', '', '', '', '2020-12-09 15:15:40'),
(4, 2, 3, 'А этот заголовок очень веселый на англ', 'А этот заголовок очень веселый на англ', '<p>А этот заголовок очень веселый на англ</p><p>А этот заголовок очень веселый на англ</p><p>А этот заголовок очень веселый на англ</p><p>А этот заголовок очень веселый на англ</p><p>А этот заголовок очень веселый на англ</p><p>А этот заголовок очень веселый на англ</p><p>А этот заголовок очень веселый на англ<br></p>', '', '', '', '', '2020-12-09 15:15:40'),
(5, 3, 1, 'Продажный заголовок', 'Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары', '<p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><br></p>', '', '', '', '', '2020-12-09 15:16:36'),
(6, 3, 3, 'Продажный заголовок на англ', 'Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары', '<p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\">Вот ты думаешь это будет полезная статья? Как бы не так, после не ты побежишь покупать мои товары</span></p><p><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><span style=\"color: rgb(119, 119, 119); font-family: Roboto, sans-serif; font-size: 15px;\"><br></span><br></p>', '', '', '', '', '2020-12-09 15:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `file_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `tag` text,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent_id`, `name`, `short_description`, `description`, `file_id`, `status`, `alias`, `tag`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(1, NULL, ' от 0 до 2 лет', 'Подходят для детей от от 0 до 2 лет', 'Описание', 179, 1, 'ot-0-do-2-let', 'Развивающие игрушки, бизиборды ', 'Игрушки для детей от 0 до 2 лет', 'Развивающие игрушки, бизиборды для маленьких детей от 0 до 2 лет', 'Развивающие игрушки, бизиборды '),
(10, NULL, 'от 2 до 4 лет', 'Подходят для детей от от 2 до 4 лет', '<p>Описание</p>', 180, 1, 'ot-2-do-4-let', 'Бизиборды, развивающие игрушки', 'Игрушки для детей от  2 до 4 лет', 'Бизиборды, развивающие игрушки для детей от  2 до 4 лет', 'Бизиборды, развивающие игрушки'),
(14, NULL, 'от 5 и старше', 'Подходят для детей от 5 и старше', 'Описание', 181, 1, 'ot-5-i-starshe', 'Развивающие игрушки, бизиборды', 'Игрушки для детей от 5 лет и старше', 'Развивающие игрушки, бизиборды для маленьких детей от от 5 лет и старше', 'Развивающие игрушки, бизиборды');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `user_name`, `user_email`, `status`, `created_at`) VALUES
(1, 'Rita', 'margomonogarova@gmail.com', 1, '2020-12-08 23:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `comment_answer`
--

CREATE TABLE IF NOT EXISTS `comment_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comment_answer_comment_id_fk` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment_answer`
--

INSERT INTO `comment_answer` (`id`, `comment_id`, `created_at`) VALUES
(1, 1, '2020-12-08 23:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `comment_answer_description`
--

CREATE TABLE IF NOT EXISTS `comment_answer_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_answer_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_answer_description_id_fk` (`comment_answer_id`),
  KEY `comment_answer_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment_answer_description`
--

INSERT INTO `comment_answer_description` (`id`, `comment_answer_id`, `language_id`, `description`) VALUES
(1, 1, 1, 'Текст ответа RU'),
(2, 1, 3, 'Текст  ответаENG');

-- --------------------------------------------------------

--
-- Table structure for table `comment_answer_file`
--

CREATE TABLE IF NOT EXISTS `comment_answer_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `comment_answer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_answer_file_file_id_fk` (`file_id`),
  KEY `comment_answer_file_comment_id_fk` (`comment_answer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment_answer_file`
--

INSERT INTO `comment_answer_file` (`id`, `file_id`, `comment_answer_id`) VALUES
(1, 185, 1),
(2, 186, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment_description`
--

CREATE TABLE IF NOT EXISTS `comment_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_description_comment_id_fk` (`comment_id`),
  KEY `comment_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment_description`
--

INSERT INTO `comment_description` (`id`, `comment_id`, `language_id`, `description`) VALUES
(1, 1, 1, 'Текст отзыва RU'),
(2, 1, 3, 'Текст  отзыва ENG');

-- --------------------------------------------------------

--
-- Table structure for table `comment_file`
--

CREATE TABLE IF NOT EXISTS `comment_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_file_file_id_fk` (`file_id`),
  KEY `comment_file_comment_id_fk` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment_file`
--

INSERT INTO `comment_file` (`id`, `file_id`, `comment_id`) VALUES
(1, 184, 1);

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `alpha2` varchar(2) NOT NULL,
  `alpha3` varchar(3) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `file_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_file_id_fk` (`file_id`),
  KEY `country_currency_id_fk` (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `alpha2`, `alpha3`, `status`, `file_id`, `currency_id`) VALUES
(1, 'Россия', 'RU', 'RUS', 1, 126, 1),
(2, 'Англия', 'GB', 'GBR', 1, 127, 2),
(4, 'Telepay Local Transfer', 'RU', 'GBR', 1, 156, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE IF NOT EXISTS `coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL,
  `discount` int(3) NOT NULL,
  `quantity` int(11) NOT NULL,
  `used` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`id`, `code`, `discount`, `quantity`, `used`, `start_date`, `end_date`) VALUES
(2, '234', 45, 356456, 0, '2020-12-12 00:00:00', '2021-01-09 00:00:00'),
(3, '1', 12, 12, 0, '2020-12-01 00:00:00', '2020-12-03 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`, `code`, `alias`) VALUES
(1, 'Рубли', 'RUB', 'руб.'),
(2, 'Доллары', 'USD', 'USD'),
(3, 'Евро', 'EUR', 'EUR');

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `alias`, `type`) VALUES
(1, 'russia', '1605206087russia.png', 'image/jpeg'),
(12, 'photo_2020-11-14_18-12-41', '1605986797photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(20, '1599152854Бук', '16060379601599152854Бук.png', 'image/png'),
(21, '1599152854Бук', '16060394911599152854Бук.png', 'image/png'),
(22, '1599152854Бук', '16060396131599152854Бук.png', 'image/png'),
(23, '1599155021Васильковый металлик', '16060396761599155021Васильковый металлик.png', 'image/png'),
(24, '1599152854Бук', '16060396771599152854Бук.png', 'image/png'),
(25, '1599155021Васильковый металлик', '16060398111599155021Васильковый металлик.png', 'image/png'),
(26, '1599152854Бук', '16060398111599152854Бук.png', 'image/png'),
(27, '1599155021Васильковый металлик', '16060476141599155021Васильковый металлик.png', 'image/png'),
(28, '1599152854Бук', '16060476141599152854Бук.png', 'image/png'),
(29, '1599152854Бук', '16060482391599152854Бук.png', 'image/png'),
(30, 'photo_2020-11-14_18-12-41', '1606116504photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(31, 'photo_2020-11-08_21-41-54', '1606116504photo_2020-11-08_21-41-54.jpg', 'image/jpeg'),
(32, '1599155021Васильковый металлик', '16061176861599155021Васильковый металлик.png', 'image/png'),
(33, '1599152854Бук', '16061176861599152854Бук.png', 'image/png'),
(34, '1599155021Васильковый металлик', '16061179241599155021Васильковый металлик.png', 'image/png'),
(35, '1599152854Бук', '16061179241599152854Бук.png', 'image/png'),
(36, '1599155021Васильковый металлик', '16061179701599155021Васильковый металлик.png', 'image/png'),
(37, '1599152854Бук', '16061179701599152854Бук.png', 'image/png'),
(38, '1599155021Васильковый металлик', '16061182011599155021Васильковый металлик.png', 'image/png'),
(39, '1599152854Бук', '16061182011599152854Бук.png', 'image/png'),
(40, '1599152854Бук', '16061187961599152854Бук.png', 'image/png'),
(41, 'photo_2020-11-14_18-12-41', '1606118796photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(42, '1599155021Васильковый металлик', '16061219921599155021Васильковый металлик.png', 'image/png'),
(43, '1599152854Бук', '16061219931599152854Бук.png', 'image/png'),
(44, '1599155021Васильковый металлик', '16061356661599155021Васильковый металлик.png', 'image/png'),
(45, '1599155021Васильковый металлик', '16061357951599155021Васильковый металлик.png', 'image/png'),
(46, '1599155021Васильковый металлик', '16061358191599155021Васильковый металлик.png', 'image/png'),
(47, '1599155021Васильковый металлик', '16061358671599155021Васильковый металлик.png', 'image/png'),
(48, '1599155021Васильковый металлик', '16061359541599155021Васильковый металлик.png', 'image/png'),
(49, '1599155021Васильковый металлик', '16061359731599155021Васильковый металлик.png', 'image/png'),
(50, '1599152854Бук', '16061359741599152854Бук.png', 'image/png'),
(51, '1599155021Васильковый металлик', '16061362141599155021Васильковый металлик.png', 'image/png'),
(52, '1599152854Бук', '16061362141599152854Бук.png', 'image/png'),
(53, '1599152854Бук', '16061369771599152854Бук.png', 'image/png'),
(54, '1599155021Васильковый металлик', '16061370621599155021Васильковый металлик.png', 'image/png'),
(55, '1599152854Бук', '16061370621599152854Бук.png', 'image/png'),
(56, '1599155021Васильковый металлик', '16061370681599155021Васильковый металлик.png', 'image/png'),
(57, '1599155021Васильковый металлик', '16061373601599155021Васильковый металлик.png', 'image/png'),
(58, '1599155021Васильковый металлик', '16061377801599155021Васильковый металлик.png', 'image/png'),
(59, '1599155021Васильковый металлик', '16061379721599155021Васильковый металлик.png', 'image/png'),
(60, '1599155021Васильковый металлик', '16061381321599155021Васильковый металлик.png', 'image/png'),
(61, '1599155021Васильковый металлик', '16061384281599155021Васильковый металлик.png', 'image/png'),
(62, '1599155021Васильковый металлик', '16061384631599155021Васильковый металлик.png', 'image/png'),
(63, 'photo_2020-11-14_18-12-41', '1606138542photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(64, '1599155021Васильковый металлик', '16061464281599155021Васильковый металлик.png', 'image/png'),
(65, '1599152854Бук', '16061464281599152854Бук.png', 'image/png'),
(66, 'Счет на предоплату', '1606146461Счет на предоплату.pdf', 'application/pdf'),
(67, '1599155021Васильковый металлик', '16061464611599155021Васильковый металлик.png', 'image/png'),
(68, '1599152854Бук', '16061464611599152854Бук.png', 'image/png'),
(69, '1599155021Васильковый металлик', '16061467641599155021Васильковый металлик.png', 'image/png'),
(70, 'photo_2020-11-14_18-12-41', '1606146765photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(71, '1599155021Васильковый металлик', '16061474791599155021Васильковый металлик.png', 'image/png'),
(72, '1599152854Бук', '16061474791599152854Бук.png', 'image/png'),
(73, '1599155021Васильковый металлик', '16061480031599155021Васильковый металлик.png', 'image/png'),
(74, '1599152854Бук', '16061480031599152854Бук.png', 'image/png'),
(75, 'photo_2020-11-14_19-47-03', '1606148035photo_2020-11-14_19-47-03.jpg', 'image/jpeg'),
(76, 'photo_2020-11-14_18-12-41', '1606148035photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(77, 'photo_2020-11-08_21-41-54', '1606148048photo_2020-11-08_21-41-54.jpg', 'image/jpeg'),
(78, 'photo_2020-11-08_21-35-16', '1606148048photo_2020-11-08_21-35-16.jpg', 'image/jpeg'),
(79, '1599155021Васильковый металлик', '16061480851599155021Васильковый металлик.png', 'image/png'),
(80, '1599152854Бук', '16061480851599152854Бук.png', 'image/png'),
(81, '1599155021Васильковый металлик', '16061480951599155021Васильковый металлик.png', 'image/png'),
(82, '1599152854Бук', '16061480951599152854Бук.png', 'image/png'),
(84, '1599155021Васильковый металлик', '16062203761599155021Васильковый металлик.png', 'image/png'),
(89, '1599152854Бук', '16062210041599152854Бук.png', 'image/png'),
(90, 'photo_2020-11-14_18-12-41', '1606221021photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(91, 'uk', '1606235037uk.png', 'image/png'),
(93, '1599155021Васильковый металлик', '16062353011599155021Васильковый металлик.png', 'image/png'),
(94, '1599152854Бук', '16062353011599152854Бук.png', 'image/png'),
(95, '1599155021Васильковый металлик', '16062401591599155021Васильковый металлик.png', 'image/png'),
(97, '1599155021Васильковый металлик', '16062411261599155021Васильковый металлик.png', 'image/png'),
(98, '1599155021Васильковый металлик', '16062430641599155021Васильковый металлик.png', 'image/png'),
(99, 'photo_2020-11-14_19-47-03', '1606243064photo_2020-11-14_19-47-03.jpg', 'image/jpeg'),
(100, 'photo_2020-11-14_18-12-41', '1606243064photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(101, '1599152854Бук', '16062430751599152854Бук.png', 'image/png'),
(103, '1599152854Бук', '16062431891599152854Бук.png', 'image/png'),
(104, 'photo_2020-11-14_18-12-41', '1606243189photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(105, 'photo_2020-11-14_18-12-41', '1606243211photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(106, '1599155021Васильковый металлик', '16062435011599155021Васильковый металлик.png', 'image/png'),
(109, '1599155021Васильковый металлик', '16062438021599155021Васильковый металлик.png', 'image/png'),
(110, '1599155021Васильковый металлик', '16062475971599155021Васильковый металлик.png', 'image/png'),
(111, '1599152854Бук', '16062476431599152854Бук.png', 'image/png'),
(112, 'photo_2020-11-14_18-12-41', '1606247822photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(113, 'photo_2020-11-14_18-12-41', '1606247852photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(114, 'photo_2020-11-14_18-12-41', '1606247875photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(115, 'photo_2020-11-14_18-12-41', '1606247893photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(116, 'photo_2020-11-14_18-12-41', '1606247939photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(117, 'photo_2020-11-14_18-12-41', '1606247957photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(118, 'photo_2020-11-14_18-12-41', '1606247974photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(119, 'photo_2020-11-14_18-12-41', '1606247985photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(120, '1599155021Васильковый металлик', '16062854481599155021Васильковый металлик.png', 'image/png'),
(121, '1599152854Бук', '16062854841599152854Бук.png', 'image/png'),
(122, '1599155021Васильковый металлик', '16062875931599155021Васильковый металлик.png', 'image/png'),
(123, '1599155021Васильковый металлик', '16063308561599155021Васильковый металлик.png', 'image/png'),
(124, '1599152854Бук', '16063308561599152854Бук.png', 'image/png'),
(126, 'russia', '1606407328russia.png', 'image/png'),
(127, 'uk', '1606407409uk.png', 'image/png'),
(128, 'tMA_k47h2TA', '1606408017tMA_k47h2TA.jpg', 'image/jpeg'),
(129, 'logo3', '1606408235logo3.jpg', 'image/jpeg'),
(130, 'logo3', '1606408245logo3.jpg', 'image/jpeg'),
(131, 'logo3', '1606408316logo3.jpg', 'image/jpeg'),
(132, 'logo3', '1606408358logo3.jpg', 'image/jpeg'),
(133, 'logo3', '1606503388logo3.jpg', 'image/jpeg'),
(134, 'logo3', '1606503497logo3.jpg', 'image/jpeg'),
(146, 'tMA_k47h2TA', '1606672864tMA_k47h2TA.jpg', 'image/jpeg'),
(147, '16040460891', '160684914716040460891.png', 'image/png'),
(148, '16040460891', '160685010116040460891.png', 'image/png'),
(149, '16040460891', '160685113016040460891.png', 'image/png'),
(150, '16040460891', '160685160016040460891.png', 'image/png'),
(152, '16040460891', '160685287116040460891.png', 'image/png'),
(153, 'tMA_k47h2TA', '1606852953tMA_k47h2TA.jpg', 'image/jpeg'),
(154, 'tMA_k47h2TA', '1606852968tMA_k47h2TA.jpg', 'image/jpeg'),
(155, '16040460891', '160691173216040460891.png', 'image/png'),
(156, '16040460891', '160691218016040460891.png', 'image/png'),
(157, '16040460891', '160693181416040460891.png', 'image/png'),
(159, '16040460891', '160693212116040460891.png', 'image/png'),
(160, '16040460891', '160693394516040460891.png', 'image/png'),
(161, 'photo_2020-11-14_18-12-41', '1606934264photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(162, '1599155021Васильковый металлик', '16069343691599155021Васильковый металлик.png', 'image/png'),
(163, '1599155021Васильковый металлик', '16069345751599155021Васильковый металлик.png', 'image/png'),
(164, '1599155021Васильковый металлик', '16069346721599155021Васильковый металлик.png', 'image/png'),
(165, 'logo3', '1607171569logo3.jpg', 'image/jpeg'),
(166, '1599155021Васильковый металлик', '16071723381599155021Васильковый металлик.png', 'image/png'),
(167, '1599152854Бук', '16071723381599152854Бук.png', 'image/png'),
(168, 'photo_2020-11-14_18-12-41', '1607172338photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(169, 'photo_2020-11-14_19-47-03', '1607172418photo_2020-11-14_19-47-03.jpg', 'image/jpeg'),
(170, 'photo_2020-11-14_18-12-41', '1607172418photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(171, 'photo_2020-11-14_18-12-41', '1607172505photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(172, 'uk', '1607187658uk.png', 'image/png'),
(173, '1599155021Васильковый металлик', '16071881131599155021Васильковый металлик.png', 'image/png'),
(174, '1599152854Бук', '16071881131599152854Бук.png', 'image/png'),
(175, '1599155021Васильковый металлик', '16071882211599155021Васильковый металлик.png', 'image/png'),
(176, 'photo_2020-11-14_18-12-41', '1607188221photo_2020-11-14_18-12-41.jpg', 'image/jpeg'),
(177, '1599155021Васильковый металлик', '16071882451599155021Васильковый металлик.png', 'image/png'),
(178, '1599152854Бук', '16071882451599152854Бук.png', 'image/png'),
(179, 'baby', '1607359118baby.jpg', 'image/jpeg'),
(180, 'baby2', '1607359207baby2.jpg', 'image/jpeg'),
(181, 'baby3', '1607359275baby3.jpg', 'image/jpeg'),
(182, 'main', '1607448315main.jpg', 'image/jpeg'),
(183, 'comment', '1607458043comment.jpg', 'image/jpeg'),
(184, 'comment', '1607458225comment.jpg', 'image/jpeg'),
(185, 'parallax-bg', '1607458233parallax-bg.png', 'image/png'),
(186, 'popular1', '1607458233popular1.jpg', 'image/jpeg'),
(187, 'blog2', '1607516140blog2.png', 'image/png'),
(188, 'blog3', '1607516196blog3.png', 'image/png');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `languages_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `alias`, `code`, `file_id`) VALUES
(1, 'RU', 'russian', 'ru,ru_RU.UTF-8,ru_RU,russian', 1),
(3, 'ENG', 'english', 'en_US.UTF-8,en_US,en-gb,en_gb,english', 172);

-- --------------------------------------------------------

--
-- Table structure for table `new`
--

CREATE TABLE IF NOT EXISTS `new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `new_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `new`
--

INSERT INTO `new` (`id`, `file_id`, `created_at`) VALUES
(1, 183, '2020-12-08 23:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `new_description`
--

CREATE TABLE IF NOT EXISTS `new_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `new_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `new_description_new_id_fk` (`new_id`),
  KEY `new_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `new_description`
--

INSERT INTO `new_description` (`id`, `new_id`, `language_id`, `name`, `description`) VALUES
(1, 1, 1, 'Название RU', 'Текст RU'),
(2, 1, 3, 'Название ENG', 'Текст ENG');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `country` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  `postcode` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `payment_method_id` varchar(128) NOT NULL,
  `shipping_method_id` varchar(128) NOT NULL,
  `comment` text NOT NULL,
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(20) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `first_name`, `last_name`, `email`, `telephone`, `country`, `city`, `postcode`, `address`, `payment_method_id`, `shipping_method_id`, `comment`, `total_price`, `currency`, `status_id`, `created_at`) VALUES
(2, 'Маргарита', 'Моногаровата1', 'argo@gmail.com1', '(999)16981421', 'Англия', 'Казань1', '1410021', '12', '1', '1', 'Ничего интересного', '4000.00', 'руб', 1, '2020-11-27');

-- --------------------------------------------------------

--
-- Table structure for table `order_payment_method`
--

CREATE TABLE IF NOT EXISTS `order_payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_payment_method`
--

INSERT INTO `order_payment_method` (`id`, `name`) VALUES
(1, 'Наличными курьеру'),
(2, 'По карте курьеру'),
(3, 'Оплата на карту'),
(4, 'Оплата с помощью Яндекс Касс');

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(4) NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sale` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `order_product_order_id_fk` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_shipping_method`
--

CREATE TABLE IF NOT EXISTS `order_shipping_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_shipping_method`
--

INSERT INTO `order_shipping_method` (`id`, `name`) VALUES
(1, 'Доставка курьером по Казани'),
(2, 'Самовывоз в Казани'),
(3, 'Доставка Почтой России');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Новый'),
(2, 'Ожидание оплаты'),
(3, 'Доставка'),
(4, 'Выполнен'),
(5, 'Отменен\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale` int(3) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_category_id_fk` (`category_id`),
  KEY `product_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `price`, `sale`, `file_id`, `status`, `popular`, `alias`, `sort`, `created_at`) VALUES
(8, 1, '0.00', NULL, 117, 1, 0, 'dsf', 0, NULL),
(11, 10, '0.00', NULL, 120, 0, 0, '33333333', 0, NULL),
(12, 10, '0.00', NULL, 121, 1, 0, 'yc', 0, NULL),
(13, 10, '0.00', NULL, 122, 0, 0, '34', 0, NULL),
(14, 14, '991.00', 50, 171, 1, 1, 'test1111', 1, NULL),
(15, 1, '12.00', 0, 128, 1, 1, 'vapvapvap', 0, NULL),
(16, 1, '0.00', 0, 129, 1, 1, '45', 0, NULL),
(17, 1, '50.00', 0, 130, 1, 1, '1', 0, NULL),
(18, 1, '500.00', 0, 131, 1, 0, '1', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_country`
--

CREATE TABLE IF NOT EXISTS `product_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_country_product_id_fk` (`product_id`),
  KEY `product_country_country_id_fk` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_country`
--

INSERT INTO `product_country` (`id`, `country_id`, `product_id`) VALUES
(53, 1, 14),
(54, 2, 14),
(55, 4, 14);

-- --------------------------------------------------------

--
-- Table structure for table `product_description`
--

CREATE TABLE IF NOT EXISTS `product_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `tag` text,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `product_description_product_id_fk` (`product_id`),
  KEY `product_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_description`
--

INSERT INTO `product_description` (`id`, `product_id`, `language_id`, `name`, `description`, `tag`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(25, 14, 1, 'Тест1111', '<p><b>ауцыафу <u>вапрыварва111 ergserger</u></b></p><p><b><u>fп</u></b></p>', '11', '11', '11', '11'),
(27, 15, 1, 'вапвапвап', '', '', '', '', ''),
(29, 16, 1, '45', '', '', '', '', ''),
(31, 17, 1, '1', '', '', '', '', ''),
(33, 18, 1, '1', '', '', '', '', ''),
(39, 14, 3, '12', '<p>12</p>', '12', '12', '12', '12'),
(40, 18, 3, 'Название на английском', '', '', '', '', ''),
(41, 17, 3, '50', '', '', '', '', ''),
(42, 16, 3, '435', '', '', '', '', ''),
(43, 15, 3, 'sdsdsd', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_file`
--

CREATE TABLE IF NOT EXISTS `product_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_file_file_id_fk` (`file_id`),
  KEY `product_file_product_id_fk` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_file`
--

INSERT INTO `product_file` (`id`, `file_id`, `product_id`) VALUES
(3, 168, 15),
(5, 170, 14),
(6, 173, 17),
(7, 174, 17),
(8, 175, 16),
(9, 176, 16),
(10, 177, 15),
(11, 178, 15);

-- --------------------------------------------------------

--
-- Table structure for table `product_recommendations`
--

CREATE TABLE IF NOT EXISTS `product_recommendations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `recommendation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id_product_id_fk` (`product_id`),
  KEY `product_id_recommendation_id_fk` (`recommendation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_recommendations`
--

INSERT INTO `product_recommendations` (`id`, `product_id`, `recommendation_id`) VALUES
(112, 14, 16),
(113, 15, 14),
(114, 16, 14),
(115, 16, 15),
(116, 17, 14),
(118, 18, 14);

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE IF NOT EXISTS `rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` int(3) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rate_currency_id_fk` (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rate`
--

INSERT INTO `rate` (`id`, `currency_id`, `rate`, `date`) VALUES
(7, 2, '73.66', '2020-12-09 00:00:00'),
(8, 3, '89.20', '2020-12-09 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `permission`) VALUES
(1, 'Главный администратор', 100),
(2, 'Администратор', 50);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`id`, `status`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sale_description`
--

CREATE TABLE IF NOT EXISTS `sale_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sale` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `sale_description_sale_id_fk` (`sale_id`),
  KEY `sale_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sale_description`
--

INSERT INTO `sale_description` (`id`, `sale_id`, `language_id`, `name`, `sale`, `description`) VALUES
(1, 1, 1, 'Новогодняя распродажа', 'Скидка 10%!', 'В честь нового года мы устраиваем зимнюю распродажу'),
(2, 1, 3, 'Новогодняя ', 'fsdf', 'sdfsfdsf');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `active_hex` varchar(32) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_role_id_fk` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `salt`, `active_hex`, `role_id`) VALUES
(1, 'Маргарита Моногарова', 'margomonogarova@gmail.com', '3518eaf64ced211c19c16e606c1aa049', 'e0393605', 'c9b07bf4beefedaabcf3828a3675b99a', 1),
(5, 'Динара', 'h.d.n@list.ru', '19024cb8d4cf5d570e4c975bef94f88b', '87001643', 'f11c913118785d448940343ebe06c457', 2);

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE IF NOT EXISTS `size`
(
    `id`   int(11)      NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4;


--
-- Table structure for table `product_size`
--

CREATE TABLE IF NOT EXISTS `product_size`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `size_id`    int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `product_size_product_id_fk` (`product_id`),
    KEY `product_size_size_id_fk` (`size_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 56
  DEFAULT CHARSET = utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `blog_description`
--
ALTER TABLE `blog_description`
  ADD CONSTRAINT `blog_description_blog_id_fk` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `comment_answer`
--
ALTER TABLE `comment_answer`
  ADD CONSTRAINT `comment_answer_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_answer_description`
--
ALTER TABLE `comment_answer_description`
  ADD CONSTRAINT `comment_answer_description_id_fk` FOREIGN KEY (`comment_answer_id`) REFERENCES `comment_answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_answer_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_answer_file`
--
ALTER TABLE `comment_answer_file`
  ADD CONSTRAINT `comment_answer_file_comment_id_fk` FOREIGN KEY (`comment_answer_id`) REFERENCES `comment_answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_answer_file_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `comment_description`
--
ALTER TABLE `comment_description`
  ADD CONSTRAINT `comment_description_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_file`
--
ALTER TABLE `comment_file`
  ADD CONSTRAINT `comment_file_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_file_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `country_currency_id_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `country_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `language`
--
ALTER TABLE `language`
  ADD CONSTRAINT `languages_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `new`
--
ALTER TABLE `new`
  ADD CONSTRAINT `new_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `new_description`
--
ALTER TABLE `new_description`
  ADD CONSTRAINT `new_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `new_description_new_id_fk` FOREIGN KEY (`new_id`) REFERENCES `new` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_country`
--
ALTER TABLE `product_country`
  ADD CONSTRAINT `product_country_country_id_fk` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_country_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_description`
--
ALTER TABLE `product_description`
  ADD CONSTRAINT `product_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_description_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_file`
--
ALTER TABLE `product_file`
  ADD CONSTRAINT `product_file_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `product_file_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_recommendations`
--
ALTER TABLE `product_recommendations`
  ADD CONSTRAINT `product_id_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_id_recommendation_id_fk` FOREIGN KEY (`recommendation_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_currency_id_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `sale_description`
--
ALTER TABLE `sale_description`
  ADD CONSTRAINT `sale_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_description_sale_id_fk` FOREIGN KEY (`sale_id`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `product_size`
--
ALTER TABLE `product_size`
    ADD CONSTRAINT `product_size_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `product_size_size_id_fk` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


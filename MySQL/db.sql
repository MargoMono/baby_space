-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 09 2021 г., 16:27
-- Версия сервера: 5.7.30-33
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `co98609_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blog`
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
-- Дамп данных таблицы `blog`
--

INSERT INTO `blog` (`id`, `alias`, `file_id`, `created_at`) VALUES
(1, 'tut-tipa-umnyy-zagolovok', 182, '2020-12-08 20:25:15'),
(2, 'a-etot-zagolovok-ochen-veselyy', 187, '2020-12-09 15:15:40'),
(3, 'prodajnyy-zagolovok', 188, '2020-12-09 15:16:36');

-- --------------------------------------------------------

--
-- Структура таблицы `blog_description`
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
-- Дамп данных таблицы `blog_description`
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
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent_id`, `file_id`, `status`, `alias`) VALUES
(1, NULL, 179, 1, 'ot-0-do-2-let'),
(10, NULL, 180, 1, 'ot-2-do-4-let'),
(14, NULL, 181, 1, 'ot-5-i-starshe');

-- --------------------------------------------------------

--
-- Структура таблицы `category_description`
--

CREATE TABLE IF NOT EXISTS `category_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_description_category_id` (`category_id`),
  KEY `category_description_language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `category_description`
--

INSERT INTO `category_description` (`id`, `category_id`, `language_id`, `name`, `short_description`, `description`) VALUES
(1, 1, 1, 'от 0 до 2 лет', 'Подходят для детей от от 0 до 2 лет', 'Подходят для детей от от 0 до 2 лет'),
(2, 1, 3, 'from 0 to 2 yeasr', 'Подходят для детей от от 0 до 2 лет', 'Подходят для детей от от 0 до 2 лет'),
(3, 10, 1, 'от 2 до 4 лет', 'Подходят для детей от от 2 до 4 лет', 'Подходят для детей от от 2 до 4 лет'),
(4, 10, 3, 'from 2 to 4 years', 'Подходят для детей от от 2 до 4 лет', 'Подходят для детей от от 2 до 4 лет'),
(5, 14, 1, 'от 5 и старше', 'Подходят для детей от 5 и старше', 'Подходят для детей от 5 и старше'),
(6, 14, 3, 'from 5 и старше', 'Подходят для детей от 5 и старше', 'Подходят для детей от 5 и старше');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
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
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `user_name`, `user_email`, `status`, `created_at`) VALUES
(1, 'Rita', 'margomonogarova@gmail.com', 1, '2020-12-08 23:08:25');

-- --------------------------------------------------------

--
-- Структура таблицы `comment_answer`
--

CREATE TABLE IF NOT EXISTS `comment_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comment_answer_comment_id_fk` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `comment_answer`
--

INSERT INTO `comment_answer` (`id`, `comment_id`, `created_at`) VALUES
(1, 1, '2020-12-08 23:08:52');

-- --------------------------------------------------------

--
-- Структура таблицы `comment_answer_description`
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
-- Дамп данных таблицы `comment_answer_description`
--

INSERT INTO `comment_answer_description` (`id`, `comment_answer_id`, `language_id`, `description`) VALUES
(1, 1, 1, 'Текст ответа RU'),
(2, 1, 3, 'Текст  ответаENG');

-- --------------------------------------------------------

--
-- Структура таблицы `comment_answer_file`
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
-- Дамп данных таблицы `comment_answer_file`
--

INSERT INTO `comment_answer_file` (`id`, `file_id`, `comment_answer_id`) VALUES
(1, 185, 1),
(2, 186, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `comment_description`
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
-- Дамп данных таблицы `comment_description`
--

INSERT INTO `comment_description` (`id`, `comment_id`, `language_id`, `description`) VALUES
(1, 1, 1, 'Текст отзыва RU'),
(2, 1, 3, 'Текст  отзыва ENG');

-- --------------------------------------------------------

--
-- Структура таблицы `comment_file`
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
-- Дамп данных таблицы `comment_file`
--

INSERT INTO `comment_file` (`id`, `file_id`, `comment_id`) VALUES
(1, 184, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
-- Дамп данных таблицы `country`
--

INSERT INTO `country` (`id`, `alpha2`, `alpha3`, `status`, `file_id`, `currency_id`) VALUES
(1, 'RU', 'RUS', 1, 126, 1),
(2, 'GB', 'GBR', 1, 127, 2),
(4, 'TR', 'TUR', 1, 189, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `country_description`
--

CREATE TABLE IF NOT EXISTS `country_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_description_country_id_fk` (`country_id`),
  KEY `country_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `country_description`
--

INSERT INTO `country_description` (`id`, `country_id`, `language_id`, `name`) VALUES
(1, 1, 1, 'Россия'),
(2, 1, 3, 'Russia'),
(3, 2, 1, 'Англия'),
(4, 2, 3, 'England'),
(5, 4, 1, 'Турция'),
(6, 4, 3, 'Turkey');

-- --------------------------------------------------------

--
-- Структура таблицы `coupon`
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
-- Дамп данных таблицы `coupon`
--

INSERT INTO `coupon` (`id`, `code`, `discount`, `quantity`, `used`, `start_date`, `end_date`) VALUES
(2, '234', 45, 356456, 0, '2020-12-12 00:00:00', '2021-01-09 00:00:00'),
(3, '1', 12, 12, 0, '2020-12-01 00:00:00', '2020-12-03 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `name`, `code`, `alias`) VALUES
(1, 'Рубли', 'RUB', 'руб.'),
(2, 'Доллары', 'USD', 'USD'),
(3, 'Евро', 'EUR', 'EUR');

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `file`
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
(188, 'blog3', '1607516196blog3.png', 'image/png'),
(189, 'Turkey_29733', '1608056300Turkey_29733.png', 'image/png'),
(190, 'голова лисы', '1608061316голова лисы.jpg', 'image/jpeg'),
(191, '60EA2F44-DC30-43D8-B85B-94814A91C4DE', '160806278460EA2F44-DC30-43D8-B85B-94814A91C4DE.jpeg', 'image/jpeg'),
(192, '276B48CA-5BF2-4C69-ACAC-C8F88C2918CB', '1608063575276B48CA-5BF2-4C69-ACAC-C8F88C2918CB.jpeg', 'image/jpeg'),
(193, '681BB888-812D-4AE7-9341-F215D2BC2898', '1608063575681BB888-812D-4AE7-9341-F215D2BC2898.jpeg', 'image/jpeg'),
(194, '2B0580D4-CD39-4CE3-BC87-3E275193E149', '16080636572B0580D4-CD39-4CE3-BC87-3E275193E149.jpeg', 'image/jpeg'),
(195, '24E56038-9AF3-4BF7-9B20-53D83940C4FC', '160806365724E56038-9AF3-4BF7-9B20-53D83940C4FC.jpeg', 'image/jpeg'),
(196, '681BB888-812D-4AE7-9341-F215D2BC2898', '1608063657681BB888-812D-4AE7-9341-F215D2BC2898.jpeg', 'image/jpeg'),
(197, '1599155021Васильковый металлик', '16080637691599155021Васильковый металлик.png', 'image/png'),
(198, '0581670B-F249-4943-A125-71DFCAAFBCBA', '16080637890581670B-F249-4943-A125-71DFCAAFBCBA.jpeg', 'image/jpeg'),
(199, 'D07A114F-4CF8-47F6-A9ED-BA1030BABD8A', '1608063789D07A114F-4CF8-47F6-A9ED-BA1030BABD8A.jpeg', 'image/jpeg'),
(200, '1599155021Васильковый металлик', '16080640561599155021Васильковый металлик.png', 'image/png'),
(201, '35E90CAC-EC4D-4D32-82F4-536F970189B9', '160806418635E90CAC-EC4D-4D32-82F4-536F970189B9.jpeg', 'image/jpeg'),
(202, '37F0C709-7F10-49FA-B0B3-D6152C3A751B', '160806418637F0C709-7F10-49FA-B0B3-D6152C3A751B.jpeg', 'image/jpeg'),
(203, 'FA260F22-1089-4F35-B74B-8A257C536701', '1608064466FA260F22-1089-4F35-B74B-8A257C536701.jpeg', 'image/jpeg'),
(204, '99FC9B3E-4E8B-4629-8753-08914E55C619', '160806446699FC9B3E-4E8B-4629-8753-08914E55C619.jpeg', 'image/jpeg'),
(205, 'C864B392-AD4C-415D-8BCC-906CB62C74C0', '1608064466C864B392-AD4C-415D-8BCC-906CB62C74C0.jpeg', 'image/jpeg'),
(206, '9F05FC9F-751C-47EE-B4A2-C07910744A92', '16080649959F05FC9F-751C-47EE-B4A2-C07910744A92.jpeg', 'image/jpeg'),
(207, 'A07ABE3A-F155-4D66-BDC4-65B341153499', '1608064995A07ABE3A-F155-4D66-BDC4-65B341153499.jpeg', 'image/jpeg'),
(208, '7FC6A9FB-50AC-48E5-821A-B79B65D3A971', '16083971077FC6A9FB-50AC-48E5-821A-B79B65D3A971.JPG', 'image/jpeg');

-- --------------------------------------------------------

--
-- Структура таблицы `language`
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
-- Дамп данных таблицы `language`
--

INSERT INTO `language` (`id`, `name`, `alias`, `code`, `file_id`) VALUES
(1, 'RU', 'russian', 'ru,ru_RU.UTF-8,ru_RU,russian', 1),
(3, 'ENG', 'english', 'en_US.UTF-8,en_US,en-gb,en_gb,english', 172);

-- --------------------------------------------------------

--
-- Структура таблицы `new`
--

CREATE TABLE IF NOT EXISTS `new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `new_file_id_fk` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `new`
--

INSERT INTO `new` (`id`, `file_id`, `created_at`) VALUES
(1, 183, '2020-12-08 23:07:23'),
(2, 208, '2020-12-19 19:58:27');

-- --------------------------------------------------------

--
-- Структура таблицы `new_description`
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `new_description`
--

INSERT INTO `new_description` (`id`, `new_id`, `language_id`, `name`, `description`) VALUES
(1, 1, 1, 'Название RU', 'Текст RU'),
(2, 1, 3, 'Название ENG', 'Текст ENG'),
(3, 2, 1, '12312', '123123'),
(4, 2, 3, '123123', '123123');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
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
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `first_name`, `last_name`, `email`, `telephone`, `country`, `city`, `postcode`, `address`, `payment_method_id`, `shipping_method_id`, `comment`, `total_price`, `currency`, `status_id`, `created_at`) VALUES
(2, 'Маргарита', 'Моногаровата1', 'argo@gmail.com1', '(999)16981421', 'Англия', 'Казань1', '1410021', '12', '1', '1', 'Ничего интересного', '4000.00', 'руб', 1, '2020-11-27');

-- --------------------------------------------------------

--
-- Структура таблицы `order_payment_method`
--

CREATE TABLE IF NOT EXISTS `order_payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_payment_method`
--

INSERT INTO `order_payment_method` (`id`, `name`) VALUES
(1, 'Наличными курьеру'),
(2, 'По карте курьеру'),
(3, 'Оплата на карту'),
(4, 'Оплата с помощью Яндекс Касс');

-- --------------------------------------------------------

--
-- Структура таблицы `order_payment_method_description`
--

CREATE TABLE IF NOT EXISTS `order_payment_method_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_payment_method_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_payment_method_description_order_payment_method_id_fk` (`order_payment_method_id`),
  KEY `order_payment_method_description_language_id_fk` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_payment_method_description`
--

INSERT INTO `order_payment_method_description` (`id`, `order_payment_method_id`, `language_id`, `name`) VALUES
(1, 1, 1, 'Наличными '),
(2, 1, 3, 'Cash ');

-- --------------------------------------------------------

--
-- Структура таблицы `order_product`
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
-- Структура таблицы `order_shipping_method`
--

CREATE TABLE IF NOT EXISTS `order_shipping_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_shipping_method`
--

INSERT INTO `order_shipping_method` (`id`, `name`) VALUES
(1, 'Доставка курьером по Казани'),
(2, 'Самовывоз в Казани'),
(3, 'Доставка Почтой России');

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Новый'),
(2, 'Ожидание оплаты'),
(3, 'Доставка'),
(4, 'Выполнен'),
(5, 'Отменен\r\n');

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `page`
--

INSERT INTO `page` (`id`, `name`, `alias`) VALUES
(1, 'Доставка и оплата', 'dostavka-i-oplata'),
(2, 'О нас', 'o-nas');

-- --------------------------------------------------------

--
-- Структура таблицы `page_description`
--

CREATE TABLE IF NOT EXISTS `page_description` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_page_description_language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `page_description`
--

INSERT INTO `page_description` (`id`, `page_id`, `language_id`, `description`) VALUES
(1, 1, 1, '<p><img src=\"data:image/jpeg;base64,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\" data-filename=\"photo_2020-11-14_19-47-03.jpg\" style=\"width: 25%;\">dfgdfgdfgsf ds sdf gfhxfgh</p>\r\n                                            \r\n                                            ');
INSERT INTO `page_description` (`id`, `page_id`, `language_id`, `description`) VALUES
(2, 2, 1, '<p><img src=\"data:image/jpeg;base64,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\" data-filename=\"photo_2020-11-03_15-01-47 (2).jpg\" style=\"width: 226px; float: left;\" class=\"note-float-left\">О нас на русском языке\r\n                                            </p>\r\n                                            '),
(3, 1, 3, 'Об оплате на русском языке\r\n                                            \r\n                                            \r\n                                            \r\n                                            \r\n                                            \r\n                                            '),
(4, 2, 3, 'О нас на английском языке                                            \r\n                                            \r\n                                            ');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `sale` int(3) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `popular` tinyint(1) DEFAULT '0',
  `alias` varchar(255) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_category_id_fk` (`category_id`),
  KEY `product_file_id_fk` (`file_id`),
  KEY `product_size_id_fk` (`size_id`),
  KEY `product_type_id_fk` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `category_id`, `size_id`, `type_id`, `price`, `weight`, `sale`, `file_id`, `status`, `popular`, `alias`, `sort`, `created_at`) VALUES
(8, 1, 4, 1, '0.00', 0, NULL, 117, 1, 0, 'dsf', 0, NULL),
(11, 10, 4, 1, '0.00', 0, NULL, 120, 0, 0, '33333333', 0, NULL),
(12, 10, 4, 1, '0.00', 0, NULL, 121, 1, 0, 'yc', 0, NULL),
(13, 10, 4, 1, '0.00', 0, NULL, 122, 0, 0, '34', 0, NULL),
(19, 1, 3, 1, '1950.00', 0, 0, 190, 1, 1, 'golova-lisy', 0, NULL),
(20, 1, 3, 1, '1950.00', 0, 0, 191, 1, 1, 'golova-volka', 0, NULL),
(21, 1, 5, 1, '5500.00', 0, 0, 192, 1, 1, 'koala', 0, NULL),
(22, 1, 5, 1, '6700.00', 0, 0, 194, 1, 1, 'lisa', 0, NULL),
(24, 1, 4, 1, '3500.00', 0, 0, 198, 1, NULL, 'medved', 0, NULL),
(26, 10, 4, 4, '3490.00', 0, 0, 201, 1, NULL, 'parkovka-garaj', 0, NULL),
(27, 10, 5, 3, '5590.00', 0, 0, 203, 1, NULL, 'kukolnyy-domik', 0, NULL),
(28, 1, 5, 2, '2590.00', 0, 0, 206, 1, NULL, 'loshadka-kachalka', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `product_country`
--

CREATE TABLE IF NOT EXISTS `product_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_country_product_id_fk` (`product_id`),
  KEY `product_country_country_id_fk` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_description`
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `product_description`
--

INSERT INTO `product_description` (`id`, `product_id`, `language_id`, `name`, `description`, `tag`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(44, 19, 1, '\"Голова лисы\"', '', '', '', '', ''),
(45, 19, 3, 'fox head', '', '', '', '', ''),
(46, 20, 1, '\"голова волка\"', '', '', '', '', ''),
(47, 20, 3, 'wolf head', '', '', '', '', ''),
(48, 21, 1, '\"Коала\"', '', '', '', '', ''),
(49, 21, 3, ' koala', '', '', '', '', ''),
(50, 22, 1, '\"Лиса\"', '', '', '', '', ''),
(51, 22, 3, 'Fox', '', '', '', '', ''),
(54, 24, 1, '\"Медведь\"', '', '', '', '', ''),
(55, 24, 3, 'bear', '', '', '', '', ''),
(58, 26, 1, '\"Парковка-гараж\"', '', '', '', '', ''),
(59, 26, 3, 'Parking garage', '', '', '', '', ''),
(60, 27, 1, '\"Кукольный домик\"', '', '', '', '', ''),
(61, 27, 3, 'Dollhouse', '', '', '', '', ''),
(62, 28, 1, '\"Лошадка-качалка\"', '', '', '', '', ''),
(63, 28, 3, 'Rocking horse', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `product_file`
--

CREATE TABLE IF NOT EXISTS `product_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_file_file_id_fk` (`file_id`),
  KEY `product_file_product_id_fk` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `product_file`
--

INSERT INTO `product_file` (`id`, `file_id`, `product_id`) VALUES
(12, 193, 21),
(13, 195, 22),
(14, 196, 22),
(15, 199, 24),
(16, 202, 26),
(17, 204, 27),
(18, 205, 27),
(19, 207, 28);

-- --------------------------------------------------------

--
-- Структура таблицы `product_recommendations`
--

CREATE TABLE IF NOT EXISTS `product_recommendations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `recommendation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id_product_id_fk` (`product_id`),
  KEY `product_id_recommendation_id_fk` (`recommendation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `rate`
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
-- Дамп данных таблицы `rate`
--

INSERT INTO `rate` (`id`, `currency_id`, `rate`, `date`) VALUES
(7, 2, '73.88', '2021-01-06 00:00:00'),
(8, 3, '90.79', '2021-01-06 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `name`, `permission`) VALUES
(1, 'Главный администратор', 100),
(2, 'Администратор', 50);

-- --------------------------------------------------------

--
-- Структура таблицы `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `sale`
--

INSERT INTO `sale` (`id`, `status`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sale_description`
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `sale_description`
--

INSERT INTO `sale_description` (`id`, `sale_id`, `language_id`, `name`, `sale`, `description`) VALUES
(1, 1, 1, 'Новогодняя распродажа', 'Скидка 10%!', 'В честь нового года мы устраиваем зимнюю распродажу'),
(2, 1, 3, 'Новогодняя ', 'fsdf', 'sdfsfdsf');

-- --------------------------------------------------------

--
-- Структура таблицы `size`
--

CREATE TABLE IF NOT EXISTS `size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `size`
--

INSERT INTO `size` (`id`) VALUES
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Структура таблицы `size_description`
--

CREATE TABLE IF NOT EXISTS `size_description` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `size_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_size_description_size_id` (`size_id`),
  KEY `FK_size_description_language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `size_description`
--

INSERT INTO `size_description` (`id`, `size_id`, `language_id`, `name`) VALUES
(1, 3, 1, 'Маленький'),
(2, 3, 3, 'Small'),
(3, 4, 1, 'Средний'),
(4, 4, 3, 'Middle'),
(5, 5, 1, 'Большой'),
(6, 5, 3, 'Large');

-- --------------------------------------------------------

--
-- Структура таблицы `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `type`
--

INSERT INTO `type` (`id`) VALUES
(1),
(2),
(3),
(4);

-- --------------------------------------------------------

--
-- Структура таблицы `type_description`
--

CREATE TABLE IF NOT EXISTS `type_description` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_type_description_type_id` (`type_id`),
  KEY `FK_type_description_language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `type_description`
--

INSERT INTO `type_description` (`id`, `type_id`, `language_id`, `name`) VALUES
(1, 1, 1, 'Бизиборды'),
(2, 1, 3, 'Бизиборды Eng'),
(3, 3, 1, 'Кукольные домики'),
(4, 3, 3, 'Кукольные домики'),
(5, 2, 1, 'Лошадки-качалки'),
(6, 2, 3, 'Лошадки-качалки'),
(7, 4, 1, 'Парковка-гараж'),
(8, 4, 3, 'Парковка-гараж');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
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
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `salt`, `active_hex`, `role_id`) VALUES
(1, 'Маргарита Моногарова', 'margomonogarova@gmail.com', '3518eaf64ced211c19c16e606c1aa049', 'e0393605', 'c9b07bf4beefedaabcf3828a3675b99a', 1),
(5, 'Динара', 'h.d.n@list.ru', '19024cb8d4cf5d570e4c975bef94f88b', '87001643', 'f11c913118785d448940343ebe06c457', 2);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `blog_description`
--
ALTER TABLE `blog_description`
  ADD CONSTRAINT `blog_description_blog_id_fk` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `category_description`
--
ALTER TABLE `category_description`
  ADD CONSTRAINT `category_description_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_description_language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment_answer`
--
ALTER TABLE `comment_answer`
  ADD CONSTRAINT `comment_answer_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment_answer_description`
--
ALTER TABLE `comment_answer_description`
  ADD CONSTRAINT `comment_answer_description_id_fk` FOREIGN KEY (`comment_answer_id`) REFERENCES `comment_answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_answer_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment_answer_file`
--
ALTER TABLE `comment_answer_file`
  ADD CONSTRAINT `comment_answer_file_comment_id_fk` FOREIGN KEY (`comment_answer_id`) REFERENCES `comment_answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_answer_file_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Ограничения внешнего ключа таблицы `comment_description`
--
ALTER TABLE `comment_description`
  ADD CONSTRAINT `comment_description_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment_file`
--
ALTER TABLE `comment_file`
  ADD CONSTRAINT `comment_file_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_file_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Ограничения внешнего ключа таблицы `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `country_currency_id_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `country_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Ограничения внешнего ключа таблицы `country_description`
--
ALTER TABLE `country_description`
  ADD CONSTRAINT `country_description_country_id_fk` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `country_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `language`
--
ALTER TABLE `language`
  ADD CONSTRAINT `languages_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Ограничения внешнего ключа таблицы `new`
--
ALTER TABLE `new`
  ADD CONSTRAINT `new_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `new_description`
--
ALTER TABLE `new_description`
  ADD CONSTRAINT `new_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `new_description_new_id_fk` FOREIGN KEY (`new_id`) REFERENCES `new` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_payment_method_description`
--
ALTER TABLE `order_payment_method_description`
  ADD CONSTRAINT `order_payment_method_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_payment_method_description_order_payment_method_id_fk` FOREIGN KEY (`order_payment_method_id`) REFERENCES `order_payment_method` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Ограничения внешнего ключа таблицы `page_description`
--
ALTER TABLE `page_description`
  ADD CONSTRAINT `FK_page_description_language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_size_id_fk` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_country`
--
ALTER TABLE `product_country`
  ADD CONSTRAINT `product_country_country_id_fk` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_country_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_description`
--
ALTER TABLE `product_description`
  ADD CONSTRAINT `product_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_description_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_file`
--
ALTER TABLE `product_file`
  ADD CONSTRAINT `product_file_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `product_file_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_recommendations`
--
ALTER TABLE `product_recommendations`
  ADD CONSTRAINT `product_id_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_id_recommendation_id_fk` FOREIGN KEY (`recommendation_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_currency_id_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sale_description`
--
ALTER TABLE `sale_description`
  ADD CONSTRAINT `sale_description_language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_description_sale_id_fk` FOREIGN KEY (`sale_id`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `size_description`
--
ALTER TABLE `size_description`
  ADD CONSTRAINT `FK_size_description_language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_size_description_size_id` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `type_description`
--
ALTER TABLE `type_description`
  ADD CONSTRAINT `FK_type_description_language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_type_description_type_id` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

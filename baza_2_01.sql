-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               5.5.37-0ubuntu0.12.04.1 - (Ubuntu)
-- Serwer OS:                    debian-linux-gnu
-- HeidiSQL Wersja:              8.1.0.4545
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Zrzut struktury tabela ral.car_car
CREATE TABLE IF NOT EXISTS `car_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mileage` bigint(20) DEFAULT NULL,
  `value` bigint(20) DEFAULT NULL,
  `upkeep` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `last_name_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.car_car: 6 rows
DELETE FROM `car_car`;
/*!40000 ALTER TABLE `car_car` DISABLE KEYS */;
INSERT INTO `car_car` (`id`, `model_id`, `name`, `mileage`, `value`, `upkeep`, `created_at`, `updated_at`, `deleted_at`, `last_name_change`) VALUES
	(1, 1, 'Samochod 2144673638', 0, 100000, 1000, '2014-12-18 15:43:00', '2014-12-18 15:43:00', NULL, NULL),
	(2, 2, 'Samochod 169564519', 0, 100000, 1000, '2014-12-18 15:43:09', '2014-12-18 15:43:09', NULL, NULL),
	(3, 0, 'Samochod 143502463', 0, 100000, 1000, '2014-12-18 19:31:05', '2014-12-18 19:31:05', NULL, NULL),
	(4, 2, 'Samochod 1315149166', 0, 100000, 1000, '2014-12-18 19:40:50', '2014-12-18 19:40:50', NULL, NULL),
	(5, 2, 'Samochod 1628820089', 0, 100000, 1000, '2014-12-18 19:42:44', '2014-12-18 19:42:44', NULL, NULL),
	(6, 1, 'super', 0, 100000, 1000, '2014-12-20 20:45:45', '2014-12-28 22:04:35', NULL, '2014-12-28 22:04:35');
/*!40000 ALTER TABLE `car_car` ENABLE KEYS */;


-- Zrzut struktury tabela ral.car_car_models
CREATE TABLE IF NOT EXISTS `car_car_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `capacity` bigint(20) DEFAULT NULL,
  `horsepower` bigint(20) DEFAULT NULL,
  `max_speed` bigint(20) DEFAULT NULL,
  `acceleration` float(10,2) DEFAULT NULL,
  `wheel_drive` enum('front','rear','4x4') DEFAULT NULL,
  `league` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.car_car_models: 2 rows
DELETE FROM `car_car_models`;
/*!40000 ALTER TABLE `car_car_models` DISABLE KEYS */;
INSERT INTO `car_car_models` (`id`, `name`, `capacity`, `horsepower`, `max_speed`, `acceleration`, `wheel_drive`, `league`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Fiat Cinquecento', 1108, 54, 150, 13.50, 'front', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(2, 'Toyota Corolla', 1598, 132, 200, 10.00, 'front', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `car_car_models` ENABLE KEYS */;


-- Zrzut struktury tabela ral.league_league
CREATE TABLE IF NOT EXISTS `league_league` (
  `league_name` float(10,2) NOT NULL DEFAULT '0.00',
  `league_level` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`league_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.league_league: 3 rows
DELETE FROM `league_league`;
/*!40000 ALTER TABLE `league_league` DISABLE KEYS */;
INSERT INTO `league_league` (`league_name`, `league_level`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1.00, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(2.10, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(2.20, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `league_league` ENABLE KEYS */;


-- Zrzut struktury tabela ral.league_season
CREATE TABLE IF NOT EXISTS `league_season` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_name` float(10,2) DEFAULT NULL,
  `team_id` bigint(20) DEFAULT NULL,
  `points` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_name_idx` (`league_name`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.league_season: 3 rows
DELETE FROM `league_season`;
/*!40000 ALTER TABLE `league_season` DISABLE KEYS */;
INSERT INTO `league_season` (`id`, `league_name`, `team_id`, `points`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1.00, 8, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(12, 1.00, 70, NULL, '2014-12-20 20:45:45', '2014-12-20 20:45:45', NULL),
	(11, 1.00, 69, NULL, '2014-12-18 19:42:44', '2014-12-18 19:42:44', NULL);
/*!40000 ALTER TABLE `league_season` ENABLE KEYS */;


-- Zrzut struktury tabela ral.people_people
CREATE TABLE IF NOT EXISTS `people_people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `age` bigint(20) DEFAULT NULL,
  `composure` bigint(20) DEFAULT NULL,
  `speed` bigint(20) DEFAULT NULL,
  `regularity` bigint(20) DEFAULT NULL,
  `reflex` bigint(20) DEFAULT NULL,
  `on_gravel` bigint(20) DEFAULT NULL,
  `on_tarmac` bigint(20) DEFAULT NULL,
  `on_snow` bigint(20) DEFAULT NULL,
  `in_rain` bigint(20) DEFAULT NULL,
  `form` bigint(20) DEFAULT NULL,
  `dictate_rhytm` bigint(20) DEFAULT NULL,
  `diction` bigint(20) DEFAULT NULL,
  `route_description` bigint(20) DEFAULT NULL,
  `intelligence` bigint(20) DEFAULT NULL,
  `talent` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.people_people: 45 rows
DELETE FROM `people_people`;
/*!40000 ALTER TABLE `people_people` DISABLE KEYS */;
INSERT INTO `people_people` (`id`, `first_name`, `last_name`, `job`, `age`, `composure`, `speed`, `regularity`, `reflex`, `on_gravel`, `on_tarmac`, `on_snow`, `in_rain`, `form`, `dictate_rhytm`, `diction`, `route_description`, `intelligence`, `talent`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'PPryKP', 'JTeZ', 'driver', 18, 4, 7, 2, 5, 4, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 7, '2014-09-19 12:45:33', '2014-09-19 12:45:33', NULL),
	(2, 'HwTij', 'ZgCgsDJAOg', 'driver', 20, 4, 4, 4, 8, 6, 7, 4, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 12:52:15', '2014-09-19 12:52:15', NULL),
	(3, 'phctgx', 'crWW', 'driver', 18, 4, 5, 8, 4, 4, 5, 4, 7, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 13:18:27', '2014-09-19 13:18:27', NULL),
	(4, 'LEaqaD', 'jgneTbuEfW', 'driver', 18, 8, 5, 4, 4, 7, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 13:18:54', '2014-09-19 13:18:54', NULL),
	(5, 'urhIK', 'AeGoZ', 'driver', 21, 4, 4, 4, 8, 4, 5, 6, 6, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(6, 'ghSbcwUOG', 'NHlBXTHtlx', 'driver', 20, 4, 5, 5, 6, 4, 4, 6, 7, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(7, 'lCQZ', 'BQRgvrJOyx', 'driver', 21, 5, 4, 4, 5, 4, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 7, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(8, 'cNLbaiWFk', 'PQYgHBoycX', 'driver', 20, 5, 4, 4, 8, 4, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 5, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(9, 'nbNQa', 'rFwWCJS', 'driver', 18, 4, 4, 4, 5, 5, 4, 4, 9, 3, NULL, NULL, NULL, NULL, 6, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(10, 'LgEtn', 'UALlmQkoX', 'driver', 19, 4, 4, 5, 4, 7, 6, 5, 4, 3, NULL, NULL, NULL, NULL, 6, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(11, 'sXcfi', 'mHLoGIB', 'driver', 21, 4, 7, 6, 4, 5, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(12, 'hYyS', 'tSZxktD', 'driver', 20, 5, 4, 4, 4, 4, 5, 8, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(13, 'aaSbliraB', 'ZEeLiFGgm', 'driver', 20, 4, 4, 4, 9, 5, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 7, '2014-09-19 14:32:11', '2014-09-19 14:32:11', NULL),
	(14, 'veJDZxWcDp', 'wJRtNjXy', 'driver', 18, 4, 8, 4, 4, 8, 4, 5, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:11', '2014-09-19 14:32:11', NULL),
	(15, 'OFswT', 'OLLEyXX', 'driver', 21, 6, 4, 4, 6, 4, 5, 5, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:11', '2014-09-19 14:32:11', NULL),
	(16, 'iMDzXISX', 'ZCJDcUVmRT', 'driver', 20, 6, 4, 6, 4, 4, 4, 5, 5, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:11', '2014-09-19 14:32:11', NULL),
	(17, 'yVVvQg', 'WzUYpCBe', 'driver', 20, 7, 4, 7, 4, 4, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 6, '2014-09-19 14:32:51', '2014-09-19 14:32:51', NULL),
	(18, 'gSTiIqO', 'mrRDLaBbJ', 'driver', 19, 7, 7, 4, 4, 4, 4, 6, 5, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:51', '2014-09-19 14:32:51', NULL),
	(19, 'OnEEgpP', 'IQEJHI', 'driver', 19, 4, 4, 4, 6, 4, 4, 5, 4, 3, NULL, NULL, NULL, NULL, 7, '2014-09-19 14:32:51', '2014-09-19 14:32:51', NULL),
	(20, 'cPOgR', 'BIVxYAVds', 'driver', 18, 4, 7, 4, 4, 6, 4, 5, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:32:51', '2014-09-19 14:32:51', NULL),
	(21, 'JbWPDLAgQl', 'Rgyfg', 'driver', 21, 7, 4, 4, 7, 4, 4, 7, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:33:33', '2014-09-19 14:33:33', NULL),
	(22, 'oHXHs', 'hHdhlfZJC', 'driver', 19, 6, 4, 7, 7, 4, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:33:33', '2014-09-19 14:33:33', NULL),
	(23, 'YCgFAOYqV', 'tcRzbBbP', 'pilot', 19, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 6, 6, 5, NULL, 6, '2014-09-19 14:33:33', '2014-09-19 14:33:33', NULL),
	(24, 'dgrp', 'DUJxGu', 'driver', 18, 4, 4, 4, 5, 6, 4, 7, 6, 3, NULL, NULL, NULL, NULL, 5, '2014-09-19 14:33:50', '2014-09-19 14:33:50', NULL),
	(25, 'ttpOzwkM', 'yogxjrSiH', 'driver', 21, 6, 6, 6, 4, 5, 5, 4, 5, 3, NULL, NULL, NULL, NULL, 4, '2014-09-19 14:33:50', '2014-09-19 14:33:50', NULL),
	(26, 'PLIay', 'yNGWXYP', 'pilot', 18, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 7, 7, 5, 4, 7, '2014-09-19 14:33:50', '2014-09-19 14:33:50', NULL),
	(27, 'jzRvpDdp', 'DNPj', 'pilot', 19, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 6, 6, 5, 7, 4, '2014-09-19 14:33:50', '2014-09-19 14:33:50', NULL),
	(28, 'adRWo', 'bSFXx', 'driver', 19, 2, 4, 2, 5, 4, 2, 5, 7, 3, NULL, NULL, NULL, NULL, 2, '2014-09-19 14:42:50', '2014-09-19 14:42:50', NULL),
	(29, 'zAvvOHx', 'nudMTCFT', 'driver', 19, 2, 4, 2, 2, 7, 3, 2, 4, 3, NULL, NULL, NULL, NULL, 7, '2014-09-19 14:42:50', '2014-09-19 14:42:50', NULL),
	(30, 'aRENFoVSv', 'FbNNwBty', 'pilot', 21, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 2, 2, 6, 6, 4, '2014-09-19 14:42:50', '2014-09-19 14:42:50', NULL),
	(31, 'uHObnQ', 'amquLTNpk', 'pilot', 21, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 2, 5, 3, 8, 3, '2014-09-19 14:42:50', '2014-09-19 14:42:50', NULL),
	(32, 'aJZwfJggM', 'OXKaHQU', 'driver', 21, 3, 2, 4, 7, 2, 3, 5, 5, 3, NULL, NULL, NULL, NULL, 2, '2014-09-19 14:42:51', '2014-09-19 14:42:51', NULL),
	(33, 'FnwvhB', 'bdxELhTVFg', 'driver', 18, 5, 4, 6, 2, 2, 2, 4, 2, 3, NULL, NULL, NULL, NULL, 6, '2014-09-19 14:42:51', '2014-09-19 14:42:51', NULL),
	(34, 'qFULMwF', 'zcskkmgQt', 'pilot', 19, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 2, 5, 4, NULL, 2, '2014-09-19 14:42:51', '2014-09-19 14:42:51', NULL),
	(35, 'aHPvdiXChx', 'oJeUl', 'pilot', 20, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 4, 2, NULL, 4, '2014-09-19 14:42:51', '2014-09-19 14:42:51', NULL),
	(36, 'BrAkiVf', 'lHfnRcUirL', 'driver', 20, 5, 3, 6, 2, 2, 2, 2, 6, 3, NULL, NULL, NULL, NULL, 5, '2014-12-18 07:34:51', '2014-12-18 07:34:51', NULL),
	(37, 'WzgbMXe', 'gwsyGIwo', 'pilot', 19, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 9, 6, 8, 5, 2, '2014-12-18 07:34:51', '2014-12-18 07:34:51', NULL),
	(38, 'hbOYVgz', 'PWnSO', 'driver', 19, 0, 0, 0, 0, 0, 0, 0, 0, 3, NULL, NULL, NULL, NULL, 0, '2014-12-18 19:31:05', '2014-12-18 19:31:05', NULL),
	(39, 'CBwReDT', 'CPYcXNYlG', 'pilot', 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 0, 0, 5, 0, '2014-12-18 19:31:05', '2014-12-18 19:31:05', NULL),
	(40, 'PmJmpsvVi', 'CpHHSINhcA', 'driver', 21, 6, 6, 4, 4, 6, 4, 6, 4, 3, NULL, NULL, NULL, NULL, 5, '2014-12-18 19:40:50', '2014-12-18 19:40:50', NULL),
	(41, 'sIVapNJd', 'MDTzfYNDPt', 'pilot', 18, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 8, 4, 8, 5, 4, '2014-12-18 19:40:50', '2014-12-18 19:40:50', NULL),
	(42, 'IdulvPRrf', 'PjOqmZ', 'driver', 18, 4, 4, 5, 4, 5, 6, 7, 4, 3, NULL, NULL, NULL, NULL, 6, '2014-12-18 19:42:44', '2014-12-18 19:42:44', NULL),
	(43, 'tOQjGh', 'XXyLo', 'pilot', 20, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 7, 4, 8, 9, 4, '2014-12-18 19:42:44', '2014-12-18 19:42:44', NULL),
	(44, 'Marc', 'Nowak', 'driver', 20, 4, 7, 6, 4, 6, 4, 6, 4, 3, NULL, NULL, NULL, NULL, 4, '2014-12-20 20:45:45', '2014-12-20 20:45:45', NULL),
	(45, 'Jan', 'Smith', 'pilot', 19, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 8, 6, 9, 8, 4, '2014-12-20 20:45:45', '2014-12-20 20:45:45', NULL);
/*!40000 ALTER TABLE `people_people` ENABLE KEYS */;


-- Zrzut struktury tabela ral.rally_accident
CREATE TABLE IF NOT EXISTS `rally_accident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `damage` decimal(18,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.rally_accident: 4 rows
DELETE FROM `rally_accident`;
/*!40000 ALTER TABLE `rally_accident` DISABLE KEYS */;
INSERT INTO `rally_accident` (`id`, `name`, `damage`, `created_at`, `updated_at`) VALUES
	(1, 'Damaged wheel', 5.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 'Hit tree', 100.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(3, '5 gear not working', 20.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(4, 'Damaged windscreens', 15.50, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `rally_accident` ENABLE KEYS */;


-- Zrzut struktury tabela ral.rally_crew
CREATE TABLE IF NOT EXISTS `rally_crew` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rally_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `pilot_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `risk` varchar(255) DEFAULT NULL,
  `in_race` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rally_id_idx` (`rally_id`),
  KEY `rally_crew_team_id_team_team_id` (`team_id`),
  KEY `rally_crew_pilot_id_people_people_id` (`pilot_id`),
  KEY `rally_crew_driver_id_people_people_id` (`driver_id`),
  KEY `rally_crew_car_id_car_car_id` (`car_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.rally_crew: 3 rows
DELETE FROM `rally_crew`;
/*!40000 ALTER TABLE `rally_crew` DISABLE KEYS */;
INSERT INTO `rally_crew` (`id`, `rally_id`, `team_id`, `driver_id`, `pilot_id`, `car_id`, `risk`, `in_race`, `created_at`, `updated_at`) VALUES
	(1, 1, 8, 36, 37, 1, 'very-big', 1, '0000-00-00 00:00:00', '2014-12-25 13:15:23'),
	(11, 1, 69, 42, 43, 5, 'normal', 1, '2014-12-20 20:35:45', '2014-12-25 13:15:23'),
	(12, 1, 70, 44, 45, 6, 'big', 1, '2014-12-20 20:47:57', '2014-12-25 13:15:22');
/*!40000 ALTER TABLE `rally_crew` ENABLE KEYS */;


-- Zrzut struktury tabela ral.rally_rally
CREATE TABLE IF NOT EXISTS `rally_rally` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `surface` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.rally_rally: 3 rows
DELETE FROM `rally_rally`;
/*!40000 ALTER TABLE `rally_rally` DISABLE KEYS */;
INSERT INTO `rally_rally` (`id`, `name`, `slug`, `date`, `active`, `surface`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Rajd Polski', 'rajd-polski', '2015-01-25 05:31:48', 1, 'tarmac', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(2, 'Rajd Chin', 'rajd-chin', '2015-01-30 08:46:51', 1, 'gravel', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(3, 'Rajd Czech', 'rajd-czech', '2014-12-29 19:45:08', 1, 'tarmac', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `rally_rally` ENABLE KEYS */;


-- Zrzut struktury tabela ral.rally_stage
CREATE TABLE IF NOT EXISTS `rally_stage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rally_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `length` decimal(18,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `min_time` time DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rally_id_idx` (`rally_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.rally_stage: 3 rows
DELETE FROM `rally_stage`;
/*!40000 ALTER TABLE `rally_stage` DISABLE KEYS */;
INSERT INTO `rally_stage` (`id`, `rally_id`, `name`, `length`, `date`, `min_time`, `created_at`, `updated_at`) VALUES
	(1, 1, 'OS 11', 6.75, NULL, '00:03:15', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 1, 'OS 12', 14.41, NULL, '00:07:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(3, 1, 'OS 13', 15.76, NULL, '00:07:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `rally_stage` ENABLE KEYS */;


-- Zrzut struktury tabela ral.rally_stage_result
CREATE TABLE IF NOT EXISTS `rally_stage_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stage_id` int(11) DEFAULT NULL,
  `crew_id` varchar(255) DEFAULT NULL,
  `base_time` time DEFAULT NULL,
  `random_factor` decimal(4,2) DEFAULT NULL,
  `accident_id` int(11) DEFAULT NULL,
  `accident_random_factor` decimal(4,2) DEFAULT NULL,
  `out_of_race` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stage_id_idx` (`stage_id`),
  KEY `accident_id_idx` (`accident_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.rally_stage_result: 3 rows
DELETE FROM `rally_stage_result`;
/*!40000 ALTER TABLE `rally_stage_result` DISABLE KEYS */;
INSERT INTO `rally_stage_result` (`id`, `stage_id`, `crew_id`, `base_time`, `random_factor`, `accident_id`, `accident_random_factor`, `out_of_race`, `created_at`, `updated_at`) VALUES
	(5, 1, '11', '00:05:22', NULL, NULL, NULL, 0, '2014-12-25 13:35:18', '2014-12-25 13:35:18'),
	(6, 1, '12', '00:04:02', NULL, NULL, NULL, 0, '2014-12-25 13:35:18', '2014-12-25 13:35:18'),
	(7, 1, '1', '00:03:48', NULL, 4, NULL, 0, '2014-12-25 13:35:18', '2014-12-25 13:35:18');
/*!40000 ALTER TABLE `rally_stage_result` ENABLE KEYS */;


-- Zrzut struktury tabela ral.rally_surface
CREATE TABLE IF NOT EXISTS `rally_surface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rally_id` int(11) DEFAULT NULL,
  `surface` varchar(255) DEFAULT NULL,
  `percentage` float(4,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rally_id_idx` (`rally_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.rally_surface: 3 rows
DELETE FROM `rally_surface`;
/*!40000 ALTER TABLE `rally_surface` DISABLE KEYS */;
INSERT INTO `rally_surface` (`id`, `rally_id`, `surface`, `percentage`, `created_at`, `updated_at`) VALUES
	(1, 1, 'tarmac', 75.60, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 1, 'gravel', 24.40, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(3, 1, 'rain', 20.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `rally_surface` ENABLE KEYS */;


-- Zrzut struktury tabela ral.team_team
CREATE TABLE IF NOT EXISTS `team_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `driver1_id` int(11) DEFAULT NULL,
  `driver2_id` int(11) DEFAULT NULL,
  `pilot1_id` int(11) DEFAULT NULL,
  `pilot2_id` int(11) DEFAULT NULL,
  `car1_id` int(11) DEFAULT NULL,
  `car2_id` int(11) DEFAULT NULL,
  `league_name` float(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.team_team: 9 rows
DELETE FROM `team_team`;
/*!40000 ALTER TABLE `team_team` DISABLE KEYS */;
INSERT INTO `team_team` (`id`, `user_id`, `name`, `driver1_id`, `driver2_id`, `pilot1_id`, `pilot2_id`, `car1_id`, `car2_id`, `league_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(2, NULL, 'Team1111', 5, 6, 7, 8, NULL, NULL, NULL, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(3, NULL, 'Team111', 9, 10, 11, 12, NULL, NULL, NULL, '2014-09-19 14:32:10', '2014-09-19 14:32:10', NULL),
	(4, NULL, 'Team11', 13, 14, 15, 16, NULL, NULL, NULL, '2014-09-19 14:32:11', '2014-09-19 14:32:11', NULL),
	(5, NULL, 'Team1', 24, 25, 26, 27, NULL, NULL, NULL, '2014-09-19 14:33:50', '2014-09-19 14:33:50', NULL),
	(6, NULL, 'Team5', 28, 29, 30, 31, NULL, NULL, NULL, '2014-09-19 14:42:50', '2014-09-19 14:42:50', NULL),
	(7, NULL, 'Team55', 32, 33, 34, 35, NULL, NULL, NULL, '2014-09-19 14:42:51', '2014-09-19 14:42:51', NULL),
	(8, 1, 'Team_1', 36, NULL, 37, NULL, 2, NULL, 1.00, '2014-12-18 07:34:51', '2014-12-18 15:43:09', NULL),
	(69, 2, 'Team_2', 42, 38, 43, 39, 5, NULL, 1.00, '2014-12-18 19:42:44', '2014-12-18 19:42:44', NULL),
	(70, 3, 'Team_3', 44, NULL, 45, NULL, 6, NULL, 1.00, '2014-12-20 20:45:45', '2014-12-20 20:45:45', NULL);
/*!40000 ALTER TABLE `team_team` ENABLE KEYS */;


-- Zrzut struktury tabela ral.user_user
CREATE TABLE IF NOT EXISTS `user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli ral.user_user: 3 rows
DELETE FROM `user_user`;
/*!40000 ALTER TABLE `user_user` DISABLE KEYS */;
INSERT INTO `user_user` (`id`, `first_name`, `last_name`, `email`, `username`, `salt`, `password`, `role`, `token`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, NULL, NULL, 'tomasz.kardas20@gmail.com', NULL, '8ea254b92f4e4f51d58a82c90db30a19', '1eb3a4c2eba67940505440f38c6ef53e', 'user', '6b527911d835be91ae0dd9bb83c12176', 1, '2014-12-18 07:33:47', '2014-12-18 07:34:51', NULL),
	(2, NULL, NULL, 'jan@nowak.pl', NULL, '4b6096565ec212ee187898c2491dc7c6', '7b8ebbeaa6d05c82303c77ce4e3d6faf', 'user', 'a74bf84b76174df96aa9ccfb250f855e', 0, '2014-12-18 19:12:05', '2014-12-18 19:42:44', NULL),
	(3, NULL, NULL, 'kardi31@o2.pl', NULL, '67ffafdad913f7e09ba0d1fd61ffbb47', 'cc10974f65abaf1638c1803f43f09154', 'user', '22134b46a034bcec418d92cfb1505e82', 1, '2014-12-20 20:44:09', '2014-12-20 20:45:45', NULL);
/*!40000 ALTER TABLE `user_user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

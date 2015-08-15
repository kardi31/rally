-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.21 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- Dumping data for table ral.car_car: 19 rows
DELETE FROM `car_car`;
/*!40000 ALTER TABLE `car_car` DISABLE KEYS */;
INSERT INTO `car_car` (`id`, `model_id`, `name`, `team_id`, `mileage`, `value`, `upkeep`, `on_market`, `last_season_value_id`, `created_at`, `updated_at`, `deleted_at`, `last_name_change`) VALUES
	(1, 9, 'Mitsubishi Lancer Pro Evo X #5664', 3, 0, 60000, 9000, 0, 0, '2015-08-04 16:27:18', '2015-08-05 11:26:03', NULL, NULL),
	(2, 4, 'Auto 4 #22750', 2, 0, NULL, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL, NULL),
	(3, 3, 'Fiat punto #7140', 3, 0, NULL, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL, NULL),
	(4, 3, 'Fiat punto #23217', 4, 0, NULL, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL, NULL),
	(5, 4, 'Auto 4 #19310', 5, 0, NULL, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL, NULL),
	(6, 4, 'Auto 4 #21735', 6, 0, NULL, 0, 1, 0, '2015-08-04 16:27:18', '2015-08-05 11:45:36', NULL, NULL),
	(7, 9, 'Mitsubishi Lancer Pro Evo X #24004', 7, 0, 60000, 9000, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL, NULL),
	(8, 3, 'Fiat punto #17685', 3, 0, NULL, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-05 11:42:32', NULL, NULL),
	(9, 10, 'Subaru Impreza 2.0 P1 #3816', 9, 0, 49800, 7950, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL, NULL),
	(10, 3, 'Fiat punto #17995', 10, 0, NULL, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL, NULL),
	(11, 9, 'Mitsubishi Lancer Pro Evo X #21409', 11, 0, 60000, 9000, 0, 0, '2015-08-04 16:27:19', '2015-08-05 11:24:56', NULL, NULL),
	(12, 4, 'Auto 4 #22148', 12, 0, NULL, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL, NULL),
	(13, 3, 'Fiat punto #11848', 13, 0, NULL, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL, NULL),
	(14, 3, 'Fiat punto #21135', 14, 0, NULL, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL, NULL),
	(15, 4, 'Auto 4 #1357', 15, 0, NULL, 0, 1, 0, '2015-08-04 16:27:19', '2015-08-05 11:53:19', NULL, NULL),
	(16, 3, 'Fiat punto #7926', 16, 0, NULL, 0, 0, 0, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL, NULL),
	(17, 3, 'Fiat punto #15667', 17, 0, NULL, 0, 0, 0, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL, NULL),
	(18, 10, 'Subaru Impreza 2.0 P1 #1143', 13, 0, 46300, 6945, 0, 0, '2015-08-05 12:20:13', '2015-08-05 12:20:13', NULL, NULL),
	(19, 4, 'Auto 4 #22245', 18, 0, NULL, 0, 0, 0, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL, NULL);
/*!40000 ALTER TABLE `car_car` ENABLE KEYS */;

-- Dumping data for table ral.car_car_models: 33 rows
DELETE FROM `car_car_models`;
/*!40000 ALTER TABLE `car_car_models` DISABLE KEYS */;
INSERT INTO `car_car_models` (`id`, `name`, `capacity`, `horsepower`, `max_speed`, `acceleration`, `wheel_drive`, `league`, `real_value`, `price`, `on_market`, `unique`, `photo`, `created_at`, `updated_at`) VALUES
	(1, 'BMW 318is', 1895, 138, 212, 9.70, '', 3, 30147, 53000, 0, 0, 'bmw318is.jpg', '2015-08-07 13:17:40', '2015-08-07 16:11:26'),
	(2, 'Nissan Micra Sport', 1598, 108, 183, 9.80, '', 4, 25010, 37000, 0, 0, 'nissan_micra.jpg', '2015-08-07 13:13:40', '2015-08-07 16:11:26'),
	(3, 'Triumph TR7', 1998, 105, 175, 9.80, '', 5, 26847, 33000, 0, 0, 'triumph_tr7.jpg', '2015-08-07 13:09:20', '2015-08-07 16:11:26'),
	(4, 'Alfa Romeo Junior', 1300, 136, 197, 10.30, '', 5, 26055, 25000, 0, 0, 'alfa_romeo_junior.jpg', '2015-08-07 13:06:34', '2015-08-07 16:11:26'),
	(5, 'Renault Clio III', 1598, 110, 190, 10.20, '', 4, 25004, 34000, 0, 0, 'renault_clio_3.jpg', '2015-08-06 17:17:50', '2015-08-07 16:11:26'),
	(6, 'Mitsubishi Lancer Evo IX GT340', 1997, 305, 250, 5.20, '', 1, 41956, 123000, 0, 0, 'mitsubishi_lancer_evo_ix.jpg', '2015-08-06 12:30:11', '2015-08-07 16:11:26'),
	(7, 'Ford Fiesta R5', 1597, 280, 180, 8.10, '', 3, 32881, 61000, 0, 0, 'ford_fiesta_r5.jpg', '2015-08-06 12:31:14', '2015-08-07 16:11:26'),
	(8, 'Peugeot 208 VTi', 1199, 120, 178, 12.20, '', 5, 25461, 19000, 0, 0, 'peugot208_vti.jpg', '2015-08-06 12:39:23', '2015-08-07 16:11:26'),
	(9, 'Fiat Cinquecento Sport', 1109, 54, 150, 13.80, '', 6, 17778, 17778, 0, 1, 'cinquecento.jpg', '2015-08-06 12:50:19', '2015-08-07 16:11:26'),
	(10, 'Suzuki Swift Sport', 1568, 136, 195, 8.70, '', 4, 27683, 39500, 0, 0, 'suzuki_swift.JPG', '2015-08-06 15:21:31', '2015-08-07 16:11:26'),
	(11, 'Subaru Impreza WRX STI 2008', 2457, 297, 243, 4.80, '', 1, 44670, 165000, 0, 1, 'subaru_wrx_sti_2008.jpg', '2015-08-06 17:21:15', '2015-08-07 16:11:26'),
	(12, 'Citroen Xsara VTS', 1998, 167, 220, 8.00, '', 2, 36487, 83000, 0, 0, 'citroen_xsara_vts.jpg', '2015-08-06 17:30:34', '2015-08-07 16:11:26'),
	(13, 'Mazda 323 GTR', 1840, 182, 221, 7.00, '', 2, 37064, 79300, 0, 0, 'mazda323_gtr.jpg', '2015-08-06 17:46:53', '2015-08-07 16:11:26'),
	(14, 'FSO Polonez', 1600, 130, 180, 8.10, '', 5, 26027, 19500, 0, 0, 'fso_polonez.JPG', '2015-08-06 17:54:15', '2015-08-07 16:11:26'),
	(15, 'Honda Civic Turbo Type R', 1998, 306, 268, 5.70, '', 1, 42673, 127000, 0, 0, 'honca_civic_type_r.jpg', '2015-08-06 18:06:58', '2015-08-07 16:11:26'),
	(16, 'Toyota Celica GT-Four ST205', 1998, 239, 246, 5.90, '', 1, 43727, 119700, 0, 0, 'toyota_celica_gt_four.jpg', '2015-08-07 10:48:25', '2015-08-07 16:11:26'),
	(17, 'Proton Satria Neo Sport', 1589, 111, 189, 11.50, '', 5, 24177, 11000, 0, 0, 'pro_satria_neo.jpg', '2015-08-07 10:56:18', '2015-08-07 16:11:26'),
	(18, 'Ford Focus RS', 1988, 215, 232, 6.70, '', 2, 41280, 97000, 0, 0, 'ford_focus_rs_I.jpg', '2015-08-07 11:01:11', '2015-08-07 16:11:26'),
	(19, 'Ford Focus WRC', 1991, 300, 230, 4.20, '', 1, 51052, 183000, 0, 1, 'ford_focus_wrc.jpg', '2015-08-07 11:21:11', '2015-08-07 16:11:26'),
	(20, 'Hyundai Accent ', 1600, 137, 194, 8.20, '', 4, 27232, 41000, 0, 0, 'hyundai_accent.jpg', '2015-08-07 12:05:37', '2015-08-07 16:11:26'),
	(21, 'Skoda Octavia WRC', 1998, 300, 230, 4.80, '', 1, 41642, 115000, 0, 0, 'skoda_octavia_wrc.jpg', '2015-08-07 12:15:00', '2015-08-07 16:11:26'),
	(22, 'Skoda Fabia IRC', 1398, 177, 223, 7.30, '', 3, 30578, 56500, 0, 0, 'skoda_fabia.jpg', '2015-08-07 12:17:42', '2015-08-07 16:11:26'),
	(23, 'Mini Cooper S', 1598, 208, 210, 6.80, '', 3, 33238, 62500, 0, 0, 'mini_cooper_s.jpg', '2015-08-07 12:22:33', '2015-08-07 16:11:26'),
	(24, 'Volkswagen Polo R WRC', 1666, 315, 200, 3.90, '', 1, 44269, 133000, 0, 0, 'volkswagen_polo_r.jpg', '2015-08-07 12:25:29', '2015-08-07 16:11:26'),
	(25, 'Suzuki Ignis', 1300, 92, 159, 10.70, '', 5, 21775, 8000, 0, 0, 'suzuki_ignis.jpg', '2015-08-07 12:35:17', '2015-08-07 16:11:26'),
	(26, 'Ford Puma', 1679, 125, 203, 9.20, '', 3, 29946, 50100, 0, 0, 'ford_puma.jpg', '2015-08-07 12:40:24', '2015-08-07 16:11:26'),
	(27, 'Ford Escort V Cosworth', 1993, 224, 232, 6.10, '', 2, 38620, 87200, 0, 0, 'ford_escort_rs_crossworth.jpg', '2015-08-07 12:55:56', '2015-08-07 16:11:26'),
	(28, 'Peugeot 207', 1998, 280, 199, 11.10, '', 2, 36708, 85100, 0, 0, 'peugeot_207.JPG', '2015-08-07 13:02:23', '2015-08-07 16:11:26'),
	(39, 'Ford Cortina MKIII', 1993, 98, 165, 11.10, '', 5, 23847, 10000, 0, 0, 'ford_cortina_mk3.JPG', '2015-08-07 15:44:10', '2015-08-07 16:11:26'),
	(40, 'Mazda 2 Sport', 1498, 102, 188, 10.40, '', 5, 26201, 29000, 0, 0, 'mazda2_sport.JPG', '2015-08-07 15:46:14', '2015-08-07 16:11:26'),
	(41, 'Talbot Samba Rally', 1219, 90, 175, 10.80, '', 5, 24804, 17500, 0, 0, 'talbot_samba.jpg', '2015-08-07 15:47:54', '2015-08-07 16:11:26'),
	(42, 'Opel Viva GT', 1975, 104, 162, 10.70, '', 4, 27722, 37500, 0, 0, 'opel_viva.jpg', '2015-08-07 15:48:26', '2015-08-07 16:11:26'),
	(43, 'Dacia Sandero', 1598, 104, 180, 11.40, '', 4, 27098, 36500, 0, 0, 'dacia_sandero.jpg', '2015-08-07 16:09:41', '2015-08-07 16:11:26');
/*!40000 ALTER TABLE `car_car_models` ENABLE KEYS */;

-- Dumping data for table ral.forum_category: 5 rows
DELETE FROM `forum_category`;
/*!40000 ALTER TABLE `forum_category` DISABLE KEYS */;
INSERT INTO `forum_category` (`id`, `name`, `slug`, `description`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Ogólne', 'ogolne', 'O grze', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(2, 'Rajdy towarzyskie', 'rajdy-towarzyskie', 'Tutaj możecie pogadać o rajdach towarzyskich', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(3, 'Pytania', 'pytania', 'Tutaj możecie pytać o wszystko', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(5, 'Off topic', 'off-topic', 'Wszystko co nie zwiazane z gra', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(4, 'Sugestie', 'sugestie', 'Masz pomysł co ulepszyć w grze? Podziel się nim z nami', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `forum_category` ENABLE KEYS */;

-- Dumping data for table ral.forum_favourite: 0 rows
DELETE FROM `forum_favourite`;
/*!40000 ALTER TABLE `forum_favourite` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum_favourite` ENABLE KEYS */;

-- Dumping data for table ral.forum_post: 2 rows
DELETE FROM `forum_post`;
/*!40000 ALTER TABLE `forum_post` DISABLE KEYS */;
INSERT INTO `forum_post` (`id`, `user_id`, `category_id`, `title`, `content`, `active`, `thread_id`, `moderator_notes`, `moderator_date`, `moderator_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, NULL, 'THis is my response', 1, 1, NULL, NULL, NULL, '2015-08-05 12:38:49', '2015-08-05 13:04:01', NULL),
	(2, 1, 1, NULL, 'aaaa', 1, 3, NULL, NULL, NULL, '2015-08-05 13:17:05', '2015-08-05 13:17:05', NULL);
/*!40000 ALTER TABLE `forum_post` ENABLE KEYS */;

-- Dumping data for table ral.forum_thread: 6 rows
DELETE FROM `forum_thread`;
/*!40000 ALTER TABLE `forum_thread` DISABLE KEYS */;
INSERT INTO `forum_thread` (`id`, `user_id`, `category_id`, `title`, `content`, `pinned`, `active`, `moderator_notes`, `moderator_date`, `moderator_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 13, 1, 'This is my thread', 'content', 0, 1, NULL, NULL, NULL, '2015-08-05 12:25:54', '2015-08-05 12:38:27', NULL),
	(2, 1, 1, 'cxzcz', 'dasda', 0, 1, NULL, NULL, NULL, '2015-08-05 13:05:25', '2015-08-05 13:05:25', NULL),
	(3, 1, 1, 'dsada', 'xczcxz', 0, 1, NULL, NULL, NULL, '2015-08-05 13:05:46', '2015-08-05 13:05:46', NULL),
	(4, 1, 1, 'ewqe', 'dasdas', 0, 1, NULL, NULL, NULL, '2015-08-05 13:05:49', '2015-08-05 13:05:49', NULL),
	(5, 1, 1, 'dasda', 'cxz', 0, 1, NULL, NULL, NULL, '2015-08-05 13:08:20', '2015-08-05 13:08:20', NULL),
	(6, 1, 1, '', '', 0, 1, NULL, NULL, NULL, '2015-08-05 13:11:26', '2015-08-05 13:11:26', NULL);
/*!40000 ALTER TABLE `forum_thread` ENABLE KEYS */;

-- Dumping data for table ral.league_league: 32 rows
DELETE FROM `league_league`;
/*!40000 ALTER TABLE `league_league` DISABLE KEYS */;
INSERT INTO `league_league` (`league_name`, `league_level`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('1.00', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('2.10', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('2.20', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('3.10', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('3.20', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('3.30', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('3.40', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.1', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.2', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.3', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.4', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.5', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.6', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.7', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.8', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.9', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.11', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.12', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.13', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.14', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.15', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.16', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.17', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.18', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.19', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.20', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.21', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.22', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.23', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.24', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('4.25', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	('1', 1, '2015-08-04 16:26:23', '2015-08-04 16:26:23', NULL);
/*!40000 ALTER TABLE `league_league` ENABLE KEYS */;

-- Dumping data for table ral.league_season: 35 rows
DELETE FROM `league_season`;
/*!40000 ALTER TABLE `league_season` DISABLE KEYS */;
INSERT INTO `league_season` (`id`, `league_name`, `team_id`, `points`, `season`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '1', 16, 0, 1, '2015-08-04 16:26:23', '2015-08-04 16:26:23', NULL),
	(2, '1', 17, 0, 1, '2015-08-04 16:26:32', '2015-08-04 16:26:32', NULL),
	(3, '1', 18, 0, 1, '2015-08-04 16:26:36', '2015-08-04 16:26:36', NULL),
	(4, '1', 19, 0, 1, '2015-08-04 16:26:37', '2015-08-04 16:26:37', NULL),
	(5, '1', 20, 0, 1, '2015-08-04 16:26:37', '2015-08-04 16:26:37', NULL),
	(6, '1', 21, 0, 1, '2015-08-04 16:26:37', '2015-08-04 16:26:37', NULL),
	(7, '1', 22, 0, 1, '2015-08-04 16:26:38', '2015-08-04 16:26:38', NULL),
	(8, '1', 23, 0, 1, '2015-08-04 16:26:38', '2015-08-04 16:26:38', NULL),
	(9, '1', 24, 0, 1, '2015-08-04 16:26:39', '2015-08-04 16:26:39', NULL),
	(10, '1', 25, 0, 1, '2015-08-04 16:26:40', '2015-08-04 16:26:40', NULL),
	(11, '1', 26, 0, 1, '2015-08-04 16:26:40', '2015-08-04 16:26:40', NULL),
	(12, '1', 27, 0, 1, '2015-08-04 16:26:41', '2015-08-04 16:26:41', NULL),
	(13, '2.10', 28, 0, 1, '2015-08-04 16:26:41', '2015-08-04 16:26:41', NULL),
	(14, '2.10', 29, 0, 1, '2015-08-04 16:26:41', '2015-08-04 16:26:41', NULL),
	(15, '2.10', 30, 0, 1, '2015-08-04 16:26:41', '2015-08-04 16:26:41', NULL),
	(16, '2.10', 31, 0, 1, '2015-08-04 16:26:41', '2015-08-04 16:26:41', NULL),
	(17, '2.10', 32, 0, 1, '2015-08-04 16:26:42', '2015-08-04 16:26:42', NULL),
	(18, '2.10', 1, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(19, '2.10', 2, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(20, '2.10', 3, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(21, '2.10', 4, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(22, '2.10', 5, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(23, '2.10', 6, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(24, '2.10', 7, 0, 1, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(25, '2.20', 8, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(26, '2.20', 9, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(27, '2.20', 10, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(28, '2.20', 11, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(29, '2.20', 12, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(30, '2.20', 13, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(31, '2.20', 14, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(32, '2.20', 15, 0, 1, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(33, '2.20', 16, 0, 1, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(34, '2.20', 17, 0, 1, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL),
	(35, '2.20', 18, 0, 1, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL);
/*!40000 ALTER TABLE `league_season` ENABLE KEYS */;

-- Dumping data for table ral.league_season_info: 1 rows
DELETE FROM `league_season_info`;
/*!40000 ALTER TABLE `league_season_info` DISABLE KEYS */;
INSERT INTO `league_season_info` (`id`, `season`, `season_start`, `season_finish`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, '2015-08-02 15:09:25', '2015-09-25 15:09:47', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `league_season_info` ENABLE KEYS */;

-- Dumping data for table ral.market_bid: 5 rows
DELETE FROM `market_bid`;
/*!40000 ALTER TABLE `market_bid` DISABLE KEYS */;
INSERT INTO `market_bid` (`id`, `offer_id`, `value`, `team_id`, `active`, `user_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 2, 550, 13, 1, '::1', '2015-08-05 10:09:11', '2015-08-05 10:24:35', NULL),
	(2, 2, 605, 4, 1, '::1', '2015-08-05 10:34:13', '2015-08-05 10:34:13', NULL),
	(3, 2, 665, 2, 1, '::1', '2015-08-05 10:34:25', '2015-08-05 10:34:25', NULL),
	(4, 2, 731, 1, 1, '::1', '2015-08-05 10:34:30', '2015-08-05 10:34:30', NULL),
	(5, 2, 804, 3, 1, '::1', '2015-08-05 10:34:37', '2015-08-05 10:34:37', NULL);
/*!40000 ALTER TABLE `market_bid` ENABLE KEYS */;

-- Dumping data for table ral.market_car_bid: 13 rows
DELETE FROM `market_car_bid`;
/*!40000 ALTER TABLE `market_car_bid` DISABLE KEYS */;
INSERT INTO `market_car_bid` (`id`, `offer_id`, `value`, `team_id`, `active`, `user_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1500, 2, 1, '::1', '2015-08-05 11:04:41', '2015-08-05 11:04:41', NULL),
	(2, 1, 1650, 4, 1, '::1', '2015-08-05 11:04:56', '2015-08-05 11:04:56', NULL),
	(3, 1, 1815, 5, 1, '::1', '2015-08-05 11:05:24', '2015-08-05 11:05:24', NULL),
	(4, 1, 1996, 6, 1, '::1', '2015-08-05 11:05:30', '2015-08-05 11:05:30', NULL),
	(5, 1, 2195, 7, 1, '::1', '2015-08-05 11:05:34', '2015-08-05 11:05:34', NULL),
	(6, 1, 2414, 8, 1, '::1', '2015-08-05 11:05:40', '2015-08-05 11:05:40', NULL),
	(8, 1, 2655, 3, 1, '::1', '2015-08-05 11:14:08', '2015-08-05 11:14:08', NULL),
	(9, 3, 2, 3, 1, '::1', '2015-08-05 11:34:55', '2015-08-05 11:34:55', NULL),
	(10, 4, 1500, 2, 1, '::1', '2015-08-05 11:49:00', '2015-08-05 11:49:00', NULL),
	(11, 4, 1650, 4, 1, '::1', '2015-08-05 11:58:00', '2015-08-05 11:58:00', NULL),
	(12, 4, 1815, 7, 1, '::1', '2015-08-05 11:58:06', '2015-08-05 11:58:06', NULL),
	(13, 4, 1996, 11, 1, '::1', '2015-08-05 11:58:10', '2015-08-05 11:58:10', NULL),
	(14, 4, 2195, 13, 1, '::1', '2015-08-05 11:58:14', '2015-08-05 11:58:14', NULL);
/*!40000 ALTER TABLE `market_car_bid` ENABLE KEYS */;

-- Dumping data for table ral.market_car_dealer: 0 rows
DELETE FROM `market_car_dealer`;
/*!40000 ALTER TABLE `market_car_dealer` DISABLE KEYS */;
/*!40000 ALTER TABLE `market_car_dealer` ENABLE KEYS */;

-- Dumping data for table ral.market_car_duplicate: 14 rows
DELETE FROM `market_car_duplicate`;
/*!40000 ALTER TABLE `market_car_duplicate` DISABLE KEYS */;
INSERT INTO `market_car_duplicate` (`id`, `offer_id`, `bid_id`, `solved`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, 0, '2015-08-05 11:04:42', '2015-08-05 11:04:42', NULL),
	(2, 1, 2, 0, '2015-08-05 11:04:56', '2015-08-05 11:04:56', NULL),
	(3, 1, 3, 0, '2015-08-05 11:05:24', '2015-08-05 11:05:24', NULL),
	(4, 1, 4, 0, '2015-08-05 11:05:30', '2015-08-05 11:05:30', NULL),
	(5, 1, 5, 0, '2015-08-05 11:05:34', '2015-08-05 11:05:34', NULL),
	(6, 1, 6, 0, '2015-08-05 11:05:40', '2015-08-05 11:05:40', NULL),
	(7, 1, 7, 0, '2015-08-05 11:11:21', '2015-08-05 11:11:21', NULL),
	(8, 1, 8, 0, '2015-08-05 11:14:08', '2015-08-05 11:14:08', NULL),
	(9, 3, 9, 0, '2015-08-05 11:34:55', '2015-08-05 11:34:55', NULL),
	(10, 4, 10, 0, '2015-08-05 11:49:00', '2015-08-05 11:49:00', NULL),
	(11, 4, 11, 0, '2015-08-05 11:58:00', '2015-08-05 11:58:00', NULL),
	(12, 4, 12, 0, '2015-08-05 11:58:06', '2015-08-05 11:58:06', NULL),
	(13, 4, 13, 0, '2015-08-05 11:58:10', '2015-08-05 11:58:10', NULL),
	(14, 4, 14, 0, '2015-08-05 11:58:14', '2015-08-05 11:58:14', NULL);
/*!40000 ALTER TABLE `market_car_duplicate` ENABLE KEYS */;

-- Dumping data for table ral.market_car_offer: 5 rows
DELETE FROM `market_car_offer`;
/*!40000 ALTER TABLE `market_car_offer` DISABLE KEYS */;
INSERT INTO `market_car_offer` (`id`, `car_id`, `team_id`, `start_date`, `finish_date`, `asking_price`, `highest_bid`, `active`, `car_moved`, `canceled`, `user_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, '2015-08-05 11:04:21', '2015-08-05 10:56:08', 1500, 2655, 1, 1, 0, NULL, '2015-08-05 11:04:21', '2015-08-05 11:26:03', NULL),
	(2, 11, 11, '2015-08-05 11:14:34', '2015-08-05 10:15:25', 500, 0, 1, 1, 0, NULL, '2015-08-05 11:14:34', '2015-08-05 11:24:56', NULL),
	(3, 8, 8, '2015-08-05 11:34:48', '2015-08-05 10:40:24', 2, 2, 1, 1, 0, NULL, '2015-08-05 11:34:48', '2015-08-05 11:42:32', NULL),
	(4, 6, 6, '2015-08-05 11:45:36', '2015-08-08 11:45:36', 1500, 2195, 1, 0, 0, NULL, '2015-08-05 11:45:36', '2015-08-05 11:58:14', NULL),
	(5, 15, 15, '2015-08-05 11:53:19', '2015-08-05 10:56:14', 200, 0, 1, 0, 0, NULL, '2015-08-05 11:53:19', '2015-08-05 11:53:19', NULL);
/*!40000 ALTER TABLE `market_car_offer` ENABLE KEYS */;

-- Dumping data for table ral.market_offer: 3 rows
DELETE FROM `market_offer`;
/*!40000 ALTER TABLE `market_offer` DISABLE KEYS */;
INSERT INTO `market_offer` (`id`, `people_id`, `team_id`, `start_date`, `finish_date`, `asking_price`, `highest_bid`, `active`, `player_moved`, `canceled`, `user_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 27, 14, '2015-08-05 10:08:36', '2015-08-09 10:08:36', 1500000, 0, 1, 0, 0, '::1', '2015-08-05 10:08:36', '2015-08-05 10:08:36', NULL),
	(2, 28, 14, '2015-08-05 10:08:47', '2015-08-05 09:56:15', 550, 804, 1, 1, 0, '::1', '2015-08-05 10:08:47', '2015-08-05 11:00:58', NULL),
	(3, 25, 13, '2015-08-05 10:12:49', '2015-08-05 09:21:57', 5000, 0, 1, 1, 0, '::1', '2015-08-05 10:12:49', '2015-08-05 10:56:28', NULL);
/*!40000 ALTER TABLE `market_offer` ENABLE KEYS */;

-- Dumping data for table ral.market_people_duplicate: 5 rows
DELETE FROM `market_people_duplicate`;
/*!40000 ALTER TABLE `market_people_duplicate` DISABLE KEYS */;
INSERT INTO `market_people_duplicate` (`id`, `offer_id`, `bid_id`, `solved`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 2, 1, 1, '2015-08-05 10:09:11', '2015-08-05 10:24:40', NULL),
	(2, 2, 2, 0, '2015-08-05 10:34:13', '2015-08-05 10:34:13', NULL),
	(3, 2, 3, 0, '2015-08-05 10:34:25', '2015-08-05 10:34:25', NULL),
	(4, 2, 4, 0, '2015-08-05 10:34:30', '2015-08-05 10:34:30', NULL),
	(5, 2, 5, 0, '2015-08-05 10:34:37', '2015-08-05 10:34:37', NULL);
/*!40000 ALTER TABLE `market_people_duplicate` ENABLE KEYS */;

-- Dumping data for table ral.news_news: 4 rows
DELETE FROM `news_news`;
/*!40000 ALTER TABLE `news_news` DISABLE KEYS */;
INSERT INTO `news_news` (`id`, `title`, `slug`, `content`, `publish_date`, `visible`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Pierrwszy post\\', 'pierw', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce lorem tortor, auctor quis lobortis eleifend, ultricies vel orci. Suspendisse quis sagittis tellus. Morbi blandit lectus vitae velit vulputate, id molestie velit feugiat. Vivamus et nibh placerat, congue mi sodales, euismod urna. Proin posuere odio erat, ac commodo neque gravida ut. Vivamus viverra feugiat mauris vitae malesuada. Ut pulvinar, mauris a lacinia malesuada, felis risus blandit enim, at blandit lectus tortor vitae mauris. Sed volutpat nibh in commodo pretium. Donec rhoncus sed odio iaculis euismod.', '2015-06-19 12:29:33', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(2, 'Drugi enws', 'drugi-news', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce lorem tortor, auctor quis lobortis eleifend, ultricies vel orci. Suspendisse quis sagittis tellus. Morbi blandit lectus vitae velit vulputate, id molestie velit feugiat. Vivamus et nibh placerat, congue mi sodales, euismod urna. Proin posuere odio erat, ac commodo neque gravida ut. Vivamus viverra feugiat mauris vitae malesuada. Ut pulvinar, mauris a lacinia malesuada, felis risus blandit enim, at blandit lectus tortor vitae mauris. Sed volutpat nibh in commodo pretium. Donec rhoncus sed odio iaculis euismod.', '2015-06-19 15:34:32', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(3, 'Trzeci', 'trzeci', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce lorem tortor, auctor quis lobortis eleifend, ultricies vel orci. Suspendisse quis sagittis tellus. Morbi blandit lectus vitae velit vulputate, id molestie velit feugiat. Vivamus et nibh placerat, congue mi sodales, euismod urna. Proin posuere odio erat, ac commodo neque gravida ut. Vivamus viverra feugiat mauris vitae malesuada. Ut pulvinar, mauris a lacinia malesuada, felis risus blandit enim, at blandit lectus tortor vitae mauris. Sed volutpat nibh in commodo pretium. Donec rhoncus sed odio iaculis euismod.', '2015-06-19 15:34:35', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
	(4, 'Czwarty', 'czwarty-news', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce lorem tortor, auctor quis lobortis eleifend, ultricies vel orci. Suspendisse quis sagittis tellus. Morbi blandit lectus vitae velit vulputate, id molestie velit feugiat. Vivamus et nibh placerat, congue mi sodales, euismod urna. Proin posuere odio erat, ac commodo neque gravida ut. Vivamus viverra feugiat mauris vitae malesuada. Ut pulvinar, mauris a lacinia malesuada, felis risus blandit enim, at blandit lectus tortor vitae mauris. Sed volutpat nibh in commodo pretium. Donec rhoncus sed odio iaculis euismod.', '2015-06-19 15:34:37', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `news_news` ENABLE KEYS */;

-- Dumping data for table ral.people_people: 36 rows
DELETE FROM `people_people`;
/*!40000 ALTER TABLE `people_people` DISABLE KEYS */;
INSERT INTO `people_people` (`id`, `first_name`, `last_name`, `job`, `team_id`, `value`, `salary`, `age`, `composure`, `speed`, `regularity`, `reflex`, `on_gravel`, `on_tarmac`, `on_snow`, `in_rain`, `form`, `dictate_rhytm`, `diction`, `route_description`, `intelligence`, `talent`, `experience`, `active_training_skill`, `on_market`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Steph', 'Exum', 'driver', 1, 235576, 1649, 20, 4, 4, 4, 4, 7, 6, 4, 6, 3, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(2, 'Seb', 'Thompson', 'pilot', 1, 122367, 857, 19, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 8, 4, 7, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(3, 'Bubba', 'Curry', 'driver', 2, 226047, 1582, 21, 4, 6, 4, 4, 5, 4, 4, 5, 3, NULL, NULL, NULL, NULL, 7, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(4, 'Marc', 'Smith', 'pilot', 2, 122385, 857, 21, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 4, 4, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(5, 'Jean', 'Kowalski', 'driver', 3, 227284, 1591, 18, 6, 7, 4, 4, 6, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(6, 'Jan', 'Thompson', 'pilot', 3, 121870, 853, 21, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 7, 4, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(7, 'Marc', 'Simpson', 'driver', 4, 235483, 1648, 20, 4, 5, 5, 4, 8, 5, 4, 5, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(8, 'Kevin', 'Curry', 'pilot', 4, 116865, 818, 19, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 7, 4, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(9, 'John', 'Nowak', 'driver', 5, 229744, 1608, 18, 6, 5, 7, 4, 4, 4, 4, 6, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(10, 'Bubba', 'Smith', 'pilot', 5, 121867, 853, 21, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 4, 4, NULL, 6, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(11, 'James', 'Ripper', 'driver', 6, 225333, 1577, 21, 7, 6, 4, 5, 4, 5, 4, 4, 3, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(12, 'Steph', 'Thompson', 'pilot', 6, 119932, 840, 19, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 6, 8, 4, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(13, 'Andrew', 'Evans', 'driver', 7, 234065, 1638, 19, 4, 7, 4, 4, 6, 7, 4, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(14, 'Jack', 'Nowak', 'pilot', 7, 121724, 852, 20, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 7, 6, 6, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(15, 'Jack', 'Kowalski', 'driver', 8, 236878, 1658, 18, 4, 6, 4, 5, 4, 4, 4, 6, 3, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(16, 'Marc', 'House', 'pilot', 8, 115587, 809, 18, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 7, 6, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(17, 'James', 'Simpson', 'driver', 9, 237214, 1660, 21, 4, 6, 5, 5, 4, 7, 4, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(18, 'Jean', 'Evans', 'pilot', 9, 127450, 892, 21, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 4, 5, 5, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(19, 'Jean', 'Thompson', 'driver', 10, 229962, 1610, 21, 4, 4, 5, 7, 6, 6, 4, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(20, 'Jan', 'Exum', 'pilot', 10, 128408, 899, 20, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 8, 4, 4, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(21, 'Jack', 'Nowak', 'driver', 11, 212955, 1491, 21, 4, 5, 5, 4, 5, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 8, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(22, 'Jack', 'Evans', 'pilot', 11, 108270, 758, 18, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 4, 4, 6, NULL, 7, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(23, 'Jan', 'Nowak', 'driver', 12, 224023, 1568, 18, 5, 6, 4, 4, 6, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(24, 'Tom', 'Curry', 'pilot', 12, 121915, 853, 21, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 4, 4, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(25, 'Andrew', 'House', 'driver', 13, 224568, 1572, 18, 6, 4, 5, 6, 4, 6, 4, 4, 3, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-05 10:56:28', NULL),
	(26, 'Antoine', 'Exum', 'pilot', 13, 119950, 840, 19, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 6, 7, 6, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(27, 'Seb', 'Exum', 'driver', 14, 235815, 1651, 19, 4, 4, 5, 4, 5, 6, 4, 8, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 1, '2015-08-04 16:27:19', '2015-08-05 10:08:36', NULL),
	(28, 'James', 'Kowalski', 'pilot', 3, 116605, 816, 18, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 4, 4, NULL, 7, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-05 11:00:58', NULL),
	(29, 'Jack', 'Smith', 'driver', 15, 237918, 1665, 20, 6, 4, 4, 4, 4, 5, 6, 5, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(30, 'Tom', 'House', 'pilot', 15, 115107, 806, 20, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 6, 5, 4, NULL, 5, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(31, 'Andrew', 'Simpson', 'driver', 3, 234577, 1642, 20, 6, 7, 4, 4, 7, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(32, 'Antoine', 'Thompson', 'pilot', 16, 119746, 838, 20, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 6, 6, 4, NULL, 4, NULL, NULL, 0, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(33, 'Jean', 'Curry', 'driver', 17, 234804, 1644, 20, 4, 7, 5, 5, 4, 4, 5, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL),
	(34, 'Antoine', 'Curry', 'pilot', 17, 112837, 790, 20, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 4, 6, NULL, 5, NULL, NULL, 0, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL),
	(35, 'Marc', 'Evans', 'driver', 18, 237860, 1665, 19, 5, 5, 5, 6, 4, 4, 4, 4, 3, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL),
	(36, 'John', 'Nowak', 'pilot', 18, 131721, 922, 18, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 4, 6, 4, NULL, 4, NULL, NULL, 0, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL);
/*!40000 ALTER TABLE `people_people` ENABLE KEYS */;

-- Dumping data for table ral.people_training: 0 rows
DELETE FROM `people_training`;
/*!40000 ALTER TABLE `people_training` DISABLE KEYS */;
/*!40000 ALTER TABLE `people_training` ENABLE KEYS */;

-- Dumping data for table ral.people_training_factor: 36 rows
DELETE FROM `people_training_factor`;
/*!40000 ALTER TABLE `people_training_factor` DISABLE KEYS */;
INSERT INTO `people_training_factor` (`people_id`, `composure`, `speed`, `regularity`, `reflex`, `on_gravel`, `on_tarmac`, `on_snow`, `in_rain`, `dictate_rhytm`, `diction`, `route_description`, `intelligence`, `composure_max`, `speed_max`, `regularity_max`, `reflex_max`, `on_gravel_max`, `on_tarmac_max`, `on_snow_max`, `in_rain_max`, `dictate_rhytm_max`, `diction_max`, `route_description_max`, `intelligence_max`, `last_season_value_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1.34, 0.50, 1.19, 0.56, 0.50, 1.50, 0.50, 0.51, NULL, NULL, NULL, NULL, 8, 8, 8, 7, 8, 8, 7, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(2, 1.63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.31, 0.62, 0.81, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 7, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(3, 1.15, 0.96, 0.95, 0.80, 0.90, 0.73, 1.31, 1.46, NULL, NULL, NULL, NULL, 8, 9, 8, 9, 8, 9, 9, 9, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(4, 1.31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.53, 0.90, 0.55, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 7, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(5, 0.97, 1.27, 0.50, 0.55, 1.05, 0.50, 0.53, 0.50, NULL, NULL, NULL, NULL, 7, 7, 7, 7, 7, 8, 8, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(6, 0.69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.15, 1.50, 1.53, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 8, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(7, 0.50, 1.33, 0.50, 0.50, 0.85, 0.50, 0.65, 1.06, NULL, NULL, NULL, NULL, 7, 8, 7, 7, 7, 7, 7, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(8, 1.28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.34, 0.78, 0.94, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, 7, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(9, 0.50, 0.81, 0.50, 1.48, 0.66, 0.95, 0.50, 0.50, NULL, NULL, NULL, NULL, 7, 7, 8, 7, 7, 8, 7, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(10, 1.57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.43, 1.48, 0.74, NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 8, 8, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(11, 0.50, 1.33, 0.62, 1.04, 1.35, 0.71, 0.50, 0.50, NULL, NULL, NULL, NULL, 8, 7, 8, 8, 8, 7, 8, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(12, 0.75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.02, 1.12, 1.44, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, 7, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(13, 1.15, 0.50, 0.50, 0.50, 1.48, 0.62, 0.56, 0.50, NULL, NULL, NULL, NULL, 7, 7, 8, 7, 7, 7, 7, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(14, 1.24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.00, 1.18, 0.86, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, 8, NULL, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(15, 0.50, 1.37, 1.17, 0.54, 0.60, 0.73, 1.17, 0.50, NULL, NULL, NULL, NULL, 8, 7, 8, 8, 7, 8, 8, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(16, 1.60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.92, 1.47, 0.89, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 8, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(17, 1.22, 0.50, 0.50, 0.80, 1.27, 0.50, 0.50, 0.52, NULL, NULL, NULL, NULL, 7, 7, 7, 8, 7, 7, 8, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(18, 0.53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.12, 1.78, 0.97, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, 7, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(19, 1.04, 0.60, 0.70, 0.69, 1.01, 0.69, 0.62, 0.50, NULL, NULL, NULL, NULL, 7, 8, 7, 7, 7, 7, 8, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(20, 1.30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.96, 0.50, 1.47, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, 8, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(21, 1.41, 0.76, 1.21, 1.07, 0.72, 0.95, 1.43, 1.33, NULL, NULL, NULL, NULL, 9, 9, 9, 10, 9, 9, 9, 9, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(22, 1.64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.64, 1.56, 0.96, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 9, 8, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(23, 0.51, 0.58, 0.52, 1.12, 1.22, 1.46, 1.37, 0.50, NULL, NULL, NULL, NULL, 8, 8, 8, 8, 8, 8, 8, 9, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(24, 1.40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.32, 1.17, 0.84, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, 8, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(25, 0.58, 1.51, 0.71, 0.81, 0.67, 0.66, 0.96, 0.64, NULL, NULL, NULL, NULL, 8, 7, 8, 8, 8, 7, 8, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(26, 0.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.96, 1.22, 1.53, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, 7, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(27, 0.50, 1.14, 0.50, 0.57, 0.50, 0.50, 1.30, 0.86, NULL, NULL, NULL, NULL, 8, 7, 7, 7, 8, 7, 7, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(28, 1.71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.63, 1.62, 0.79, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 8, 8, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(29, 1.29, 0.70, 0.50, 0.86, 0.50, 0.71, 0.81, 0.52, NULL, NULL, NULL, NULL, 7, 7, 7, 8, 7, 7, 8, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(30, 1.64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.58, 1.72, 0.83, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 8, NULL, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(31, 0.82, 0.50, 0.50, 0.50, 0.68, 0.74, 0.80, 1.28, NULL, NULL, NULL, NULL, 7, 7, 7, 7, 7, 8, 7, 8, NULL, NULL, NULL, NULL, 0, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(32, 0.91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.81, 1.32, 1.24, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 7, NULL, 0, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(33, 0.50, 1.29, 0.62, 0.54, 0.79, 0.52, 0.51, 1.11, NULL, NULL, NULL, NULL, 7, 7, 8, 7, 8, 7, 7, 7, NULL, NULL, NULL, NULL, 0, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL),
	(34, 1.16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.83, 1.65, 1.15, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, 8, NULL, 0, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL),
	(35, 0.89, 0.97, 0.50, 0.73, 0.56, 0.62, 0.66, 0.93, NULL, NULL, NULL, NULL, 7, 7, 7, 8, 7, 8, 7, 7, NULL, NULL, NULL, NULL, 0, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL),
	(36, 1.31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.89, 1.45, 0.65, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, 8, NULL, 0, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL);
/*!40000 ALTER TABLE `people_training_factor` ENABLE KEYS */;

-- Dumping data for table ral.rally_accident: 0 rows
DELETE FROM `rally_accident`;
/*!40000 ALTER TABLE `rally_accident` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_accident` ENABLE KEYS */;

-- Dumping data for table ral.rally_big_awards: 0 rows
DELETE FROM `rally_big_awards`;
/*!40000 ALTER TABLE `rally_big_awards` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_big_awards` ENABLE KEYS */;

-- Dumping data for table ral.rally_crew: 10 rows
DELETE FROM `rally_crew`;
/*!40000 ALTER TABLE `rally_crew` DISABLE KEYS */;
INSERT INTO `rally_crew` (`id`, `rally_id`, `team_id`, `driver_id`, `pilot_id`, `car_id`, `risk`, `in_race`, `training_done`, `km_passed`, `created_at`, `updated_at`) VALUES
	(1, 15, 18, 35, 36, 19, 'normal', 1, 0, 0.00, '2015-08-07 16:23:08', '2015-08-07 16:23:08'),
	(2, 15, 13, 25, 26, 13, 'normal', 1, 0, 0.00, '2015-08-07 16:23:20', '2015-08-07 16:23:20'),
	(3, 15, 3, 5, 6, 1, 'normal', 1, 0, 0.00, '2015-08-07 16:23:32', '2015-08-07 16:23:32'),
	(4, 15, 7, 13, 14, 7, 'normal', 1, 0, 0.00, '2015-08-07 16:23:49', '2015-08-07 16:23:49'),
	(5, 15, 9, 17, 18, 9, 'normal', 1, 0, 0.00, '2015-08-07 16:24:06', '2015-08-07 16:24:06'),
	(6, 15, 10, 19, 20, 10, 'normal', 1, 0, 0.00, '2015-08-07 16:24:16', '2015-08-07 16:24:16'),
	(7, 15, 11, 21, 22, 11, 'normal', 1, 0, 0.00, '2015-08-07 16:24:22', '2015-08-07 16:24:22'),
	(8, 15, 12, 23, 24, 12, 'normal', 1, 0, 0.00, '2015-08-07 16:24:31', '2015-08-07 16:24:31'),
	(9, 15, 17, 33, 34, 17, 'normal', 1, 0, 0.00, '2015-08-07 16:25:06', '2015-08-07 16:25:06'),
	(10, 15, 3, 31, 28, 3, 'normal', 1, 0, 0.00, '2015-08-07 16:26:30', '2015-08-07 16:26:30');
/*!40000 ALTER TABLE `rally_crew` ENABLE KEYS */;

-- Dumping data for table ral.rally_friendly: 0 rows
DELETE FROM `rally_friendly`;
/*!40000 ALTER TABLE `rally_friendly` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_friendly` ENABLE KEYS */;

-- Dumping data for table ral.rally_friendly_invitations: 0 rows
DELETE FROM `rally_friendly_invitations`;
/*!40000 ALTER TABLE `rally_friendly_invitations` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_friendly_invitations` ENABLE KEYS */;

-- Dumping data for table ral.rally_friendly_participants: 0 rows
DELETE FROM `rally_friendly_participants`;
/*!40000 ALTER TABLE `rally_friendly_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_friendly_participants` ENABLE KEYS */;

-- Dumping data for table ral.rally_rally: 15 rows
DELETE FROM `rally_rally`;
/*!40000 ALTER TABLE `rally_rally` DISABLE KEYS */;
INSERT INTO `rally_rally` (`id`, `name`, `slug`, `description`, `date`, `active`, `league_rally`, `surface`, `league`, `big_awards`, `finished`, `friendly`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'League 2.10 Rally - #2', 'league-210-rally-2', NULL, '2015-08-16 08:30:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(2, 'League 2.10 Rally - #3', 'league-210-rally-3', NULL, '2015-08-23 14:30:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(3, 'League 2.10 Rally - #4', 'league-210-rally-4', NULL, '2015-08-30 11:45:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(4, 'League 2.10 Rally - #5', 'league-210-rally-5', NULL, '2015-09-06 09:15:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(5, 'League 2.10 Rally - #6', 'league-210-rally-6', NULL, '2015-09-13 11:00:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(6, 'League 2.10 Rally - #7', 'league-210-rally-7', NULL, '2015-09-20 13:45:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(7, 'League 2.10 Rally - #8', 'league-210-rally-8', NULL, '2015-09-27 08:45:00', 1, 1, NULL, 2.10, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(8, 'League 2.20 Rally - #2', 'league-220-rally-2', NULL, '2015-08-16 12:15:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(9, 'League 2.20 Rally - #3', 'league-220-rally-3', NULL, '2015-08-23 08:45:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(10, 'League 2.20 Rally - #4', 'league-220-rally-4', NULL, '2015-08-30 13:30:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(11, 'League 2.20 Rally - #5', 'league-220-rally-5', NULL, '2015-09-06 08:30:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(12, 'League 2.20 Rally - #6', 'league-220-rally-6', NULL, '2015-09-13 11:00:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(13, 'League 2.20 Rally - #7', 'league-220-rally-7', NULL, '2015-09-20 08:45:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(14, 'League 2.20 Rally - #8', 'league-220-rally-8', NULL, '2015-09-27 08:15:00', 1, 1, NULL, 2.20, 0, 0, 0, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(15, 'Test rally liga 1', 'test-rally-liga-1', 'dasda', '2015-08-20 14:50:00', 1, 0, NULL, 1.00, 0, 0, 0, '2015-08-07 16:20:20', '2015-08-07 16:20:20', NULL);
/*!40000 ALTER TABLE `rally_rally` ENABLE KEYS */;

-- Dumping data for table ral.rally_result: 0 rows
DELETE FROM `rally_result`;
/*!40000 ALTER TABLE `rally_result` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_result` ENABLE KEYS */;

-- Dumping data for table ral.rally_stage: 269 rows
DELETE FROM `rally_stage`;
/*!40000 ALTER TABLE `rally_stage` DISABLE KEYS */;
INSERT INTO `rally_stage` (`id`, `rally_id`, `name`, `length`, `date`, `min_time`, `created_at`, `updated_at`, `finished`) VALUES
	(1, 1, 'Stage 0', 3.29, '2015-08-16 08:30:00', '00:15:19', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(2, 1, 'Stage 1', 5.16, '2015-08-16 08:45:00', '00:10:27', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(3, 1, 'Stage 2', 16.84, '2015-08-16 09:00:00', '00:08:34', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(4, 1, 'Stage 3', 2.90, '2015-08-16 09:15:00', '00:25:08', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(5, 1, 'Stage 4', 10.40, '2015-08-16 09:30:00', '00:18:22', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(6, 1, 'Stage 5', 29.48, '2015-08-16 09:45:00', '00:10:26', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(7, 1, 'Stage 6', 16.22, '2015-08-16 10:00:00', '00:14:25', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(8, 1, 'Stage 7', 29.16, '2015-08-16 10:15:00', '00:28:18', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(9, 1, 'Stage 8', 7.56, '2015-08-16 10:30:00', '00:23:51', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(10, 1, 'Stage 9', 26.38, '2015-08-16 10:45:00', '00:15:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(11, 1, 'Stage 10', 24.24, '2015-08-16 11:00:00', '00:08:36', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(12, 1, 'Stage 11', 25.48, '2015-08-16 11:15:00', '00:23:09', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(13, 1, 'Stage 12', 2.60, '2015-08-16 11:30:00', '00:26:33', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(14, 1, 'Stage 13', 17.28, '2015-08-16 11:45:00', '00:18:39', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(15, 1, 'Stage 14', 3.97, '2015-08-16 12:00:00', '00:04:10', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(16, 1, 'Stage 15', 16.70, '2015-08-16 12:15:00', '00:13:59', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(17, 1, 'Stage 16', 17.73, '2015-08-16 12:30:00', '00:20:53', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(18, 1, 'Stage 17', 10.80, '2015-08-16 12:45:00', '00:14:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(19, 2, 'Stage 0', 29.09, '2015-08-23 14:30:00', '00:14:43', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(20, 2, 'Stage 1', 9.70, '2015-08-23 14:45:00', '00:18:40', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(21, 2, 'Stage 2', 3.62, '2015-08-23 15:00:00', '00:17:50', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(22, 2, 'Stage 3', 6.50, '2015-08-23 15:15:00', '00:27:46', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(23, 2, 'Stage 4', 19.65, '2015-08-23 15:30:00', '00:27:20', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(24, 2, 'Stage 5', 23.54, '2015-08-23 15:45:00', '00:16:28', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(25, 2, 'Stage 6', 7.16, '2015-08-23 16:00:00', '00:08:13', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(26, 2, 'Stage 7', 3.63, '2015-08-23 16:15:00', '00:21:23', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(27, 2, 'Stage 8', 20.01, '2015-08-23 16:30:00', '00:22:48', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(28, 2, 'Stage 9', 15.85, '2015-08-23 16:45:00', '00:05:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(29, 2, 'Stage 10', 21.82, '2015-08-23 17:00:00', '00:16:24', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(30, 2, 'Stage 11', 19.04, '2015-08-23 17:15:00', '00:22:35', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(31, 2, 'Stage 12', 17.67, '2015-08-23 17:30:00', '00:03:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(32, 2, 'Stage 13', 5.35, '2015-08-23 17:45:00', '00:27:15', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(33, 2, 'Stage 14', 18.19, '2015-08-23 18:00:00', '00:11:05', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(34, 2, 'Stage 15', 14.27, '2015-08-23 18:15:00', '00:04:10', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(35, 2, 'Stage 16', 28.63, '2015-08-23 18:30:00', '00:19:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(36, 2, 'Stage 17', 10.48, '2015-08-23 18:45:00', '00:19:17', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(37, 3, 'Stage 0', 16.42, '2015-08-30 11:45:00', '00:19:34', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(38, 3, 'Stage 1', 12.30, '2015-08-30 12:00:00', '00:03:11', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(39, 3, 'Stage 2', 3.86, '2015-08-30 12:15:00', '00:21:31', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(40, 3, 'Stage 3', 17.59, '2015-08-30 12:30:00', '00:27:49', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(41, 3, 'Stage 4', 16.02, '2015-08-30 12:45:00', '00:28:05', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(42, 3, 'Stage 5', 10.97, '2015-08-30 13:00:00', '00:12:31', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(43, 3, 'Stage 6', 3.80, '2015-08-30 13:15:00', '00:28:38', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(44, 3, 'Stage 7', 18.30, '2015-08-30 13:30:00', '00:11:58', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(45, 3, 'Stage 8', 19.37, '2015-08-30 13:45:00', '00:16:03', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(46, 3, 'Stage 9', 26.78, '2015-08-30 14:00:00', '00:07:46', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(47, 3, 'Stage 10', 12.10, '2015-08-30 14:15:00', '00:03:58', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(48, 3, 'Stage 11', 13.17, '2015-08-30 14:30:00', '00:07:22', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(49, 3, 'Stage 12', 29.95, '2015-08-30 14:45:00', '00:06:40', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(50, 3, 'Stage 13', 24.49, '2015-08-30 15:00:00', '00:23:12', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(51, 3, 'Stage 14', 27.13, '2015-08-30 15:15:00', '00:11:14', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(52, 3, 'Stage 15', 9.72, '2015-08-30 15:30:00', '00:11:02', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(53, 3, 'Stage 16', 15.10, '2015-08-30 15:45:00', '00:22:17', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(54, 3, 'Stage 17', 6.22, '2015-08-30 16:00:00', '00:05:13', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(55, 4, 'Stage 0', 20.07, '2015-09-06 09:15:00', '00:14:43', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(56, 4, 'Stage 1', 16.11, '2015-09-06 09:30:00', '00:07:26', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(57, 4, 'Stage 2', 22.58, '2015-09-06 09:45:00', '00:19:40', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(58, 4, 'Stage 3', 26.45, '2015-09-06 10:00:00', '00:09:54', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(59, 4, 'Stage 4', 15.80, '2015-09-06 10:15:00', '00:23:11', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(60, 4, 'Stage 5', 13.45, '2015-09-06 10:30:00', '00:22:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(61, 4, 'Stage 6', 24.32, '2015-09-06 10:45:00', '00:27:40', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(62, 4, 'Stage 7', 24.09, '2015-09-06 11:00:00', '00:02:58', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(63, 4, 'Stage 8', 19.92, '2015-09-06 11:15:00', '00:12:25', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(64, 4, 'Stage 9', 10.97, '2015-09-06 11:30:00', '00:12:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(65, 4, 'Stage 10', 28.22, '2015-09-06 11:45:00', '00:24:24', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(66, 4, 'Stage 11', 6.96, '2015-09-06 12:00:00', '00:18:32', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(67, 4, 'Stage 12', 5.33, '2015-09-06 12:15:00', '00:03:12', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(68, 4, 'Stage 13', 29.49, '2015-09-06 12:30:00', '00:12:45', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(69, 4, 'Stage 14', 26.62, '2015-09-06 12:45:00', '00:08:54', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(70, 4, 'Stage 15', 7.12, '2015-09-06 13:00:00', '00:08:27', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(71, 4, 'Stage 16', 8.10, '2015-09-06 13:15:00', '00:23:54', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(72, 4, 'Stage 17', 18.66, '2015-09-06 13:30:00', '00:11:15', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(73, 5, 'Stage 0', 9.50, '2015-09-13 11:00:00', '00:21:25', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(74, 5, 'Stage 1', 25.71, '2015-09-13 11:15:00', '00:08:10', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(75, 5, 'Stage 2', 5.40, '2015-09-13 11:30:00', '00:17:57', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(76, 5, 'Stage 3', 19.37, '2015-09-13 11:45:00', '00:16:26', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(77, 5, 'Stage 4', 16.12, '2015-09-13 12:00:00', '00:10:44', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(78, 5, 'Stage 5', 16.06, '2015-09-13 12:15:00', '00:15:26', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(79, 5, 'Stage 6', 27.87, '2015-09-13 12:30:00', '00:22:10', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(80, 5, 'Stage 7', 7.41, '2015-09-13 12:45:00', '00:12:49', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(81, 5, 'Stage 8', 10.91, '2015-09-13 13:00:00', '00:21:44', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(82, 5, 'Stage 9', 26.47, '2015-09-13 13:15:00', '00:22:50', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(83, 5, 'Stage 10', 8.90, '2015-09-13 13:30:00', '00:19:52', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(84, 5, 'Stage 11', 29.54, '2015-09-13 13:45:00', '00:11:43', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(85, 5, 'Stage 12', 28.56, '2015-09-13 14:00:00', '00:18:55', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(86, 5, 'Stage 13', 11.75, '2015-09-13 14:15:00', '00:14:36', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(87, 5, 'Stage 14', 18.09, '2015-09-13 14:30:00', '00:26:19', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(88, 5, 'Stage 15', 27.80, '2015-09-13 14:45:00', '00:03:02', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(89, 5, 'Stage 16', 18.69, '2015-09-13 15:00:00', '00:23:04', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(90, 5, 'Stage 17', 20.47, '2015-09-13 15:15:00', '00:28:19', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(91, 6, 'Stage 0', 23.77, '2015-09-20 13:45:00', '00:26:38', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(92, 6, 'Stage 1', 8.75, '2015-09-20 14:00:00', '00:23:42', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(93, 6, 'Stage 2', 3.79, '2015-09-20 14:15:00', '00:12:51', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(94, 6, 'Stage 3', 23.81, '2015-09-20 14:30:00', '00:04:44', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(95, 6, 'Stage 4', 22.96, '2015-09-20 14:45:00', '00:05:45', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(96, 6, 'Stage 5', 26.02, '2015-09-20 15:00:00', '00:27:55', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(97, 6, 'Stage 6', 9.46, '2015-09-20 15:15:00', '00:21:13', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(98, 6, 'Stage 7', 29.70, '2015-09-20 15:30:00', '00:09:09', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(99, 6, 'Stage 8', 17.67, '2015-09-20 15:45:00', '00:18:33', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(100, 6, 'Stage 9', 18.37, '2015-09-20 16:00:00', '00:19:33', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(101, 6, 'Stage 10', 5.72, '2015-09-20 16:15:00', '00:30:10', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(102, 6, 'Stage 11', 24.14, '2015-09-20 16:30:00', '00:15:12', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(103, 6, 'Stage 12', 26.99, '2015-09-20 16:45:00', '00:26:09', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(104, 6, 'Stage 13', 2.04, '2015-09-20 17:00:00', '00:05:02', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(105, 6, 'Stage 14', 11.24, '2015-09-20 17:15:00', '00:10:17', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(106, 6, 'Stage 15', 11.65, '2015-09-20 17:30:00', '00:12:21', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(107, 6, 'Stage 16', 4.70, '2015-09-20 17:45:00', '00:08:46', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(108, 6, 'Stage 17', 18.30, '2015-09-20 18:00:00', '00:24:58', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(109, 7, 'Stage 0', 2.80, '2015-09-27 08:45:00', '00:06:46', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(110, 7, 'Stage 1', 17.98, '2015-09-27 09:00:00', '00:04:36', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(111, 7, 'Stage 2', 15.76, '2015-09-27 09:15:00', '00:13:31', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(112, 7, 'Stage 3', 7.45, '2015-09-27 09:30:00', '00:25:12', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(113, 7, 'Stage 4', 21.03, '2015-09-27 09:45:00', '00:20:41', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(114, 7, 'Stage 5', 26.30, '2015-09-27 10:00:00', '00:25:21', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(115, 7, 'Stage 6', 3.40, '2015-09-27 10:15:00', '00:20:22', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(116, 7, 'Stage 7', 9.26, '2015-09-27 10:30:00', '00:17:40', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(117, 7, 'Stage 8', 5.54, '2015-09-27 10:45:00', '00:11:41', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(118, 7, 'Stage 9', 6.19, '2015-09-27 11:00:00', '00:27:23', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(119, 7, 'Stage 10', 12.63, '2015-09-27 11:15:00', '00:11:32', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(120, 7, 'Stage 11', 21.48, '2015-09-27 11:30:00', '00:15:28', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(121, 7, 'Stage 12', 23.31, '2015-09-27 11:45:00', '00:17:37', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(122, 7, 'Stage 13', 19.04, '2015-09-27 12:00:00', '00:27:08', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(123, 7, 'Stage 14', 18.46, '2015-09-27 12:15:00', '00:05:33', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(124, 7, 'Stage 15', 17.11, '2015-09-27 12:30:00', '00:14:12', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(125, 7, 'Stage 16', 14.68, '2015-09-27 12:45:00', '00:11:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(126, 7, 'Stage 17', 11.30, '2015-09-27 13:00:00', '00:04:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(127, 8, 'Stage 0', 13.14, '2015-08-16 12:15:00', '00:05:33', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(128, 8, 'Stage 1', 24.77, '2015-08-16 12:30:00', '00:15:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(129, 8, 'Stage 2', 20.59, '2015-08-16 12:45:00', '00:24:36', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(130, 8, 'Stage 3', 11.34, '2015-08-16 13:00:00', '00:09:14', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(131, 8, 'Stage 4', 14.85, '2015-08-16 13:15:00', '00:06:51', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(132, 8, 'Stage 5', 16.42, '2015-08-16 13:30:00', '00:15:01', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(133, 8, 'Stage 6', 27.80, '2015-08-16 13:45:00', '00:03:43', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(134, 8, 'Stage 7', 20.89, '2015-08-16 14:00:00', '00:18:51', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(135, 8, 'Stage 8', 3.41, '2015-08-16 14:15:00', '00:22:54', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(136, 8, 'Stage 9', 9.68, '2015-08-16 14:30:00', '00:28:55', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(137, 8, 'Stage 10', 23.86, '2015-08-16 14:45:00', '00:16:07', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(138, 8, 'Stage 11', 3.51, '2015-08-16 15:00:00', '00:05:15', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(139, 8, 'Stage 12', 12.25, '2015-08-16 15:15:00', '00:23:17', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(140, 8, 'Stage 13', 21.09, '2015-08-16 15:30:00', '00:06:25', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(141, 8, 'Stage 14', 8.77, '2015-08-16 15:45:00', '00:30:14', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(142, 8, 'Stage 15', 8.07, '2015-08-16 16:00:00', '00:14:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(143, 8, 'Stage 16', 3.80, '2015-08-16 16:15:00', '00:06:55', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(144, 8, 'Stage 17', 18.77, '2015-08-16 16:30:00', '00:21:00', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(145, 9, 'Stage 0', 11.52, '2015-08-23 08:45:00', '00:23:03', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(146, 9, 'Stage 1', 14.21, '2015-08-23 09:00:00', '00:15:32', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(147, 9, 'Stage 2', 22.26, '2015-08-23 09:15:00', '00:21:47', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(148, 9, 'Stage 3', 8.98, '2015-08-23 09:30:00', '00:11:50', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(149, 9, 'Stage 4', 17.40, '2015-08-23 09:45:00', '00:13:47', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(150, 9, 'Stage 5', 13.05, '2015-08-23 10:00:00', '00:08:47', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(151, 9, 'Stage 6', 22.25, '2015-08-23 10:15:00', '00:05:53', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(152, 9, 'Stage 7', 26.71, '2015-08-23 10:30:00', '00:12:53', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(153, 9, 'Stage 8', 8.14, '2015-08-23 10:45:00', '00:28:16', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(154, 9, 'Stage 9', 15.04, '2015-08-23 11:00:00', '00:03:51', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(155, 9, 'Stage 10', 28.05, '2015-08-23 11:15:00', '00:05:22', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(156, 9, 'Stage 11', 8.73, '2015-08-23 11:30:00', '00:25:44', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(157, 9, 'Stage 12', 2.68, '2015-08-23 11:45:00', '00:21:04', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(158, 9, 'Stage 13', 2.85, '2015-08-23 12:00:00', '00:03:28', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(159, 9, 'Stage 14', 13.13, '2015-08-23 12:15:00', '00:28:08', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(160, 9, 'Stage 15', 15.48, '2015-08-23 12:30:00', '00:25:28', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(161, 9, 'Stage 16', 8.11, '2015-08-23 12:45:00', '00:19:22', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(162, 9, 'Stage 17', 22.18, '2015-08-23 13:00:00', '00:20:26', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(163, 10, 'Stage 0', 17.31, '2015-08-30 13:30:00', '00:14:28', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(164, 10, 'Stage 1', 17.20, '2015-08-30 13:45:00', '00:29:20', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(165, 10, 'Stage 2', 9.21, '2015-08-30 14:00:00', '00:09:25', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(166, 10, 'Stage 3', 4.42, '2015-08-30 14:15:00', '00:27:26', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(167, 10, 'Stage 4', 11.37, '2015-08-30 14:30:00', '00:23:21', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(168, 10, 'Stage 5', 12.35, '2015-08-30 14:45:00', '00:26:35', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(169, 10, 'Stage 6', 27.55, '2015-08-30 15:00:00', '00:07:41', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(170, 10, 'Stage 7', 7.69, '2015-08-30 15:15:00', '00:07:02', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(171, 10, 'Stage 8', 2.90, '2015-08-30 15:30:00', '00:30:03', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(172, 10, 'Stage 9', 27.28, '2015-08-30 15:45:00', '00:13:48', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(173, 10, 'Stage 10', 18.83, '2015-08-30 16:00:00', '00:13:44', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(174, 10, 'Stage 11', 29.35, '2015-08-30 16:15:00', '00:09:59', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(175, 10, 'Stage 12', 15.94, '2015-08-30 16:30:00', '00:08:38', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(176, 10, 'Stage 13', 16.92, '2015-08-30 16:45:00', '00:15:50', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(177, 10, 'Stage 14', 2.62, '2015-08-30 17:00:00', '00:13:18', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(178, 10, 'Stage 15', 12.32, '2015-08-30 17:15:00', '00:15:01', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(179, 10, 'Stage 16', 2.91, '2015-08-30 17:30:00', '00:13:32', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(180, 10, 'Stage 17', 3.07, '2015-08-30 17:45:00', '00:18:38', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(181, 11, 'Stage 0', 19.73, '2015-09-06 08:30:00', '00:18:12', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(182, 11, 'Stage 1', 9.72, '2015-09-06 08:45:00', '00:15:11', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(183, 11, 'Stage 2', 8.91, '2015-09-06 09:00:00', '00:15:45', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(184, 11, 'Stage 3', 12.08, '2015-09-06 09:15:00', '00:24:44', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(185, 11, 'Stage 4', 25.96, '2015-09-06 09:30:00', '00:23:17', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(186, 11, 'Stage 5', 10.15, '2015-09-06 09:45:00', '00:12:32', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(187, 11, 'Stage 6', 12.96, '2015-09-06 10:00:00', '00:16:16', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(188, 11, 'Stage 7', 10.99, '2015-09-06 10:15:00', '00:26:25', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(189, 11, 'Stage 8', 8.53, '2015-09-06 10:30:00', '00:24:19', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(190, 11, 'Stage 9', 6.67, '2015-09-06 10:45:00', '00:15:17', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(191, 11, 'Stage 10', 2.23, '2015-09-06 11:00:00', '00:10:14', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(192, 11, 'Stage 11', 28.05, '2015-09-06 11:15:00', '00:26:35', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(193, 11, 'Stage 12', 24.98, '2015-09-06 11:30:00', '00:13:20', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(194, 11, 'Stage 13', 3.85, '2015-09-06 11:45:00', '00:22:14', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(195, 11, 'Stage 14', 27.14, '2015-09-06 12:00:00', '00:15:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(196, 11, 'Stage 15', 13.42, '2015-09-06 12:15:00', '00:08:53', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(197, 11, 'Stage 16', 26.62, '2015-09-06 12:30:00', '00:06:03', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(198, 11, 'Stage 17', 9.23, '2015-09-06 12:45:00', '00:11:05', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(199, 12, 'Stage 0', 2.91, '2015-09-13 11:00:00', '00:21:08', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(200, 12, 'Stage 1', 25.51, '2015-09-13 11:15:00', '00:15:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(201, 12, 'Stage 2', 3.63, '2015-09-13 11:30:00', '00:04:15', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(202, 12, 'Stage 3', 6.17, '2015-09-13 11:45:00', '00:28:04', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(203, 12, 'Stage 4', 15.48, '2015-09-13 12:00:00', '00:10:05', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(204, 12, 'Stage 5', 26.14, '2015-09-13 12:15:00', '00:22:38', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(205, 12, 'Stage 6', 13.80, '2015-09-13 12:30:00', '00:13:06', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(206, 12, 'Stage 7', 27.16, '2015-09-13 12:45:00', '00:09:37', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(207, 12, 'Stage 8', 19.98, '2015-09-13 13:00:00', '00:05:52', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(208, 12, 'Stage 9', 6.89, '2015-09-13 13:15:00', '00:05:37', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(209, 12, 'Stage 10', 9.63, '2015-09-13 13:30:00', '00:12:08', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(210, 12, 'Stage 11', 26.54, '2015-09-13 13:45:00', '00:23:22', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(211, 12, 'Stage 12', 28.32, '2015-09-13 14:00:00', '00:07:05', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(212, 12, 'Stage 13', 16.83, '2015-09-13 14:15:00', '00:07:30', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(213, 12, 'Stage 14', 9.10, '2015-09-13 14:30:00', '00:14:08', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(214, 12, 'Stage 15', 16.81, '2015-09-13 14:45:00', '00:15:51', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(215, 12, 'Stage 16', 8.34, '2015-09-13 15:00:00', '00:23:19', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(216, 12, 'Stage 17', 21.11, '2015-09-13 15:15:00', '00:09:45', '2015-08-04 16:27:18', '2015-08-04 16:27:18', 0),
	(217, 13, 'Stage 0', 16.77, '2015-09-20 08:45:00', '00:29:38', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(218, 13, 'Stage 1', 16.59, '2015-09-20 09:00:00', '00:27:33', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(219, 13, 'Stage 2', 24.69, '2015-09-20 09:15:00', '00:12:20', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(220, 13, 'Stage 3', 15.17, '2015-09-20 09:30:00', '00:09:02', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(221, 13, 'Stage 4', 25.64, '2015-09-20 09:45:00', '00:04:54', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(222, 13, 'Stage 5', 13.93, '2015-09-20 10:00:00', '00:24:28', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(223, 13, 'Stage 6', 11.74, '2015-09-20 10:15:00', '00:12:49', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(224, 13, 'Stage 7', 2.69, '2015-09-20 10:30:00', '00:03:26', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(225, 13, 'Stage 8', 25.67, '2015-09-20 10:45:00', '00:05:03', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(226, 13, 'Stage 9', 10.51, '2015-09-20 11:00:00', '00:27:43', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(227, 13, 'Stage 10', 14.72, '2015-09-20 11:15:00', '00:03:34', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(228, 13, 'Stage 11', 5.90, '2015-09-20 11:30:00', '00:15:50', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(229, 13, 'Stage 12', 23.07, '2015-09-20 11:45:00', '00:14:30', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(230, 13, 'Stage 13', 16.02, '2015-09-20 12:00:00', '00:08:34', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(231, 13, 'Stage 14', 26.03, '2015-09-20 12:15:00', '00:15:54', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(232, 13, 'Stage 15', 5.10, '2015-09-20 12:30:00', '00:12:14', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(233, 13, 'Stage 16', 16.89, '2015-09-20 12:45:00', '00:16:40', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(234, 13, 'Stage 17', 5.72, '2015-09-20 13:00:00', '00:05:38', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(235, 14, 'Stage 0', 27.80, '2015-09-27 08:15:00', '00:02:52', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(236, 14, 'Stage 1', 29.98, '2015-09-27 08:30:00', '00:12:26', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(237, 14, 'Stage 2', 17.73, '2015-09-27 08:45:00', '00:05:57', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(238, 14, 'Stage 3', 10.73, '2015-09-27 09:00:00', '00:28:55', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(239, 14, 'Stage 4', 11.45, '2015-09-27 09:15:00', '00:16:56', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(240, 14, 'Stage 5', 28.77, '2015-09-27 09:30:00', '00:21:46', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(241, 14, 'Stage 6', 7.42, '2015-09-27 09:45:00', '00:03:41', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(242, 14, 'Stage 7', 13.35, '2015-09-27 10:00:00', '00:08:33', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(243, 14, 'Stage 8', 10.17, '2015-09-27 10:15:00', '00:14:27', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(244, 14, 'Stage 9', 25.98, '2015-09-27 10:30:00', '00:18:29', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(245, 14, 'Stage 10', 10.32, '2015-09-27 10:45:00', '00:12:25', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(246, 14, 'Stage 11', 29.35, '2015-09-27 11:00:00', '00:07:02', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(247, 14, 'Stage 12', 3.44, '2015-09-27 11:15:00', '00:05:04', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(248, 14, 'Stage 13', 23.43, '2015-09-27 11:30:00', '00:14:17', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(249, 14, 'Stage 14', 16.05, '2015-09-27 11:45:00', '00:27:29', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(250, 14, 'Stage 15', 16.18, '2015-09-27 12:00:00', '00:22:57', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(251, 14, 'Stage 16', 16.42, '2015-09-27 12:15:00', '00:28:14', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(252, 14, 'Stage 17', 22.10, '2015-09-27 12:30:00', '00:05:10', '2015-08-04 16:27:19', '2015-08-04 16:27:19', 0),
	(253, 15, 'SS 1', 28.79, '2015-08-20 14:50:00', '16:44:01', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(254, 15, 'SS 2', 6.07, '2015-08-20 15:05:00', '16:24:58', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(255, 15, 'SS 3', 22.40, '2015-08-20 15:20:00', '16:37:58', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(256, 15, 'SS 4', 28.84, '2015-08-20 15:35:00', '16:43:47', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(257, 15, 'SS 5', 11.45, '2015-08-20 15:50:00', '16:29:34', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(258, 15, 'SS 6', 16.67, '2015-08-20 16:05:00', '16:34:46', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(259, 15, 'SS 7', 19.87, '2015-08-20 16:20:00', '16:37:32', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(260, 15, 'SS 8', 24.03, '2015-08-20 16:35:00', '16:41:26', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(261, 15, 'SS 9', 20.75, '2015-08-20 16:50:00', '16:36:14', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(262, 15, 'SS 10', 16.54, '2015-08-20 17:05:00', '16:31:22', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(263, 15, 'SS 11', 29.57, '2015-08-20 17:20:00', '16:43:35', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(264, 15, 'SS 12', 12.63, '2015-08-20 17:35:00', '16:29:51', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(265, 15, 'SS 13', 21.17, '2015-08-20 17:50:00', '16:37:09', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(266, 15, 'SS 14', 10.53, '2015-08-20 18:05:00', '16:27:49', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(267, 15, 'SS 15', 6.25, '2015-08-20 18:20:00', '16:25:02', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(268, 15, 'SS 16', 23.47, '2015-08-20 18:35:00', '16:35:55', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0),
	(269, 15, 'SS 17', 22.18, '2015-08-20 18:50:00', '16:39:38', '2015-08-07 16:20:21', '2015-08-07 16:20:21', 0);
/*!40000 ALTER TABLE `rally_stage` ENABLE KEYS */;

-- Dumping data for table ral.rally_stage_result: 0 rows
DELETE FROM `rally_stage_result`;
/*!40000 ALTER TABLE `rally_stage_result` DISABLE KEYS */;
/*!40000 ALTER TABLE `rally_stage_result` ENABLE KEYS */;

-- Dumping data for table ral.rally_surface: 30 rows
DELETE FROM `rally_surface`;
/*!40000 ALTER TABLE `rally_surface` DISABLE KEYS */;
INSERT INTO `rally_surface` (`id`, `rally_id`, `surface`, `percentage`, `created_at`, `updated_at`) VALUES
	(1, 1, 'tarmac', 87.52, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(2, 1, 'gravel', 12.48, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(3, 2, 'tarmac', 52.56, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(4, 2, 'rain', 47.44, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(5, 3, 'tarmac', 14.46, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(6, 3, 'rain', 85.54, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(7, 4, 'tarmac', 38.70, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(8, 4, 'snow', 61.30, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(9, 5, 'gravel', 55.37, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(10, 5, 'rain', 44.63, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(11, 6, 'rain', 67.62, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(12, 6, 'snow', 32.38, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(13, 7, 'tarmac', 20.08, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(14, 7, 'gravel', 79.92, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(15, 8, 'gravel', 67.24, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(16, 8, 'snow', 32.76, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(17, 9, 'rain', 17.00, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(18, 9, 'snow', 83.00, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(19, 10, 'gravel', 28.79, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(20, 10, 'rain', 71.21, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(21, 11, 'tarmac', 48.46, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(22, 11, 'rain', 51.54, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(23, 12, 'tarmac', 54.37, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(24, 12, 'rain', 45.63, '2015-08-04 16:27:18', '2015-08-04 16:27:18'),
	(25, 13, 'tarmac', 86.47, '2015-08-04 16:27:19', '2015-08-04 16:27:19'),
	(26, 13, 'snow', 13.53, '2015-08-04 16:27:19', '2015-08-04 16:27:19'),
	(27, 14, 'gravel', 24.62, '2015-08-04 16:27:19', '2015-08-04 16:27:19'),
	(28, 14, 'rain', 75.38, '2015-08-04 16:27:19', '2015-08-04 16:27:19'),
	(29, 15, 'rain', 20.00, '2015-08-07 16:20:21', '2015-08-07 16:20:21'),
	(30, 15, 'gravel', 80.00, '2015-08-07 16:20:21', '2015-08-07 16:20:21');
/*!40000 ALTER TABLE `rally_surface` ENABLE KEYS */;

-- Dumping data for table ral.team_finance: 37 rows
DELETE FROM `team_finance`;
/*!40000 ALTER TABLE `team_finance` DISABLE KEYS */;
INSERT INTO `team_finance` (`id`, `team_id`, `amount`, `description`, `save_date`, `income`, `detailed_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(2, 2, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(3, 3, 50000, 'Initial FastRally bonus', '2015-08-05 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(4, 4, 50000, 'Initial FastRally bonus', '2015-07-26 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(5, 5, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(6, 6, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(7, 7, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:18', 1, 8, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(8, 8, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(9, 9, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(10, 10, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(11, 11, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(12, 12, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(13, 13, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(14, 14, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(15, 15, 50000, 'Initial FastRally bonus', '2015-08-04 16:27:19', 1, 8, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(16, 16, 50000, 'Initial FastRally bonus', '2015-08-04 17:01:16', 1, 8, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(17, 17, 50000, 'Initial FastRally bonus', '2015-08-04 17:18:34', 1, 8, '2015-08-04 17:18:34', '2015-08-04 17:18:34', NULL),
	(18, 14, 5000, 'Putting player Seb Exum on transfer list', '2015-08-05 10:08:36', 0, 6, '2015-08-05 10:08:36', '2015-08-05 10:08:36', NULL),
	(19, 14, 150, 'Putting player James Kowalski on transfer list', '2015-08-05 10:08:47', 0, 6, '2015-08-05 10:08:47', '2015-08-05 10:08:47', NULL),
	(20, 13, 150, 'Putting player Andrew House on transfer list', '2015-08-05 10:12:49', 0, 6, '2015-08-05 10:12:49', '2015-08-05 10:12:49', NULL),
	(21, 13, 550, 'Arrival of player James Kowalski', '2015-08-05 10:56:28', 0, 6, '2015-08-05 10:56:28', '2015-08-05 10:56:28', NULL),
	(22, 14, 550, 'Sell of player James Kowalski', '2015-08-05 10:56:28', 1, 3, '2015-08-05 10:56:28', '2015-08-05 10:56:28', NULL),
	(23, 3, 804, 'Arrival of player James Kowalski', '2015-08-05 11:00:58', 0, 6, '2015-08-05 11:00:58', '2015-08-05 11:00:58', NULL),
	(24, 14, 804, 'Sell of player James Kowalski', '2015-08-05 11:00:58', 1, 3, '2015-08-05 11:00:58', '2015-08-05 11:00:58', NULL),
	(25, 1, 150, 'Putting car Mitsubishi Lancer Pro Evo X #5664 on market', '2015-08-05 11:04:21', 0, 6, '2015-08-05 11:04:21', '2015-08-05 11:04:21', NULL),
	(26, 11, 150, 'Putting car Mitsubishi Lancer Pro Evo X #21409 on market', '2015-08-05 11:14:34', 0, 6, '2015-08-05 11:14:34', '2015-08-05 11:14:34', NULL),
	(27, 3, 2655, 'Arrival of car Mitsubishi Lancer Pro Evo X #5664', '2015-08-05 11:25:26', 0, 6, '2015-08-05 11:25:26', '2015-08-05 11:25:26', NULL),
	(28, 1, 2655, 'Sell of player Mitsubishi Lancer Pro Evo X #5664', '2015-08-05 11:25:26', 1, 3, '2015-08-05 11:25:26', '2015-08-05 11:25:26', NULL),
	(29, 3, 2655, 'Arrival of car Mitsubishi Lancer Pro Evo X #5664', '2015-08-05 11:26:03', 0, 6, '2015-08-05 11:26:03', '2015-08-05 11:26:03', NULL),
	(30, 1, 2655, 'Sell of player Mitsubishi Lancer Pro Evo X #5664', '2015-08-05 11:26:03', 1, 3, '2015-08-05 11:26:03', '2015-08-05 11:26:03', NULL),
	(31, 8, 150, 'Putting car Fiat punto #17685 on market', '2015-08-05 11:34:48', 0, 6, '2015-08-05 11:34:48', '2015-08-05 11:34:48', NULL),
	(32, 3, 2, 'Bought car Fiat punto #17685', '2015-08-05 11:42:32', 0, 6, '2015-08-05 11:42:32', '2015-08-05 11:42:32', NULL),
	(33, 8, 2, 'Sold car Fiat punto #17685', '2015-08-05 11:42:32', 1, 3, '2015-08-05 11:42:32', '2015-08-05 11:42:32', NULL),
	(34, 6, 150, 'Putting car Auto 4 #21735 on market', '2015-08-05 11:45:36', 0, 6, '2015-08-05 11:45:36', '2015-08-05 11:45:36', NULL),
	(35, 15, 150, 'Putting car Auto 4 #1357 on market', '2015-08-05 11:53:19', 0, 6, '2015-08-05 11:53:19', '2015-08-05 11:53:19', NULL),
	(36, 13, 46300, 'Car Subaru Impreza 2.0 P1 was bought', '2015-08-05 12:20:13', 0, 7, '2015-08-05 12:20:13', '2015-08-05 12:20:13', NULL),
	(37, 18, 50000, 'Initial FastRally bonus', '2015-08-05 16:00:53', 1, 8, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL);
/*!40000 ALTER TABLE `team_finance` ENABLE KEYS */;

-- Dumping data for table ral.team_sponsor_list: 3 rows
DELETE FROM `team_sponsor_list`;
/*!40000 ALTER TABLE `team_sponsor_list` DISABLE KEYS */;
INSERT INTO `team_sponsor_list` (`id`, `name`, `slug`, `description`, `active`, `logo`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'City Network', 'city-network', 'City Network is one of Swedens leading hosting companies and offers everything from simple packages to advanced managed solutions. You can get a generous package including e-mail and lots of space for as little as 5 USD per month. City Network is the exclusive host of ManagerZone and makes sure each manager gets the best access to the site.', 1, 'sponsor_city_network.sponsor_city_network', '2015-04-03 17:46:19', '2015-04-03 18:20:03', NULL),
	(2, 'Goal.com', 'goalcom', 'Goal.com is the ultimate resource for football fans with the aim of giving life to the largest football community on the planet. It covers the whole spectrum of the football world with news, statistics, photos, videos, transfer info, polls, games, fan forums, and much more.', 0, 'sponsor_goal.sponsor_goal', '2015-04-03 18:22:37', '2015-04-03 18:22:37', NULL),
	(3, 'ESPN', 'espn', 'ESPN Inc., the Worldwide Leader in Sports, is a multinational, multimedia sports entertainment company comprised of six domestic and 19 international television networks, a recently launched interactive TV channel, the largest sports radio network, ESPN The Magazine, ESPN.com, sports-themed dining and entertainment establishments, event management, ESPN Enterprises, wireless and broadband services, and more. ESPN features the broadest portfolio of multimedia assets in sports marketing with more than 40 national and international business interests or entities.', 0, 'sponsor_espn.sponsor_espn', '2015-04-03 18:23:17', '2015-04-03 18:23:17', NULL);
/*!40000 ALTER TABLE `team_sponsor_list` ENABLE KEYS */;

-- Dumping data for table ral.team_team: 18 rows
DELETE FROM `team_team`;
/*!40000 ALTER TABLE `team_team` DISABLE KEYS */;
INSERT INTO `team_team` (`id`, `user_id`, `name`, `driver1_id`, `driver2_id`, `pilot1_id`, `pilot2_id`, `car1_id`, `car2_id`, `cash`, `sponsor_id`, `league_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'Team_1', NULL, NULL, NULL, NULL, NULL, NULL, 55160, NULL, 2.10, '2015-08-04 16:27:18', '2015-08-05 11:26:03', NULL),
	(2, 2, 'Team_2', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.10, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(3, 3, 'Team_3', NULL, NULL, NULL, NULL, NULL, NULL, 43884, 2, 2.10, '2015-08-04 16:27:18', '2015-08-05 11:42:32', NULL),
	(4, 4, 'Team_4', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.10, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(5, 5, 'Team_5', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.10, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(6, 6, 'Team_6', NULL, NULL, NULL, NULL, NULL, NULL, 49850, NULL, 2.10, '2015-08-04 16:27:18', '2015-08-05 11:45:36', NULL),
	(7, 7, 'Team_7', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.10, '2015-08-04 16:27:18', '2015-08-04 16:27:18', NULL),
	(8, 8, 'Team_8', NULL, NULL, NULL, NULL, NULL, NULL, 49852, NULL, 2.20, '2015-08-04 16:27:18', '2015-08-05 11:42:32', NULL),
	(9, 9, 'Team_9', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(10, 10, 'Team_10', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(11, 11, 'Team_11', NULL, NULL, NULL, NULL, NULL, NULL, 49850, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-05 11:14:34', NULL),
	(12, 12, 'Team_12', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-04 16:27:19', NULL),
	(13, 13, 'Team_13', NULL, NULL, NULL, NULL, NULL, NULL, 3000, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-05 12:20:13', NULL),
	(14, 14, 'Team_14', NULL, NULL, NULL, NULL, NULL, NULL, 46204, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-05 11:00:58', NULL),
	(15, 15, 'Team_15', NULL, NULL, NULL, NULL, NULL, NULL, 49850, NULL, 2.20, '2015-08-04 16:27:19', '2015-08-05 11:53:19', NULL),
	(16, 16, 'Team_16', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.20, '2015-08-04 17:01:16', '2015-08-04 17:01:16', NULL),
	(17, 18, 'Team_18', NULL, NULL, NULL, NULL, NULL, NULL, 50000, 1, 2.20, '2015-08-04 17:18:34', '2015-08-04 18:01:07', NULL),
	(18, 20, 'Team_20', NULL, NULL, NULL, NULL, NULL, NULL, 50000, NULL, 2.20, '2015-08-05 16:00:53', '2015-08-05 16:00:53', NULL);
/*!40000 ALTER TABLE `team_team` ENABLE KEYS */;

-- Dumping data for table ral.user_board: 0 rows
DELETE FROM `user_board`;
/*!40000 ALTER TABLE `user_board` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_board` ENABLE KEYS */;

-- Dumping data for table ral.user_friends: 0 rows
DELETE FROM `user_friends`;
/*!40000 ALTER TABLE `user_friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_friends` ENABLE KEYS */;

-- Dumping data for table ral.user_invite: 3 rows
DELETE FROM `user_invite`;
/*!40000 ALTER TABLE `user_invite` DISABLE KEYS */;
INSERT INTO `user_invite` (`id`, `user_id`, `email`, `created_at`, `updated_at`) VALUES
	(1, 3, 'tomasz.kardas20@gmail.com', '2015-08-04 16:35:28', '2015-08-04 16:35:28'),
	(2, 3, 'dsada@dsada.pl', '2015-08-04 16:48:49', '2015-08-04 16:48:49'),
	(3, 3, 'dsada@dsadada.pl', '2015-08-04 16:49:53', '2015-08-04 16:49:53');
/*!40000 ALTER TABLE `user_invite` ENABLE KEYS */;

-- Dumping data for table ral.user_notification: 0 rows
DELETE FROM `user_notification`;
/*!40000 ALTER TABLE `user_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_notification` ENABLE KEYS */;

-- Dumping data for table ral.user_premium_log: 4 rows
DELETE FROM `user_premium_log`;
/*!40000 ALTER TABLE `user_premium_log` DISABLE KEYS */;
INSERT INTO `user_premium_log` (`id`, `amount`, `user_id`, `income`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 20, 3, 0, '7 days gold membership', '2015-08-04 16:32:38', '2015-08-04 16:32:38', NULL),
	(2, 20, 1, 0, '7 days gold membership', '2015-08-05 13:17:38', '2015-08-05 13:17:38', NULL),
	(3, 80, 1, 0, '90 days gold membership', '2015-08-05 13:17:46', '2015-08-05 13:17:46', NULL),
	(4, 80, 1, 0, '90 days gold membership', '2015-08-05 13:18:31', '2015-08-05 13:18:31', NULL);
/*!40000 ALTER TABLE `user_premium_log` ENABLE KEYS */;

-- Dumping data for table ral.user_user: 18 rows
DELETE FROM `user_user`;
/*!40000 ALTER TABLE `user_user` DISABLE KEYS */;
INSERT INTO `user_user` (`id`, `first_name`, `last_name`, `email`, `username`, `salt`, `password`, `role`, `token`, `active`, `premium`, `referer`, `gold_member`, `gold_member_expire`, `referer_paid`, `referer_not_active`, `created_at`, `last_active`, `updated_at`, `deleted_at`) VALUES
	(1, NULL, NULL, 'peop1153137@kardimobile.pl', 'peop_1153137', '2700d5c61954700899c7e09a0ba3ca45', 'cd918f5416c1ea18d4c8fc2b53b71499', 'user', '2700d5c61954700899c7e09a0ba3ca45', 1, 20, NULL, 1, '2016-02-08 13:17:38', 0, 0, '2015-08-04 16:27:17', '2015-08-07 16:24:55', '2015-08-07 16:24:55', NULL),
	(2, NULL, NULL, 'peop1166425@kardimobile.pl', 'peop_1166425', 'abf18df95f6a7e25204096f514362f5f', '5d13bb14515e4ed0d6bf4b23803531fa', 'user', 'abf18df95f6a7e25204096f514362f5f', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:18', '2015-08-05 11:57:47', '2015-08-05 11:57:47', NULL),
	(3, NULL, NULL, 'peop1128534@kardimobile.pl', 'kk33', '7baf806acc43ad103f8d84ac7171cd8f', '0613210aeddc1d25cfb1854e682ea278', 'user', '7baf806acc43ad103f8d84ac7171cd8f', 1, 80, NULL, 1, '2015-08-11 16:32:38', 0, 0, '2015-08-04 16:27:18', '2015-08-07 16:43:33', '2015-08-07 16:43:33', NULL),
	(4, NULL, NULL, 'peop1057544@kardimobile.pl', 'peop_1057544', 'd53f9d7345633cf1a8dc57b56958d20b', '6ff29a3ae5942df31d41ab1a519b1a3f', 'user', '0c36fc79ac8ff111cc0737a44b30dd69', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:18', '2015-08-05 11:58:01', '2015-08-05 11:58:01', NULL),
	(5, NULL, NULL, 'peop1191248@kardimobile.pl', 'peop_1191248', '14676095b78dd05a4f364a0adfd413ff', '8952afe94795b8de39cc8b58f817d61f', 'user', '14676095b78dd05a4f364a0adfd413ff', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:18', '2015-08-05 11:05:25', '2015-08-05 11:05:25', NULL),
	(6, NULL, NULL, 'peop1190143@kardimobile.pl', 'peop_1190143', '2329a8f7dacc735fb41dd15dda7f5dbc', '89c535bd0d01bf61e979227ef71add35', 'user', '2329a8f7dacc735fb41dd15dda7f5dbc', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:18', '2015-08-07 16:23:38', '2015-08-07 16:23:38', NULL),
	(7, NULL, NULL, 'peop1078992@kardimobile.pl', 'peop_1078992', '3f16c7ff7b4e27c3791f4dc3f848d68a', '95e6901759b670cc3d255b8898d2ba79', 'user', '3f16c7ff7b4e27c3791f4dc3f848d68a', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:18', '2015-08-07 16:23:49', '2015-08-07 16:23:49', NULL),
	(8, NULL, NULL, 'peop1167926@kardimobile.pl', 'peop_1167926', '73c193ff46f2104fad7e0b8583593e37', '2eef827465bbc87d9d37d9850e6d03c0', 'user', '73c193ff46f2104fad7e0b8583593e37', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:18', '2015-08-05 11:34:48', '2015-08-05 11:34:48', NULL),
	(9, NULL, NULL, 'peop1066382@kardimobile.pl', 'peop_1066382', '9f51863abd9f73fc45660c01ab0f55b3', 'b9c315d7e3ddeb99ec98189d1330700e', 'user', '9f51863abd9f73fc45660c01ab0f55b3', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:24:07', '2015-08-07 16:24:07', NULL),
	(10, NULL, NULL, 'peop1015344@kardimobile.pl', 'peop_1015344', 'aeab0b83c9c5d5aea56a6c7cf8fd0e95', '5a670d3978cdd86002150810afb64822', 'user', 'aeab0b83c9c5d5aea56a6c7cf8fd0e95', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:24:16', '2015-08-07 16:24:16', NULL),
	(11, NULL, NULL, 'peop1117383@kardimobile.pl', 'peop_1117383', 'e97790bc818a723510936a8ba4e08fb6', 'f66e3d7984b3516a96edc58ec7319f25', 'user', 'e97790bc818a723510936a8ba4e08fb6', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:25:21', '2015-08-07 16:25:21', NULL),
	(12, NULL, NULL, 'peop1031573@kardimobile.pl', 'peop_1031573', 'c85e53fc8ab7efef767612606aaf703b', 'f694dada21ced07f8675183934a94a2d', 'user', '92139366268af18e725e2c7ca176a091', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:24:31', '2015-08-07 16:24:31', NULL),
	(13, NULL, NULL, 'peop1004553@kardimobile.pl', 'peop_1004553', '6b26ec1f7d7641d69cb142dca4e9370a', '45f32d0cf8349aea0961b89219cf7194', 'user', '6b26ec1f7d7641d69cb142dca4e9370a', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:24:36', '2015-08-07 16:24:36', NULL),
	(14, NULL, NULL, 'peop1190900@kardimobile.pl', 'peop_1190900', '0cb62f70f000448807129f2910aea74b', '7421067aba54006911706f6efe50ccbb', 'user', '628f00a7ed47f74920f76c6762fdeaa4', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:24:42', '2015-08-07 16:24:42', NULL),
	(15, NULL, NULL, 'peop1148505@kardimobile.pl', 'peop_1148505', 'ee0f39de3d10b2b521da9b6e39eafadb', 'e32c8697fbda745690fac33ac1380bc9', 'user', 'ee0f39de3d10b2b521da9b6e39eafadb', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-04 16:27:19', '2015-08-07 16:24:48', '2015-08-07 16:24:48', NULL),
	(19, NULL, NULL, 'moderator', 'moderator', '93d07212d9fc94c440ea40c637b0a87a', '3752e702bcf99c77e5c91271b034d32c', 'moderator', '93d07212d9fc94c440ea40c637b0a87a', 1, NULL, 3, 0, NULL, 0, 0, '2015-08-04 17:18:18', '2015-08-05 12:38:33', '2015-08-05 12:38:33', NULL),
	(18, NULL, NULL, 'biuro@kardimobile.pl', 'km123', '93d07212d9fc94c440ea40c637b0a87a', '3752e702bcf99c77e5c91271b034d32c', 'user', '93d07212d9fc94c440ea40c637b0a87a', 1, NULL, 3, 0, NULL, 0, 0, '2015-08-04 17:18:18', '2015-08-07 16:25:12', '2015-08-07 16:25:12', NULL),
	(20, NULL, NULL, 'cron@kardimobile.pl', 'admin', '48fd05708efcfdf88f30d7b5eb68725a', 'c7721b616347c470ad6bba999c384464', 'admin', '48fd05708efcfdf88f30d7b5eb68725a', 1, NULL, NULL, 0, NULL, 0, 0, '2015-08-05 16:00:32', '2015-08-07 16:23:52', '2015-08-07 16:23:52', NULL);
/*!40000 ALTER TABLE `user_user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

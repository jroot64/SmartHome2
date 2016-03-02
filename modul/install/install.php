<?php
/**
 *
 */

function install(){

    $db = Database::getinstanceInstall();
    $dbName = Settings::getDBname();
    $salt = genSalt();
    $hash = genHash( $_POST['admin-password'], $salt);
    $login = $_POST['login'];
    $name = $_POST['name'];

    //    :login , :hash , :salt , 1, 1, :userName

    $result = $db->query("
CREATE DATABASE $dbName;
USE $dbName;

CREATE TABLE IF NOT EXISTS `access` (
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `access`
--

INSERT INTO `access` (`level`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mod_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `actions`
--

INSERT INTO `actions` (`id`, `mod_id`, `action_id`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5),
(6, 2, 6),
(7, 2, 7),
(8, 3, 1),
(9, 3, 2),
(10, 3, 3),
(11, 4, 1),
(12, 4, 2),
(13, 4, 3),
(14, 5, 1),
(15, 5, 2),
(16, 5, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `actuator`
--

CREATE TABLE IF NOT EXISTS `actuator` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `devicecode` varchar(5) DEFAULT NULL,
  `housecode` varchar(5) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `error_code`
--

CREATE TABLE IF NOT EXISTS `error_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ecode` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `level` int(11) DEFAULT '3',
  `text` varchar(255) DEFAULT NULL
) CHARSET=utf8;


INSERT INTO `error_code` (`id`, `ecode`, `module_id`, `level`, `text`) VALUES
(1, 1, 1, 3, 'Test Fehler'),
(2, 1, 2, 2, 'Die Erstellung eines Aktors ohne House- und/oder Devicecode wurde abgebrochen.'),
(3, 2, 2, 2, 'Der Aktor existiert bereits.'),
(4, 4, 1, 3, 'Die generierung eines Panels ohne Inhalt wurde abgebrochen.'),
(5, 5, 1, 2, 'Es wurde ein Panel ohne Titel erzeugt.'),
(6, 6, 1, 1, 'Es sollte ein Panel ohne Größenangabe erzeugt werden.'),
(7, 8, 1, 2, 'Der Benutzer ist Inaktiv. Bitte wenden sie sich an den Administrator.'),
(8, 7, 1, 1, 'Der Benutzername oder das Passwort sind nicht korrekt.'),
(9, 1, 5, 3, 'Ein Benutzer ohne Angaben zum Login, Passwort und Namen ist nicht zulässig.');
-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `group`
--

CREATE TABLE IF NOT EXISTS `group` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(45) DEFAULT NULL
) CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `group_member`
--

CREATE TABLE IF NOT EXISTS `group_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `group_id` int(11) NOT NULL,
  `actuator_id` int(11) NOT NULL,
  `inuse` int(11) NOT NULL DEFAULT '1'
) CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `log_actuator`
--

CREATE TABLE IF NOT EXISTS `log_actuator` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `time` timestamp NULL DEFAULT NULL,
  `condition` tinyint(1) DEFAULT NULL,
  `actuator_id` int(11) NOT NULL
) CHARSET=utf8;

--
-- Tabellenstruktur f�r Tabelle `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
) CHARSET=utf8;

--
-- Daten f�r Tabelle `menu`
--

INSERT INTO `menu` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `menu_entry`
--

CREATE TABLE IF NOT EXISTS `menu_entry` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(45) DEFAULT NULL,
  `getvar` varchar(45) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `symbol` int(11) NOT NULL,
  `access_id` int(11) NOT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `menu_entry`
--

INSERT INTO `menu_entry` (`id`, `name`, `getvar`, `menu_id`, `sort`, `symbol`, `access_id`) VALUES
(1, 'Settings', 'settings', 1, 1, 150, 1),
(3, 'Home', 'home', 1, 0, 104, 100);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `menu_entry_modul`
--

CREATE TABLE IF NOT EXISTS `menu_entry_modul` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `menu_entry_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `menu_entry_modul`
--

INSERT INTO `menu_entry_modul` (`id`, `menu_entry_id`, `module_id`, `access_id`) VALUES
(14, 1, 5, 1),
(1, 1, 2, 2),
(2, 3, 2, 2),
(10, 1, 3, 2),
(11, 1, 4, 2),
(12, 3, 3, 2),
(13, 3, 4, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `module`
--

CREATE TABLE IF NOT EXISTS `module` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(45) DEFAULT NULL,
  `class` varchar(45) DEFAULT NULL,
  `com-file` varchar(255) NOT NULL,
  `include-file` varchar(255) NOT NULL,
  `js-Class` varchar(255) NOT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `module`
--

INSERT INTO `module` (`id`, `name`, `class`, `com-file`, `include-file`, `js-Class`) VALUES
(1, 'system', NULL, '', '', ''),
(2, 'Actuator', 'ActuatorWrapper', 'modul/actuator/com.php', '/modul/actuator/include.php', 'modul/actuator/Actuator.js'),
(3, 'Group', 'GroupWrapper', 'modul/group/com.php', '/modul/group/include.php', 'modul/group/Group.js'),
(4, 'Room', 'RoomWrapper', 'modul/room/com.php', '/modul/room/include.php', 'modul/room/Room.js'),
(5, 'Users', 'UsersWrapper', 'modul/users/com.php', '/modul/users/include.php', ' modul/users/User.js');

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `modul_entry_settings`
--

CREATE TABLE IF NOT EXISTS `modul_entry_settings` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(45) DEFAULT NULL,
  `val` varchar(45) DEFAULT NULL,
  `menu_entry_modul_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `modul_entry_settings`
--

INSERT INTO `modul_entry_settings` (`id`, `type`, `val`, `menu_entry_modul_id`, `access_id`) VALUES
(7, 'sd', 'true', 1, 2),
(8, 'sh', 'true', 1, 2),
(9, 'cs', 'true', 1, 2),
(10, 'cr', 'true', 1, 2),
(11, 'cg', 'true', 1, 2),
(12, 'ca', 'true', 1, 2),
(16, 'sd', 'true', 2, 2),
(17, 'sh', 'true', 2, 2),
(18, 'cs', 'true', 2, 2),
(19, 'da', 'true', 1, 1),
(20, 'aa', 'true', 1, 2),
(30, 'size', 'col-sm-12', 1, 2),
(40, 'size', 'col-xs-12 col-sm-6 col-md-4 col-lg-3', 2, 2),
(53, 'sn', 'true', 10, 2),
(54, 'cs', 'true', 10, 2),
(55, 'dg', 'true', 10, 2),
(56, 'ag', 'true', 10, 2),
(57, 'size', 'col-md-6 col-lg-4', 10, 2),
(58, 'sn', 'true', 11, 2),
(59, 'cs', 'true', 11, 2),
(60, 'dr', 'true', 11, 2),
(61, 'ar', 'true', 11, 2),
(62, 'size', 'col-md-6 col-lg-4', 11, 2),
(63, 'sn', 'true', 12, 2),
(64, 'cs', 'true', 12, 2),
(65, 'size', 'col-xs-12 col-sm-6 col-md-4 col-lg-3 	\n', 12, 2),
(66, 'sn', 'true', 13, 2),
(67, 'cs', 'true', 13, 2),
(68, 'size', 'col-xs-12 col-sm-6 col-md-4 col-lg-3 	\n', 13, 2),
(70, 'size', 'col-xs-12 col-sm-12 col-md-10 col-lg-8 	', 14, 2),
(71, 'sn', 'true', 14, 2),
(72, 'sl', 'true', 14, 2),
(73, 'cs', 'true', 14, 2),
(74, 'du', 'true', 14, 2),
(75, 'au', 'true', 14, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `modul_settings`
--

CREATE TABLE IF NOT EXISTS `modul_settings` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(45) DEFAULT NULL,
  `val` varchar(45) DEFAULT NULL,
  `module_id` int(11) NOT NULL
) CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `room`
--

CREATE TABLE IF NOT EXISTS `room` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(45) DEFAULT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `room`
--

INSERT INTO `room` (`id`, `name`) VALUES
(1, 'NO ROOM');
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `savedErrors`
--

CREATE TABLE IF NOT EXISTS `savedErrors` (
  `errorCode_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `symbols`
--

CREATE TABLE IF NOT EXISTS `symbols` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `symbol_tag` varchar(45) DEFAULT NULL
) CHARSET=utf8;

--
-- Daten f�r Tabelle `symbols`
--

INSERT INTO `symbols` (`id`, `symbol_tag`) VALUES
(1, 'arrow_up'),
(2, 'arrow_down'),
(3, 'arrow_left'),
(4, 'arrow_right'),
(5, 'arrow_left-up'),
(6, 'arrow_right-up'),
(7, 'arrow_right-down'),
(8, 'arrow_left-down'),
(9, 'arrow-up-down'),
(10, 'arrow_up-down_alt'),
(11, 'arrow_left-right_alt'),
(12, 'arrow_left-right'),
(13, 'arrow_expand_alt'),
(14, 'arrow_expand_alt'),
(15, 'arrow_condense'),
(16, 'arrow_expand'),
(17, 'arrow_move'),
(18, 'arrow_carrot-up'),
(19, 'arrow_carrot-down'),
(20, 'arrow_carrot-left'),
(21, 'arrow_carrot-right'),
(22, 'arrow_carrot-up'),
(23, 'arrow_carrot-down'),
(24, 'arrow_carrot-left'),
(25, 'arrow_carrot-right'),
(26, 'arrow_carrot-up_alt'),
(27, 'arrow_carrot-down_alt'),
(28, 'arrow_carrot-left_alt'),
(29, 'arrow_carrot-right_alt'),
(30, 'arrow_carrot-up_alt'),
(31, 'arrow_carrot-down_alt'),
(32, 'arrow_carrot-left_alt'),
(33, 'arrow_carrot-right_alt'),
(34, 'arrow_triangle-up'),
(35, 'arrow_triangle-down'),
(36, 'arrow_triangle-left'),
(37, 'arrow_triangle-right'),
(38, 'arrow_triangle-up_alt'),
(39, 'arrow_triangle-down_alt'),
(40, 'arrow_triangle-left_alt'),
(41, 'arrow_triangle-right_alt'),
(42, 'arrow_back'),
(43, 'icon_minus-'),
(44, 'icon_plus'),
(45, 'icon_close'),
(46, 'icon_check'),
(47, 'icon_minus_alt'),
(48, 'icon_plus_alt'),
(49, 'icon_close_alt'),
(50, 'icon_check_alt'),
(51, 'icon_zoom-out_alt'),
(52, 'icon_zoom-in_alt'),
(53, 'icon_search'),
(54, 'icon_box-empty'),
(55, 'icon_box-selected'),
(56, 'icon_minus-box'),
(57, 'icon_plus-box'),
(58, 'icon_box-checked'),
(59, 'icon_circle-empty'),
(60, 'icon_circle-slelected'),
(61, 'icon_stop_alt'),
(62, 'icon_stop'),
(63, 'icon_pause_alt'),
(64, 'icon_pause'),
(65, 'icon_menu'),
(66, 'icon_menu-square_alt'),
(67, 'icon_menu-circle_alt'),
(68, 'icon_ul'),
(69, 'icon_ol'),
(70, 'icon_adjust-horiz'),
(71, 'icon_adjust-vert'),
(72, 'icon_document_alt'),
(73, 'icon_documents_alt'),
(74, 'icon_pencil'),
(75, 'icon_pencil-edit_alt'),
(76, 'icon_pencil-edit'),
(77, 'icon_folder-alt'),
(78, 'icon_folder-open_alt'),
(79, 'icon_folder-add_alt'),
(80, 'icon_info_alt'),
(81, 'icon_error-oct_alt'),
(82, 'icon_error-circle_alt'),
(83, 'icon_error-triangle_alt'),
(84, 'icon_question_alt'),
(85, 'icon_question'),
(86, 'icon_comment_alt'),
(87, 'icon_chat_alt'),
(88, 'icon_vol-mute_alt'),
(89, 'icon_volume-low_alt'),
(90, 'icon_volume-high_alt'),
(91, 'icon_quotations'),
(92, 'icon_quotations_alt'),
(93, 'icon_clock_alt'),
(94, 'icon_lock_alt'),
(95, 'icon_lock-open_alt'),
(96, 'icon_key_alt'),
(97, 'icon_cloud_alt'),
(98, 'icon_cloud-upload_alt'),
(99, 'icon_cloud-download_alt'),
(100, 'icon_image'),
(101, 'icon_images'),
(102, 'icon_lightbulb_alt'),
(103, 'icon_gift_alt'),
(104, 'icon_house_alt'),
(105, 'icon_genius'),
(106, 'icon_mobile'),
(107, 'icon_tablet'),
(108, 'icon_laptop'),
(109, 'icon_desktop'),
(110, 'icon_camera_alt'),
(111, 'icon_mail_alt'),
(112, 'icon_cone_alt'),
(113, 'icon_ribbon_alt'),
(114, 'icon_bag_alt'),
(115, 'icon_creditcard'),
(116, 'icon_cart_alt'),
(117, 'icon_paperclip'),
(118, 'icon_tag_alt'),
(119, 'icon_tags_alt'),
(120, 'icon_trash_alt'),
(121, 'icon_cursor_alt'),
(122, 'icon_mic_alt'),
(123, 'icon_compass_alt'),
(124, 'icon_pin_alt'),
(125, 'icon_pushpin_alt'),
(126, 'icon_map_alt'),
(127, 'icon_drawer_alt'),
(128, 'icon_toolbox_alt'),
(129, 'icon_book_alt'),
(130, 'icon_calendar'),
(131, 'icon_film'),
(132, 'icon_table'),
(133, 'icon_contacts_alt'),
(134, 'icon_headphones'),
(135, 'icon_lifesaver'),
(136, 'icon_piechart'),
(137, 'icon_refresh'),
(138, 'icon_link_alt'),
(139, 'icon_link'),
(140, 'icon_loading'),
(141, 'icon_blocked'),
(142, 'icon_archive_alt'),
(143, 'icon_heart_alt'),
(144, 'icon_star_alt'),
(145, 'icon_star-half_alt'),
(146, 'icon_star'),
(147, 'icon_star-half'),
(148, 'icon_tools'),
(149, 'icon_tool'),
(150, 'icon_cog'),
(151, 'icon_cogs'),
(152, 'arrow_up_alt'),
(153, 'arrow_down_alt'),
(154, 'arrow_left_alt'),
(155, 'arrow_right_alt'),
(156, 'arrow_left-up_alt'),
(157, 'arrow_right-up_alt'),
(158, 'arrow_right-down_alt'),
(159, 'arrow_left-down_alt'),
(160, 'arrow_condense_alt'),
(161, 'arrow_expand_alt'),
(162, 'arrow_carrot_up_alt'),
(163, 'arrow_carrot-down_alt'),
(164, 'arrow_carrot-left_alt'),
(165, 'arrow_carrot-right_alt'),
(166, 'arrow_carrot-up_alt'),
(167, 'arrow_carrot-dwnn_alt'),
(168, 'arrow_carrot-left_alt'),
(169, 'arrow_carrot-right_alt'),
(170, 'arrow_triangle-up_alt'),
(171, 'arrow_triangle-down_alt'),
(172, 'arrow_triangle-left_alt'),
(173, 'arrow_triangle-right_alt'),
(174, 'icon_minus_alt'),
(175, 'icon_plus_alt'),
(176, 'icon_close_alt'),
(177, 'icon_check_alt'),
(178, 'icon_zoom-out'),
(179, 'icon_zoom-in'),
(180, 'icon_stop_alt'),
(181, 'icon_menu-square_alt'),
(182, 'icon_menu-circle_alt'),
(183, 'icon_document'),
(184, 'icon_documents'),
(185, 'icon_pencil_alt'),
(186, 'icon_folder'),
(187, 'icon_folder-open'),
(188, 'icon_folder-add'),
(189, 'icon_folder_upload'),
(190, 'icon_folder_download'),
(191, 'icon_info'),
(192, 'icon_error-circle'),
(193, 'icon_error-oct'),
(194, 'icon_error-triangle'),
(195, 'icon_question_alt'),
(196, 'icon_comment'),
(197, 'icon_chat'),
(198, 'icon_vol-mute'),
(199, 'icon_volume-low'),
(200, 'icon_volume-high'),
(201, 'icon_quotations_alt'),
(202, 'icon_clock'),
(203, 'icon_lock'),
(204, 'icon_lock-open'),
(205, 'icon_key'),
(206, 'icon_cloud'),
(207, 'icon_cloud-upload'),
(208, 'icon_cloud-download'),
(209, 'icon_lightbulb'),
(210, 'icon_gift'),
(211, 'icon_house'),
(212, 'icon_camera'),
(213, 'icon_mail'),
(214, 'icon_cone'),
(215, 'icon_ribbon'),
(216, 'icon_bag'),
(217, 'icon_cart'),
(218, 'icon_tag'),
(219, 'icon_tags'),
(220, 'icon_trash'),
(221, 'icon_cursor'),
(222, 'icon_mic'),
(223, 'icon_compass'),
(224, 'icon_pin'),
(225, 'icon_pushpin'),
(226, 'icon_map'),
(227, 'icon_drawer'),
(228, 'icon_toolbox'),
(229, 'icon_book'),
(230, 'icon_contacts'),
(231, 'icon_archive'),
(232, 'icon_heart'),
(233, 'icon_profile'),
(234, 'icon_group'),
(235, 'icon_grid-x'),
(236, 'icon_grid-x'),
(237, 'icon_music'),
(238, 'icon_pause_alt'),
(239, 'icon_phone'),
(240, 'icon_upload'),
(241, 'icon_download'),
(242, 'social_facebook'),
(243, 'social_twitter'),
(244, 'social_pinterest'),
(245, 'social_googleplus'),
(246, 'social_tumblr'),
(247, 'social_tumbleupon'),
(248, 'social_wordpress'),
(249, 'social_instagram'),
(250, 'social_dribbble'),
(251, 'social_vimeo'),
(252, 'social_linkedin'),
(253, 'social_rss'),
(254, 'social_deviantart'),
(255, 'social_share'),
(256, 'social_myspace'),
(257, 'social_skype'),
(258, 'social_youtube'),
(259, 'social_picassa'),
(260, 'social_googledrive'),
(261, 'social_flickr'),
(262, 'social_blogger'),
(263, 'social_spotify'),
(264, 'social_delicious'),
(265, 'social_facebook_circle'),
(266, 'social_twitter_circle'),
(267, 'social_pinterest_circle'),
(268, 'social_googleplus_circle'),
(269, 'social_tumblr_circle'),
(270, 'social_stumbleupon_circle'),
(271, 'social_wordpress_circle'),
(272, 'social_instagram_circle'),
(273, 'social_dribbble_circle'),
(274, 'social_vimeo_circle'),
(275, 'social_linkedin_circle'),
(276, 'social_rss_circle'),
(277, 'social_deviantart_circle'),
(278, 'social_share_circle'),
(279, 'social_myspace_circle'),
(280, 'social_skype_circle'),
(281, 'social_youtube_circle'),
(282, 'social_googledrive_alt'),
(283, 'social_flickr_circle'),
(284, 'social_blogger_circle'),
(285, 'social_spotify_circle'),
(286, 'social_delicious_circle'),
(287, 'social_facebook_square'),
(288, 'social_twitter_square'),
(289, 'social_pinterest_square'),
(290, 'social_googleplus_square'),
(291, 'social_tumblr_square'),
(292, 'social_stumbleupon_square'),
(293, 'social_wordpress_square'),
(294, 'social_instagram_square'),
(295, 'social_dribbble_square'),
(296, 'social_vimeo_square'),
(297, 'social_linkedin_square'),
(298, 'social_rss_square'),
(299, 'social_deviantart_square'),
(300, 'social_share_square'),
(301, 'social_myspace_square'),
(302, 'social_skype_square'),
(303, 'social_youtube_square'),
(304, 'social_picassa_square'),
(305, 'social_googledrive_square'),
(306, 'social_flickr_square'),
(307, 'social_blogger_square'),
(308, 'social_spotify_square'),
(309, 'social_delicious_square'),
(310, 'icon_printer'),
(311, 'icon_calulator'),
(312, 'icon_building'),
(313, 'icon_floppy'),
(314, 'icon_drive'),
(315, 'icon_search-'),
(316, 'icon_id'),
(317, 'icon_id-'),
(318, 'icon_puzzle'),
(319, 'icon_like'),
(320, 'icon_dislike'),
(321, 'icon_mug'),
(322, 'icon_currency'),
(323, 'icon_wallet'),
(324, 'icon_pens'),
(325, 'icon_easel'),
(326, 'icon_flowchart'),
(327, 'icon_datareport'),
(328, 'icon_briefcase'),
(329, 'icon_shield'),
(330, 'icon_percent'),
(331, 'icon_globe'),
(332, 'icon_globe-'),
(333, 'icon_target'),
(334, 'icon_hourglass'),
(335, 'icon_balance'),
(336, 'icon_rook'),
(337, 'icon_printer-alt'),
(338, 'icon_calculator_alt'),
(339, 'icon_building_alt'),
(340, 'icon_floppy_alt'),
(341, 'icon_drive_alt'),
(342, 'icon_search_alt'),
(343, 'icon_id_alt'),
(344, 'icon_id-_alt'),
(345, 'icon_puzzle_alt'),
(346, 'icon_like_alt'),
(347, 'icon_dislike_alt'),
(348, 'icon_mug_alt'),
(349, 'icon_currency_alt'),
(350, 'icon_wallet_alt'),
(351, 'icon_pens_alt'),
(352, 'icon_easel_alt'),
(353, 'icon_flowchart_alt'),
(354, 'icon_datareport_alt'),
(355, 'icon_briefcase_alt'),
(356, 'icon_shield_alt'),
(357, 'icon_percent_alt'),
(358, 'icon_globe_alt'),
(359, 'icon_clipboard');

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `token`
--

CREATE TABLE IF NOT EXISTS `token` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `action_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `loginname` varchar(45) DEFAULT NULL,
  `passphrase` varchar(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `access_id` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL
) CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes f�r die Tabelle `access`
--

ALTER TABLE `actions`
 ADD KEY `mod_id` (`mod_id`,`action_id`);

--
-- Indizes f�r die Tabelle `actuator`
--
ALTER TABLE `actuator`
 ADD KEY `fk_actuator_room_idx` (`room_id`), ADD KEY `devicecode` (`devicecode`,`housecode`,`status`,`room_id`);

--
-- Indizes f�r die Tabelle `group_member`
--
ALTER TABLE `group_member`
 ADD KEY `fk_group_member_group1_idx` (`group_id`), ADD KEY `fk_group_member_actuator1_idx` (`actuator_id`);

--
-- Indizes f�r die Tabelle `log_actuator`
--
ALTER TABLE `log_actuator`
 ADD KEY `fk_log_actuator_actuator1_idx` (`actuator_id`);

--
-- Indizes f�r die Tabelle `menu_entry`
--
ALTER TABLE `menu_entry`
 ADD KEY `fk_menu_entry_menu1_idx` (`menu_id`), ADD KEY `getvar` (`getvar`,`menu_id`,`sort`);

--
-- Indizes f�r die Tabelle `menu_entry_modul`
--
ALTER TABLE `menu_entry_modul`
 ADD KEY `fk_menu_entry_modul_menu_entry1_idx` (`menu_entry_id`), ADD KEY `fk_menu_entry_modul_module1_idx` (`module_id`), ADD KEY `access_id` (`access_id`), ADD KEY `menu_entry_id` (`menu_entry_id`,`module_id`,`access_id`);

--
-- Indizes f�r die Tabelle `module`
--
ALTER TABLE `module`
 ADD KEY `id` (`id`,`name`);

--
-- Indizes f�r die Tabelle `modul_entry_settings`
--
ALTER TABLE `modul_entry_settings`
 ADD KEY `fk_modul_entry_settings_menu_entry_modul1_idx` (`menu_entry_modul_id`), ADD KEY `id` (`id`,`menu_entry_modul_id`,`access_id`);

--
-- Indizes f�r die Tabelle `modul_settings`
--
ALTER TABLE `modul_settings`
 ADD KEY `fk_modul_settings_module1_idx` (`module_id`), ADD KEY `id` (`id`,`module_id`);

--
-- Indizes f�r die Tabelle `room`
--
ALTER TABLE `room`
 ADD KEY `id` (`id`);

--
-- Indizes f�r die Tabelle `token`
--
ALTER TABLE `token`
 ADD KEY `id` (`id`,`action_id`,`user_id`,`active`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `actuator`
--
ALTER TABLE `actuator`
ADD CONSTRAINT `fk_actuator_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `group_member`
--
ALTER TABLE `group_member`
ADD CONSTRAINT `fk_group_member_actuator1` FOREIGN KEY (`actuator_id`) REFERENCES `actuator` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_group_member_group1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `log_actuator`
--
ALTER TABLE `log_actuator`
ADD CONSTRAINT `fk_log_actuator_actuator1` FOREIGN KEY (`actuator_id`) REFERENCES `actuator` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `menu_entry`
--
ALTER TABLE `menu_entry`
ADD CONSTRAINT `fk_menu_entry_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `menu_entry_modul`
--
ALTER TABLE `menu_entry_modul`
ADD CONSTRAINT `fk_menu_entry_modul_menu_entry1` FOREIGN KEY (`menu_entry_id`) REFERENCES `menu_entry` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_menu_entry_modul_module1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `modul_entry_settings`
--
ALTER TABLE `modul_entry_settings`
ADD CONSTRAINT `fk_modul_entry_settings_menu_entry_modul1` FOREIGN KEY (`menu_entry_modul_id`) REFERENCES `menu_entry_modul` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `modul_settings`
--
ALTER TABLE `modul_settings`
ADD CONSTRAINT `fk_modul_settings_module1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
");
//var_dump($result);
//    if($result){
//        unlink("./modul/install/install.php");
//        unlink("./modul/install/configadminlogin.php");
//        unlink("./install.php");
//    }
  $db->addBound($_POST['login']);
  $db->addParam('login');
  $db->addBound($hash);
  $db->addParam('hash');
  $db->addBound($salt);
  $db->addParam('salt');
  $db->addBound($_POST['name']);
  $db->addParam('userName');

  $result = $db->query("INSERT INTO `user` (`id`, `loginname`, `passphrase`, `salt`, `state`, `access_id`, `name`) VALUES
(0, :login , :hash , :salt , 1, 1, :userName );
");
  var_dump( $result);
if($result){
  header('location: index.php?page=home');
}

}

function genHash( $password , $salt){
    $hash = hash('sha256' , hash('sha256', $password ) . $salt );
    return $hash;
}

function genSalt(){
    $salt = time();
    return $salt;
}
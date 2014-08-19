# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.73-0ubuntu0.10.04.1)
# Database: route-pages
# Generation Time: 2014-08-19 15:09:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table rp_commentmeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_commentmeta`;

CREATE TABLE `rp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table rp_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_comments`;

CREATE TABLE `rp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_comments` WRITE;
/*!40000 ALTER TABLE `rp_comments` DISABLE KEYS */;

INSERT INTO `rp_comments` (`comment_ID`, `comment_post_ID`, `comment_author`, `comment_author_email`, `comment_author_url`, `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`, `comment_agent`, `comment_type`, `comment_parent`, `user_id`)
VALUES
	(1,1,'Mr WordPress','','https://wordpress.org/','','2014-08-18 14:03:04','2014-08-18 14:03:04','Hi, this is a comment.\nTo delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.',0,'1','','',0,0);

/*!40000 ALTER TABLE `rp_comments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_links`;

CREATE TABLE `rp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table rp_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_options`;

CREATE TABLE `rp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_options` WRITE;
/*!40000 ALTER TABLE `rp_options` DISABLE KEYS */;

INSERT INTO `rp_options` (`option_id`, `option_name`, `option_value`, `autoload`)
VALUES
	(1,'siteurl','http://route-pages.local','yes'),
	(2,'blogname','Route Pages','yes'),
	(3,'blogdescription','Just another WordPress site','yes'),
	(4,'users_can_register','0','yes'),
	(5,'admin_email','luca.tumedei@gmail.com','yes'),
	(6,'start_of_week','1','yes'),
	(7,'use_balanceTags','0','yes'),
	(8,'use_smilies','1','yes'),
	(9,'require_name_email','1','yes'),
	(10,'comments_notify','1','yes'),
	(11,'posts_per_rss','10','yes'),
	(12,'rss_use_excerpt','0','yes'),
	(13,'mailserver_url','mail.example.com','yes'),
	(14,'mailserver_login','login@example.com','yes'),
	(15,'mailserver_pass','password','yes'),
	(16,'mailserver_port','110','yes'),
	(17,'default_category','1','yes'),
	(18,'default_comment_status','open','yes'),
	(19,'default_ping_status','open','yes'),
	(20,'default_pingback_flag','0','yes'),
	(21,'posts_per_page','10','yes'),
	(22,'date_format','F j, Y','yes'),
	(23,'time_format','g:i a','yes'),
	(24,'links_updated_date_format','F j, Y g:i a','yes'),
	(25,'comment_moderation','0','yes'),
	(26,'moderation_notify','1','yes'),
	(27,'permalink_structure','','yes'),
	(28,'gzipcompression','0','yes'),
	(29,'hack_file','0','yes'),
	(30,'blog_charset','UTF-8','yes'),
	(31,'moderation_keys','','no'),
	(33,'home','http://route-pages.local','yes'),
	(34,'category_base','','yes'),
	(35,'ping_sites','http://rpc.pingomatic.com/','yes'),
	(36,'advanced_edit','0','yes'),
	(37,'comment_max_links','2','yes'),
	(38,'gmt_offset','0','yes'),
	(39,'default_email_category','1','yes'),
	(40,'recently_edited','','no'),
	(41,'template','twentyfourteen','yes'),
	(42,'stylesheet','twentyfourteen','yes'),
	(43,'comment_whitelist','1','yes'),
	(44,'blacklist_keys','','no'),
	(45,'comment_registration','0','yes'),
	(46,'html_type','text/html','yes'),
	(47,'use_trackback','0','yes'),
	(48,'default_role','subscriber','yes'),
	(49,'db_version','27916','yes'),
	(50,'uploads_use_yearmonth_folders','1','yes'),
	(51,'upload_path','','yes'),
	(52,'blog_public','0','yes'),
	(53,'default_link_category','2','yes'),
	(54,'show_on_front','posts','yes'),
	(55,'tag_base','','yes'),
	(56,'show_avatars','1','yes'),
	(57,'avatar_rating','G','yes'),
	(58,'upload_url_path','','yes'),
	(59,'thumbnail_size_w','150','yes'),
	(60,'thumbnail_size_h','150','yes'),
	(61,'thumbnail_crop','1','yes'),
	(62,'medium_size_w','300','yes'),
	(63,'medium_size_h','300','yes'),
	(64,'avatar_default','mystery','yes'),
	(65,'large_size_w','1024','yes'),
	(66,'large_size_h','1024','yes'),
	(67,'image_default_link_type','file','yes'),
	(68,'image_default_size','','yes'),
	(69,'image_default_align','','yes'),
	(70,'close_comments_for_old_posts','0','yes'),
	(71,'close_comments_days_old','14','yes'),
	(72,'thread_comments','1','yes'),
	(73,'thread_comments_depth','5','yes'),
	(74,'page_comments','0','yes'),
	(75,'comments_per_page','50','yes'),
	(76,'default_comments_page','newest','yes'),
	(77,'comment_order','asc','yes'),
	(78,'sticky_posts','a:0:{}','yes'),
	(79,'widget_categories','a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),
	(80,'widget_text','a:0:{}','yes'),
	(81,'widget_rss','a:0:{}','yes'),
	(82,'uninstall_plugins','a:1:{s:49:\"log-deprecated-notices/log-deprecated-notices.php\";a:2:{i:0;s:14:\"Deprecated_Log\";i:1;s:12:\"on_uninstall\";}}','no'),
	(83,'timezone_string','','yes'),
	(84,'page_for_posts','0','yes'),
	(85,'page_on_front','0','yes'),
	(86,'default_post_format','0','yes'),
	(87,'link_manager_enabled','0','yes'),
	(88,'initial_db_version','27916','yes'),
	(89,'rp_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:62:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:9:\"add_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),
	(90,'widget_search','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),
	(91,'widget_recent-posts','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),
	(92,'widget_recent-comments','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),
	(93,'widget_archives','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),
	(94,'widget_meta','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),
	(95,'sidebars_widgets','a:5:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}s:13:\"array_version\";i:3;}','yes'),
	(96,'cron','a:4:{i:1408370595;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1408370602;a:1:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1408389960;a:1:{s:20:\"wp_maybe_auto_update\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}s:7:\"version\";i:2;}','yes'),
	(97,'_transient_doing_cron','1408460954.5314049720764160156250','yes'),
	(98,'_site_transient_update_core','O:8:\"stdClass\":3:{s:7:\"updates\";a:0:{}s:15:\"version_checked\";s:5:\"3.9.1\";s:12:\"last_checked\";i:1408460143;}','yes'),
	(99,'_site_transient_update_plugins','O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1408460828;s:7:\"checked\";a:10:{s:31:\"airplane-mode/airplane-mode.php\";s:5:\"0.0.1\";s:23:\"debug-bar/debug-bar.php\";s:5:\"0.8.1\";s:74:\"debug-bar-actions-and-filters-addon/debug-bar-action-and-filters-addon.php\";s:5:\"1.4.1\";s:41:\"debug-bar-extender/debug-bar-extender.php\";s:3:\"0.5\";s:59:\"debug-bar-list-dependencies/debug-bar-list-dependencies.php\";s:5:\"1.0.6\";s:49:\"log-deprecated-notices/log-deprecated-notices.php\";s:3:\"0.2\";s:23:\"my-routes/my_routes.php\";s:5:\"0.1.0\";s:51:\"rewrite-rules-inspector/rewrite-rules-inspector.php\";s:5:\"1.2.1\";s:27:\"route-pages/route_pages.php\";s:5:\"0.1.0\";s:23:\"wp-router/wp-router.php\";s:3:\"0.5\";}s:8:\"response\";a:0:{}s:12:\"translations\";a:0:{}}','yes'),
	(111,'_site_transient_timeout_theme_roots','1408461943','yes'),
	(112,'_site_transient_theme_roots','a:3:{s:14:\"twentyfourteen\";s:7:\"/themes\";s:14:\"twentythirteen\";s:7:\"/themes\";s:12:\"twentytwelve\";s:7:\"/themes\";}','yes'),
	(102,'_site_transient_update_themes','O:8:\"stdClass\":1:{s:12:\"last_checked\";i:1408460143;}','yes'),
	(103,'can_compress_scripts','0','yes'),
	(104,'_transient_timeout_plugin_slugs','1408547354','no'),
	(105,'_transient_plugin_slugs','a:10:{i:0;s:31:\"airplane-mode/airplane-mode.php\";i:1;s:23:\"debug-bar/debug-bar.php\";i:2;s:74:\"debug-bar-actions-and-filters-addon/debug-bar-action-and-filters-addon.php\";i:3;s:41:\"debug-bar-extender/debug-bar-extender.php\";i:4;s:59:\"debug-bar-list-dependencies/debug-bar-list-dependencies.php\";i:5;s:49:\"log-deprecated-notices/log-deprecated-notices.php\";i:6;s:23:\"my-routes/my_routes.php\";i:7;s:51:\"rewrite-rules-inspector/rewrite-rules-inspector.php\";i:8;s:27:\"route-pages/route_pages.php\";i:9;s:23:\"wp-router/wp-router.php\";}','no'),
	(106,'recently_activated','a:0:{}','yes'),
	(107,'log_deprecated_notices','a:2:{s:11:\"last_viewed\";s:19:\"2014-08-18 14:04:55\";s:10:\"db_version\";i:4;}','yes'),
	(108,'_site_transient_timeout_poptags_40cd750bba9870f18aada2478b24840a','1408381555','yes'),
	(109,'_site_transient_poptags_40cd750bba9870f18aada2478b24840a','a:40:{s:6:\"widget\";a:3:{s:4:\"name\";s:6:\"widget\";s:4:\"slug\";s:6:\"widget\";s:5:\"count\";s:4:\"4587\";}s:4:\"post\";a:3:{s:4:\"name\";s:4:\"Post\";s:4:\"slug\";s:4:\"post\";s:5:\"count\";s:4:\"2848\";}s:6:\"plugin\";a:3:{s:4:\"name\";s:6:\"plugin\";s:4:\"slug\";s:6:\"plugin\";s:5:\"count\";s:4:\"2785\";}s:5:\"admin\";a:3:{s:4:\"name\";s:5:\"admin\";s:4:\"slug\";s:5:\"admin\";s:5:\"count\";s:4:\"2284\";}s:5:\"posts\";a:3:{s:4:\"name\";s:5:\"posts\";s:4:\"slug\";s:5:\"posts\";s:5:\"count\";s:4:\"2189\";}s:7:\"sidebar\";a:3:{s:4:\"name\";s:7:\"sidebar\";s:4:\"slug\";s:7:\"sidebar\";s:5:\"count\";s:4:\"1792\";}s:6:\"google\";a:3:{s:4:\"name\";s:6:\"google\";s:4:\"slug\";s:6:\"google\";s:5:\"count\";s:4:\"1587\";}s:7:\"twitter\";a:3:{s:4:\"name\";s:7:\"twitter\";s:4:\"slug\";s:7:\"twitter\";s:5:\"count\";s:4:\"1563\";}s:6:\"images\";a:3:{s:4:\"name\";s:6:\"images\";s:4:\"slug\";s:6:\"images\";s:5:\"count\";s:4:\"1529\";}s:8:\"comments\";a:3:{s:4:\"name\";s:8:\"comments\";s:4:\"slug\";s:8:\"comments\";s:5:\"count\";s:4:\"1519\";}s:4:\"page\";a:3:{s:4:\"name\";s:4:\"page\";s:4:\"slug\";s:4:\"page\";s:5:\"count\";s:4:\"1448\";}s:9:\"shortcode\";a:3:{s:4:\"name\";s:9:\"shortcode\";s:4:\"slug\";s:9:\"shortcode\";s:5:\"count\";s:4:\"1411\";}s:5:\"image\";a:3:{s:4:\"name\";s:5:\"image\";s:4:\"slug\";s:5:\"image\";s:5:\"count\";s:4:\"1350\";}s:8:\"facebook\";a:3:{s:4:\"name\";s:8:\"Facebook\";s:4:\"slug\";s:8:\"facebook\";s:5:\"count\";s:4:\"1209\";}s:3:\"seo\";a:3:{s:4:\"name\";s:3:\"seo\";s:4:\"slug\";s:3:\"seo\";s:5:\"count\";s:4:\"1153\";}s:5:\"links\";a:3:{s:4:\"name\";s:5:\"links\";s:4:\"slug\";s:5:\"links\";s:5:\"count\";s:4:\"1121\";}s:9:\"wordpress\";a:3:{s:4:\"name\";s:9:\"wordpress\";s:4:\"slug\";s:9:\"wordpress\";s:5:\"count\";s:4:\"1044\";}s:6:\"social\";a:3:{s:4:\"name\";s:6:\"social\";s:4:\"slug\";s:6:\"social\";s:5:\"count\";s:4:\"1001\";}s:7:\"gallery\";a:3:{s:4:\"name\";s:7:\"gallery\";s:4:\"slug\";s:7:\"gallery\";s:5:\"count\";s:3:\"995\";}s:7:\"widgets\";a:3:{s:4:\"name\";s:7:\"widgets\";s:4:\"slug\";s:7:\"widgets\";s:5:\"count\";s:3:\"823\";}s:5:\"pages\";a:3:{s:4:\"name\";s:5:\"pages\";s:4:\"slug\";s:5:\"pages\";s:5:\"count\";s:3:\"811\";}s:5:\"email\";a:3:{s:4:\"name\";s:5:\"email\";s:4:\"slug\";s:5:\"email\";s:5:\"count\";s:3:\"798\";}s:3:\"rss\";a:3:{s:4:\"name\";s:3:\"rss\";s:4:\"slug\";s:3:\"rss\";s:5:\"count\";s:3:\"793\";}s:6:\"jquery\";a:3:{s:4:\"name\";s:6:\"jquery\";s:4:\"slug\";s:6:\"jquery\";s:5:\"count\";s:3:\"789\";}s:5:\"media\";a:3:{s:4:\"name\";s:5:\"media\";s:4:\"slug\";s:5:\"media\";s:5:\"count\";s:3:\"730\";}s:4:\"ajax\";a:3:{s:4:\"name\";s:4:\"AJAX\";s:4:\"slug\";s:4:\"ajax\";s:5:\"count\";s:3:\"693\";}s:5:\"video\";a:3:{s:4:\"name\";s:5:\"video\";s:4:\"slug\";s:5:\"video\";s:5:\"count\";s:3:\"691\";}s:10:\"javascript\";a:3:{s:4:\"name\";s:10:\"javascript\";s:4:\"slug\";s:10:\"javascript\";s:5:\"count\";s:3:\"661\";}s:7:\"content\";a:3:{s:4:\"name\";s:7:\"content\";s:4:\"slug\";s:7:\"content\";s:5:\"count\";s:3:\"638\";}s:10:\"buddypress\";a:3:{s:4:\"name\";s:10:\"buddypress\";s:4:\"slug\";s:10:\"buddypress\";s:5:\"count\";s:3:\"618\";}s:5:\"photo\";a:3:{s:4:\"name\";s:5:\"photo\";s:4:\"slug\";s:5:\"photo\";s:5:\"count\";s:3:\"609\";}s:5:\"login\";a:3:{s:4:\"name\";s:5:\"login\";s:4:\"slug\";s:5:\"login\";s:5:\"count\";s:3:\"607\";}s:4:\"feed\";a:3:{s:4:\"name\";s:4:\"feed\";s:4:\"slug\";s:4:\"feed\";s:5:\"count\";s:3:\"601\";}s:4:\"link\";a:3:{s:4:\"name\";s:4:\"link\";s:4:\"slug\";s:4:\"link\";s:5:\"count\";s:3:\"593\";}s:6:\"photos\";a:3:{s:4:\"name\";s:6:\"photos\";s:4:\"slug\";s:6:\"photos\";s:5:\"count\";s:3:\"587\";}s:7:\"youtube\";a:3:{s:4:\"name\";s:7:\"youtube\";s:4:\"slug\";s:7:\"youtube\";s:5:\"count\";s:3:\"546\";}s:8:\"category\";a:3:{s:4:\"name\";s:8:\"category\";s:4:\"slug\";s:8:\"category\";s:5:\"count\";s:3:\"544\";}s:4:\"spam\";a:3:{s:4:\"name\";s:4:\"spam\";s:4:\"slug\";s:4:\"spam\";s:5:\"count\";s:3:\"543\";}s:5:\"share\";a:3:{s:4:\"name\";s:5:\"Share\";s:4:\"slug\";s:5:\"share\";s:5:\"count\";s:3:\"536\";}s:8:\"security\";a:3:{s:4:\"name\";s:8:\"security\";s:4:\"slug\";s:8:\"security\";s:5:\"count\";s:3:\"534\";}}','yes'),
	(110,'airplane-mode','on','yes'),
	(113,'active_plugins','a:1:{i:0;s:31:\"airplane-mode/airplane-mode.php\";}','yes');

/*!40000 ALTER TABLE `rp_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_postmeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_postmeta`;

CREATE TABLE `rp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_postmeta` WRITE;
/*!40000 ALTER TABLE `rp_postmeta` DISABLE KEYS */;

INSERT INTO `rp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`)
VALUES
	(1,2,'_wp_page_template','default');

/*!40000 ALTER TABLE `rp_postmeta` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_posts`;

CREATE TABLE `rp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_posts` WRITE;
/*!40000 ALTER TABLE `rp_posts` DISABLE KEYS */;

INSERT INTO `rp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
VALUES
	(1,1,'2014-08-18 14:03:04','2014-08-18 14:03:04','Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!','Hello world!','','publish','open','open','','hello-world','','','2014-08-18 14:03:04','2014-08-18 14:03:04','',0,'http://route-pages.local/?p=1',0,'post','',1),
	(2,1,'2014-08-18 14:03:04','2014-08-18 14:03:04','This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:\n\n<blockquote>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my blog. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</blockquote>\n\n...or something like this:\n\n<blockquote>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</blockquote>\n\nAs a new WordPress user, you should go to <a href=\"http://route-pages.local/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!','Sample Page','','publish','open','open','','sample-page','','','2014-08-18 14:03:04','2014-08-18 14:03:04','',0,'http://route-pages.local/?page_id=2',0,'page','',0),
	(3,1,'2014-08-18 14:03:45','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2014-08-18 14:03:45','0000-00-00 00:00:00','',0,'http://route-pages.local/?p=3',0,'post','',0),
	(4,1,'2014-08-18 14:03:46','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2014-08-18 14:03:46','0000-00-00 00:00:00','',0,'http://route-pages.local/?p=4',0,'post','',0);

/*!40000 ALTER TABLE `rp_posts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_term_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_term_relationships`;

CREATE TABLE `rp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_term_relationships` WRITE;
/*!40000 ALTER TABLE `rp_term_relationships` DISABLE KEYS */;

INSERT INTO `rp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`)
VALUES
	(1,1,0);

/*!40000 ALTER TABLE `rp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_term_taxonomy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_term_taxonomy`;

CREATE TABLE `rp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `rp_term_taxonomy` DISABLE KEYS */;

INSERT INTO `rp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES
	(1,1,'category','',0,1);

/*!40000 ALTER TABLE `rp_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_terms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_terms`;

CREATE TABLE `rp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_terms` WRITE;
/*!40000 ALTER TABLE `rp_terms` DISABLE KEYS */;

INSERT INTO `rp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES
	(1,'Uncategorized','uncategorized',0);

/*!40000 ALTER TABLE `rp_terms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_usermeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_usermeta`;

CREATE TABLE `rp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_usermeta` WRITE;
/*!40000 ALTER TABLE `rp_usermeta` DISABLE KEYS */;

INSERT INTO `rp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`)
VALUES
	(1,1,'first_name',''),
	(2,1,'last_name',''),
	(3,1,'nickname','theAdmin'),
	(4,1,'description',''),
	(5,1,'rich_editing','true'),
	(6,1,'comment_shortcuts','false'),
	(7,1,'admin_color','fresh'),
	(8,1,'use_ssl','0'),
	(9,1,'show_admin_bar_front','true'),
	(10,1,'rp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),
	(11,1,'rp_user_level','10'),
	(12,1,'dismissed_wp_pointers','wp350_media,wp360_revisions,wp360_locks,wp390_widgets'),
	(13,1,'show_welcome_panel','1'),
	(14,1,'rp_dashboard_quick_press_last_post_id','4');

/*!40000 ALTER TABLE `rp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rp_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rp_users`;

CREATE TABLE `rp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rp_users` WRITE;
/*!40000 ALTER TABLE `rp_users` DISABLE KEYS */;

INSERT INTO `rp_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`)
VALUES
	(1,'theAdmin','$P$BrZb7w69wNmhXulJCTtYVE8YRh48x0.','theadmin','luca.tumedei@gmail.com','','2014-08-18 14:03:04','',0,'theAdmin');

/*!40000 ALTER TABLE `rp_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

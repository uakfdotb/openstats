SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `server` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `bans`;
CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `server` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `gamename` varchar(31) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `admin` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expiredate` varchar(31) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `warn` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page` varchar(16) COLLATE utf8_bin NOT NULL,
  `page_id` int(11) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `date` int(11) NOT NULL,
  `user_ip` varchar(16) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


DROP TABLE IF EXISTS `dotagames`;
CREATE TABLE IF NOT EXISTS `dotagames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `winner` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `sec` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gameid` (`gameid`),
  KEY `winner` (`winner`),
  KEY `min` (`min`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;


DROP TABLE IF EXISTS `dotaplayers`;
CREATE TABLE IF NOT EXISTS `dotaplayers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `colour` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `creepkills` int(11) NOT NULL,
  `creepdenies` int(11) NOT NULL,
  `assists` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `neutralkills` int(11) NOT NULL,
  `item1` char(4) NOT NULL,
  `item2` char(4) NOT NULL,
  `item3` char(4) NOT NULL,
  `item4` char(4) NOT NULL,
  `item5` char(4) NOT NULL,
  `item6` char(4) NOT NULL,
  `hero` char(4) NOT NULL,
  `newcolour` int(11) NOT NULL,
  `towerkills` int(11) NOT NULL,
  `raxkills` int(11) NOT NULL,
  `courierkills` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gameid` (`gameid`,`colour`),
  KEY `colour` (`colour`),
  KEY `newcolour` (`newcolour`),
  KEY `hero` (`hero`),
  KEY `item1` (`item1`),
  KEY `item2` (`item2`),
  KEY `item3` (`item3`),
  KEY `item4` (`item4`),
  KEY `item5` (`item5`),
  KEY `item6` (`item6`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;


DROP TABLE IF EXISTS `downloads`;
CREATE TABLE IF NOT EXISTS `downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `map` varchar(100) NOT NULL,
  `mapsize` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `name` varchar(15) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `spoofed` int(11) NOT NULL,
  `spoofedrealm` varchar(100) NOT NULL,
  `downloadtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

DROP TABLE IF EXISTS `gameplayers`;
CREATE TABLE IF NOT EXISTS `gameplayers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `spoofed` int(11) NOT NULL,
  `reserved` int(11) NOT NULL,
  `loadingtime` int(11) NOT NULL,
  `left` int(11) NOT NULL,
  `leftreason` varchar(100) NOT NULL,
  `team` int(11) NOT NULL,
  `colour` int(11) NOT NULL,
  `spoofedrealm` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gameid` (`gameid`),
  KEY `colour` (`colour`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `server` varchar(100) NOT NULL,
  `map` varchar(100) NOT NULL,
  `datetime` datetime NOT NULL,
  `gamename` varchar(31) NOT NULL,
  `ownername` varchar(15) NOT NULL,
  `duration` int(11) NOT NULL,
  `gamestate` int(11) NOT NULL,
  `creatorname` varchar(15) NOT NULL,
  `creatorserver` varchar(100) NOT NULL,
  `stats` tinyint(1) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `datetime` (`datetime`),
  KEY `map` (`map`),
  KEY `duration` (`duration`),
  KEY `gamestate` (`gamestate`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

DROP TABLE IF EXISTS `heroes`;
CREATE TABLE IF NOT EXISTS `heroes` (
  `heroid` varchar(4) NOT NULL,
  `original` varchar(4) NOT NULL,
  `description` varchar(32) NOT NULL,
  `summary` varchar(900) NOT NULL,
  `stats` varchar(300) NOT NULL,
  `skills` varchar(300) NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`heroid`),
  KEY `description` (`description`),
  KEY `original` (`original`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `itemid` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `code` smallint(10) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `shortname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `item_info` mediumtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `price` smallint(6) NOT NULL,
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` tinyint(4) NOT NULL,
  `user_group` tinyint(4) NOT NULL,
  KEY `name` (`name`),
  KEY `group` (`user_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `permissions` (`name`, `value`, `user_group`) VALUES
('can_edit_posts', 1, 2),
('can_write_posts', 1, 2),
('can_delete_posts', 1, 2),
('can_manage_games', 1, 2),
('can_delete_games', 1, 2),
('can_edit_bans', 1, 2),
('can_add_bans', 1, 2),
('can_delete_bans', 1, 2),
('manage_heroes_items', 1, 2),
('can_edit_admins', 1, 2),
('can_add_admins', 1, 2),
('can_delete_admins', 1, 2),
('can_write_comments', 1, 2),
('manage_comments', 1, 2),
('manage_users', 1, 2),
('user_permissions', 1, 2),
('configuration', 0, 2),
('can_edit_posts', 1, 1),
('can_write_posts', 1, 1),
('can_delete_posts', 1, 1),
('can_manage_games', 0, 1),
('can_delete_games', 0, 1),
('can_edit_bans', 0, 1),
('can_add_bans', 0, 1),
('can_delete_bans', 0, 1),
('manage_heroes_items', 0, 1),
('can_edit_admins', 0, 1),
('can_add_admins', 0, 1),
('can_delete_admins', 0, 1),
('can_write_comments', 1, 1),
('manage_comments', 0, 1),
('manage_users', 0, 1),
('user_permissions', 0, 1),
('configuration', 0, 1),
('can_edit_posts', 0, 0),
('can_write_posts', 0, 0),
('can_delete_posts', 0, 0),
('can_manage_games', 0, 0),
('can_delete_games', 0, 0),
('can_edit_bans', 0, 0),
('can_add_bans', 0, 0),
('can_delete_bans', 0, 0),
('manage_heroes_items', 0, 0),
('can_edit_admins', 0, 0),
('can_add_admins', 0, 0),
('can_delete_admins', 0, 0),
('can_write_comments', 1, 0),
('manage_comments', 0, 0),
('manage_users', 0, 0),
('user_permissions', 0, 0),
('configuration', 0, 0),
('can_write_comments', 1, 9),
('can_delete_admins', 1, 9),
('can_add_admins', 1, 9),
('can_edit_admins', 1, 9),
('manage_heroes_items', 1, 9),
('can_delete_bans', 1, 9),
('can_add_bans', 1, 9),
('can_edit_bans', 1, 9),
('can_delete_games', 1, 9),
('can_manage_games', 1, 9),
('can_delete_posts', 1, 9),
('can_write_posts', 1, 9),
('can_edit_posts', 1, 9),
('manage_comments', 1, 9),
('manage_users', 1, 9),
('user_permissions', 1, 9),
('configuration', 1, 9);


DROP TABLE IF EXISTS `safelist`;
CREATE TABLE IF NOT EXISTS `safelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `voucher` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `scores`;
CREATE TABLE IF NOT EXISTS `scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(25) NOT NULL,
  `name` varchar(15) NOT NULL,
  `server` varchar(100) NOT NULL,
  `score` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `score` (`score`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `stats`;
CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `score` int(11) NOT NULL,
  `games` int(11) NOT NULL,
  `wins` int(11) NOT NULL,
  `losses` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `assists` int(11) NOT NULL,
  `creeps` int(11) NOT NULL,
  `denies` int(11) NOT NULL,
  `neutrals` int(11) NOT NULL,
  `towers` int(11) NOT NULL,
  `rax` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `score` (`score`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_password` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password_hash` varchar(65) NOT NULL,
  `user_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_joined` int(11) NOT NULL,
  `user_level` tinyint(1) NOT NULL,
  `user_last_login` int(11) NOT NULL,
  `user_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_location` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_realm` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_website` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_gender` tinyint(4) NOT NULL,
  `user_fbid` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `can_comment` tinyint(4) NOT NULL DEFAULT '1',
  `code` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `confirm` varchar(65) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `last_login` (`user_last_login`),
  KEY `joined` (`user_joined`),
  KEY `confirm` (`confirm`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `w3mmdplayers`;
CREATE TABLE IF NOT EXISTS `w3mmdplayers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `category` varchar(25) NOT NULL,
  `gameid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `flag` varchar(32) NOT NULL,
  `leaver` int(11) NOT NULL,
  `practicing` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `w3mmdvars`;
CREATE TABLE IF NOT EXISTS `w3mmdvars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `varname` varchar(25) NOT NULL,
  `value_int` int(11) DEFAULT NULL,
  `value_real` double DEFAULT NULL,
  `value_string` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `news_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `news_content` mediumtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `news_date` int(11) NOT NULL,
  `news_updated` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`news_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
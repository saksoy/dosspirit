-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Vert: localhost
-- Generert den: 05. Mar, 2012 00:51 AM
-- Tjenerversjon: 5.5.16
-- PHP-Versjon: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

CREATE SCHEMA `tds`;
USE `tds`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tdscake`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `category`
--

CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_norwegian` varchar(50) NOT NULL,
  `name_english` varchar(50) NOT NULL COMMENT 'name of the category',
  `slug` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `company`
--

CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `slug` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `game`
--

CREATE TABLE `game` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `year` smallint(5) NOT NULL,
  `description` text COMMENT 'description of game',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `publish_date` datetime NOT NULL COMMENT 'date for which the game shall be published',
  `ingress` text NOT NULL COMMENT 'Ingress entry for this game entry.',
  `focus` text NOT NULL COMMENT 'Focus image for this game entry.',
  `user_id` int(11) unsigned NOT NULL COMMENT 'The original user that added this game',
  `age` tinytext NOT NULL,
  `license` smallint(5) unsigned NOT NULL DEFAULT '2' COMMENT '0 shareware, 1 demo, 2 abware, 3 freeware, 4 ad, 5 indie, 6 other',
  `dosbox_page` int(11) unsigned NOT NULL,
  `scummvm_page` tinytext NOT NULL,
  `visits` int(11) unsigned NOT NULL,
  `votes_sum` float unsigned NOT NULL COMMENT 'The added sum of all votes',
  `number_of_votes` int(11) unsigned NOT NULL COMMENT 'The total number of votes received',
  `slug` tinytext NOT NULL COMMENT 'the url to define this game',
  `active` tinyint(1) DEFAULT '1' COMMENT 'Wether this game is active and can be accessed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='contains all the games';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `game_category`
--

CREATE TABLE `game_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_game_category_to_category` (`category_id`),
  KEY `fk_game_category_to_game` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Relation table between a game and what categories it belongs';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `game_company`
--

CREATE TABLE `game_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `company_type` enum('developer','publisher') NOT NULL COMMENT 'Whether this company is a developer or publisher for the game it relates to',
  PRIMARY KEY (`id`),
  KEY `fk_game_company_to_company` (`company_id`),
  KEY `fk_game_company_to_game` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains relations between a game and what companies it has.';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `game_mode`
--

CREATE TABLE `game_mode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `mode` enum('singleplayer','multiplayer','hotseat') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mode_to_game` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Different game modes present in a game.';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `game_platform`
--

CREATE TABLE `game_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains relations between a game and its platforms';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `game_reputation`
--

CREATE TABLE `game_reputation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `date` datetime NOT NULL COMMENT 'Time when entry was added',
  `ip` tinytext NOT NULL COMMENT 'Support for IPv6',
  `user_id` int(11) unsigned NOT NULL COMMENT 'User id, if user is logged in',
  `type` enum('likes','played','owns','want') NOT NULL COMMENT 'What type of game rep this is',
  PRIMARY KEY (`id`),
  KEY `game_reputation_to_game` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='How many likes, play count and haves a game has among users';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `media`
--

CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL COMMENT 'reference to the game this media belongs to',
  `filename` tinytext COMMENT 'The file source. Should be the filename only',
  `user_id` int(11) unsigned NOT NULL COMMENT 'The user who uploaded this',
  `date` datetime NOT NULL,
  `comment` tinytext,
  `visits` int(11) unsigned DEFAULT '0',
  `type` enum('gamefile','front_cover','back_cover','media','screenshot','manual','audio','walkthrough','advertisement','ingress','focus') DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1' COMMENT 'whether this media is active and can be accessed',
  PRIMARY KEY (`id`),
  KEY `fk_media_to_game` (`game_id`),
  KEY `fk_media_to_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `media_pool`
--

CREATE TABLE `media_pool` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `created` datetime NOT NULL,
  `user_id` int(11) unsigned NOT NULL COMMENT 'The user who uploaded this.',
  `active` tinyint(1) NOT NULL COMMENT 'If this media has been accepted or not',
  `type` enum('screenshot','file','front_cover','back_cover','advertisement','other') NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `fk_media_pool_to_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='A collection of uploaded media that''s not connected to any game yet, but can be used as they see fit.';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `news_article`
--

CREATE TABLE `news_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `heading` text NOT NULL,
  `text` text NOT NULL,
  `image` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `visits` int(11) unsigned NOT NULL,
  `slug` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `platform`
--

CREATE TABLE `platform` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `slug` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE  `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `comment` text NOT NULL,
  `type` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains comments for various content';

--
-- Tabellstruktur for tabell `review`
--

CREATE TABLE `review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL COMMENT 'Foreign key to game id in game table',
  `text` text NOT NULL,
  `teaser_text` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL COMMENT 'Foreign key to user id in user table',
  `created` datetime NOT NULL COMMENT 'date review was written',
  `publish_date` datetime NOT NULL COMMENT 'date for which review shall be published',
  `modified` datetime NOT NULL COMMENT 'When was this review last edited',
  `rating` tinytext NOT NULL COMMENT 'Rating data',
  `golden` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether this title is editors choice',
  `language` enum('nor','eng') NOT NULL COMMENT 'what language this is written in',
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `total` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_review_to_game` (`game_id`),
  KEY `fk_review_to_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains review for a game';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user`
--

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL COMMENT 'Password is in hash format',
  `created` datetime NOT NULL,
  `activated` tinyint(1) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `avatar` varchar(45) DEFAULT NULL,
  `experience` int(11) unsigned NOT NULL DEFAULT '0',
  `last_logged_in` datetime DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `slug` tinytext,
  `resetkey` tinytext COMMENT 'A unique reset key only used when a user needs to reset her password',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains registered users';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_activation_code`
--

CREATE TABLE `user_activation_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_activation_code_to_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_game_collection`
--

CREATE TABLE `user_game_collection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'A soft delete trigger',
  `date` datetime NOT NULL COMMENT 'When this game was added',
  PRIMARY KEY (`id`),
  KEY `user_game_collection_to_game` (`game_id`),
  KEY `user_game_collection_to_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='A set of games a user has added to his or her personal collection';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_review`
--

CREATE TABLE `user_review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date` datetime DEFAULT NULL,
  `review` text,
  `summary` tinytext,
  `rating` tinytext,
  `accepted` tinyint(1) DEFAULT NULL,
  `accepted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_review_to_user` (`user_id`),
  KEY `fk_user_review_to_game` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `video`
--

CREATE TABLE `video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `video_source` tinytext NOT NULL,
  `video_provider` enum('google_video','youtube','other') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_video_to_game` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains videos for a game.';


CREATE TABLE  `game_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL COMMENT 'User who did the change',
  `game_id` int(11) unsigned NOT NULL COMMENT 'Game this entry belongs to',
  `data` text NOT NULL COMMENT 'Content that describes what changed',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_game_log_to_game` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains history log for changes to a title';


CREATE TABLE  `user_inbox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `activity` text NOT NULL,
  `link` TINYTEXT NOT NULL,
  `reward` int(11) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='contains user activities';

CREATE TABLE  `site_setting` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `editors_choice_id` int(11) unsigned NOT NULL,
  `editors_choice_slug` tinytext NOT NULL,
  `theme` varchar(45) default 'default' COMMENT 'Sets the theme for the entire site. Values are default, easter, summer etc.',
  `reward_game` smallint(5) unsigned default '0',
  `reward_review` smallint(5) unsigned default '0',
  `reward_media` smallint(5) unsigned default '0',
  `reward_news` smallint(5) unsigned default '0',
  `reward_poll` smallint(5) unsigned default '0',
  `reward_validate_media` smallint(5) unsigned default '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains various settings that relate directly to The DOS Spirit';

CREATE TABLE  `achievements` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `reward` int(11) unsigned NOT NULL,
  `requirement` varchar(50) NOT NULL,
  `data` int(11) unsigned NOT NULL,
  `qualifier` varchar(50) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains various achievements and requirements';

CREATE TABLE  `search_pool` (
  `id` int(11) NOT NULL auto_increment,
  `term` varchar(255) NOT NULL,
  `frequency` int(11) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'What kind of search type this is',
  PRIMARY KEY  USING BTREE (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Table that contains what has been searched on';

CREATE TABLE  `serie` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` tinytext NOT NULL COMMENT 'The serie''s name',
  `slug` tinytext NOT NULL COMMENT 'The serie''s slug',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains game series';

CREATE TABLE  `game_serie` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `game_id` int(11) unsigned NOT NULL COMMENT 'Game that this serie relation relates to',
  `serie_id` int(11) unsigned NOT NULL COMMENT 'Serie this relation relates to',
  PRIMARY KEY  (`id`),
  KEY `fk_game_serie_to_game` (`game_id`),
  KEY `fk_game_serie_to_serie` (`serie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Game to series relation table';

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `game_category`
--

ALTER TABLE `game_category`
  ADD CONSTRAINT `fk_game_category_to_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_game_category_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `game_company`
--
ALTER TABLE `game_company`
  ADD CONSTRAINT `fk_game_company_to_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_game_company_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `game_mode`
--
ALTER TABLE `game_mode`
  ADD CONSTRAINT `fk_mode_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `game_reputation`
--
ALTER TABLE `game_reputation`
  ADD CONSTRAINT `fk_game_reputation_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Begrensninger for tabell `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_media_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `media_pool`
--
ALTER TABLE `media_pool`
  ADD CONSTRAINT `fk_media_pool_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_review_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `user_activation_code`
--
ALTER TABLE `user_activation_code`
  ADD CONSTRAINT `fk_activation_code_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `user_game_collection`
--
ALTER TABLE `user_game_collection`
  ADD CONSTRAINT `fk_user_game_collection_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_game_collection_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Begrensninger for tabell `user_review`
--
ALTER TABLE `user_review`
  ADD CONSTRAINT `fk_user_review_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_review_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Begrensninger for tabell `game_log`
--
ALTER TABLE `game_log`
  ADD CONSTRAINT `fk_game_log_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  
ALTER TABLE `game_serie`
  ADD CONSTRAINT `fk_game_serie_to_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_game_serie_to_serie` FOREIGN KEY (`serie_id`) REFERENCES `serie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

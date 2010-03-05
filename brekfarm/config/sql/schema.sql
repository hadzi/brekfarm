DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` binary(36) NOT NULL,
  `slug` varchar(146) character set ascii NOT NULL,
  `title` varchar(128) NOT NULL,
  `body` mediumtext NOT NULL,
  `category_id` binary(36) NOT NULL,
  `status` varchar(16) character set ascii NOT NULL default 'draft',
  `comment_count` int(10) NOT NULL default '0',
  `created` datetime default NULL,
  `created_by` binary(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_ARTICLE` (`slug`,`category_id`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` binary(36) NOT NULL,
  `slug` varchar(72) character set ascii NOT NULL,
  `title` varchar(64) NOT NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `parent_id` binary(36) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  `item_count` int(10) NOT NULL default '0',
  `created` datetime default NULL,
  `created_by` binary(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_CATEGORY` (`slug`,`model`),
  KEY `title` (`title`),
  KEY `parent_id` (`parent_id`),
  KEY `lft` (`lft`),
  KEY `rght` (`rght`),
  KEY `item_count` (`item_count`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` binary(36) NOT NULL,
  `body` text NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) character set ascii NOT NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `foreign_key` binary(36) NOT NULL,
  `status` varchar(16) character set ascii NOT NULL default 'clean',
  `created` datetime default NULL,
  `created_by` binary(36) default NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  KEY `USER` (`name`,`email`,`created_by`),
  KEY `COMMENTED` (`model`,`foreign_key`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `favourites`;
CREATE TABLE `favourites` (
  `id` binary(36) NOT NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `foreign_key` binary(36) NOT NULL,
  `user_id` binary(36) NOT NULL,
  `weight` int(2) unsigned NOT NULL default '0',
  `created` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_FAVOURITE` (`model`,`foreign_key`,`user_id`),
  KEY `weight` (`weight`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `geocodes`;
CREATE TABLE `geocodes` (
  `id` binary(36) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(6) character set ascii NOT NULL,
  `provider` varchar(16) character set ascii NOT NULL,
  `address1` varchar(255) default NULL,
  `address2` varchar(255) default NULL,
  `address3` varchar(255) default NULL,
  `address4` varchar(255) default NULL,
  `town` varchar(90) default NULL,
  `postcode` varchar(10) character set ascii default NULL,
  `created` datetime default NULL,
  `created_by` binary(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `GEOCODE` (`lng`,`lat`),
  KEY `LOCATION` (`name`,`country`),
  KEY `provider` (`provider`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` binary(36) NOT NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `foreign_key` binary(36) NOT NULL,
  `parent_id` binary(36) default NULL,
  `title` varchar(255) default NULL,
  `path` varchar(255) character set ascii default NULL,
  `filesize` int(10) unsigned default NULL,
  `width` int(10) unsigned default NULL,
  `height` int(10) unsigned default NULL,
  `weight` int(2) unsigned NOT NULL default '0',
  `media_type` varchar(20) character set ascii default NULL,
  `status` varchar(16) character set ascii NOT NULL default 'pending',
  `created` datetime default NULL,
  `created_by` binary(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  KEY `MEDIA` (`model`,`foreign_key`),
  KEY `parent_id` (`parent_id`),
  KEY `weight` (`weight`),
  KEY `status` (`status`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` binary(36) NOT NULL,
  `code` char(3) character set ascii NOT NULL,
  `vat_included` decimal(6,2) default NULL,
  `vat_net` decimal(6,2) default NULL,
  `vat_rate` tinyint(2) default NULL,
  `vat` tinyint(2) default NULL,
  `payment_method` varchar(16) character set ascii default NULL,
  `payment_target` varchar(255) default NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `foreign_key` binary(36) NOT NULL,
  `parent_id` binary(36) default NULL,
  `status` varchar(16) character set ascii NOT NULL default 'new',
  `created` datetime default NULL,
  `created_by` binary(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_CODE` (`code`,`model`,`foreign_key`),
  KEY `vat_included` (`vat_included`),
  KEY `payment_method` (`payment_method`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `modified` (`modified`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `producers`;
CREATE TABLE `producers` (
  `id` binary(36) NOT NULL,
  `slug` varchar(146) character set ascii NOT NULL,
  `title` varchar(128) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(16) character set ascii NOT NULL,
  `email` varchar(128) character set ascii default NULL,
  `url` varchar(128) character set ascii default NULL,
  `description` text NOT NULL,
  `client_code` char(7) character set ascii NOT NULL,
  `geocode_id` binary(36) default NULL,
  `user_id` binary(36) default NULL,
  `promoter_id` binary(36) default NULL,
  `product_id` binary(36) default NULL,
  `status` varchar(16) character set ascii NOT NULL default 'new',
  `tos` tinyint(1) NOT NULL default '0',
  `approved_from` datetime default NULL,
  `approved_to` datetime default NULL,
  `weight` int(2) unsigned NOT NULL default '0',
  `rating_avg` decimal(1,1) NOT NULL default '0.0',
  `comment_count` int(10) NOT NULL default '0',
  `created` datetime default NULL,
  `created_by` binary(36) default NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `client_code` (`client_code`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `phone` (`phone`),
  KEY `email` (`email`),
  KEY `url` (`url`),
  KEY `geocode_id` (`geocode_id`),
  KEY `user_id` (`user_id`),
  KEY `promoter_id` (`promoter_id`),
  KEY `product_id` (`product_id`),
  KEY `status` (`status`),
  KEY `tos` (`tos`),
  KEY `approved_from` (`approved_from`),
  KEY `approved_to` (`approved_to`),
  KEY `weight` (`weight`),
  KEY `rating_avg` (`rating_avg`),
  KEY `comment_count` (`comment_count`),
  KEY `created` (`created`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` binary(36) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL default '0.00',
  `unit` varchar(16) NOT NULL,
  `category_id` binary(36) NOT NULL,
  `producer_id` binary(36) NOT NULL,
  `status` varchar(16) character set ascii NOT NULL default 'draft',
  `approved_from` datetime default NULL,
  `approved_to` datetime default NULL,
  `weight` int(2) unsigned NOT NULL default '0',
  `created` datetime default NULL,
  `created_by` binary(36) default NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  KEY `description` (`description`),
  KEY `price` (`price`),
  KEY `unit` (`unit`),
  KEY `category_id` (`category_id`),
  KEY `producer_id` (`producer_id`),
  KEY `status` (`status`),
  KEY `approved_from` (`approved_from`),
  KEY `approved_to` (`approved_to`),
  KEY `weight` (`weight`),
  KEY `created` (`created`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `id` binary(36) NOT NULL,
  `email` varchar(128) character set ascii NOT NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `foreign_key` binary(36) NOT NULL,
  `value` tinyint(1) NOT NULL default '0',
  `status` varchar(16) character set ascii NOT NULL default 'clean',
  `created` datetime default NULL,
  `created_by` binary(36) default NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  KEY `USER` (`email`,`created_by`),
  KEY `RATED` (`model`,`foreign_key`),
  KEY `status` (`status`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `saved_searches`;
CREATE TABLE `saved_searches` (
  `id` binary(36) NOT NULL,
  `user_id` binary(36) NOT NULL,
  `geocode_id` binary(36) default NULL,
  `title` varchar(128) NOT NULL,
  `value` varchar(255) NOT NULL,
  `distance` int(10) NOT NULL default '20',
  `weight` int(2) unsigned NOT NULL default '0',
  `created` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_TITLE` (`title`,`user_id`),
  KEY `geocode_id` (`geocode_id`),
  KEY `weight` (`weight`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `shield`;
CREATE TABLE `shield` (
  `id` binary(36) NOT NULL,
  `message` varchar(255) character set ascii NOT NULL,
  `ip` varchar(39) character set ascii NOT NULL,
  `severity` tinyint(1) NOT NULL default '0',
  `here` varchar(255) character set ascii NOT NULL,
  `referer` varchar(255) default NULL,
  `referrer` varchar(255) default NULL,
  `value` varchar(255) default NULL,
  `model` varchar(16) character set ascii default NULL,
  `foreign_key` binary(36) default NULL,
  `user_agent` varchar(255) character set ascii default NULL,
  `created` datetime default NULL,
  `created_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  KEY `message` (`message`),
  KEY `ip` (`ip`),
  KEY `severity` (`severity`),
  KEY `here` (`here`),
  KEY `referer` (`referer`),
  KEY `referrer` (`referrer`),
  KEY `MODEL_RECORD` (`model`,`foreign_key`),
  KEY `user_agent` (`user_agent`),
  KEY `created` (`created`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` binary(36) NOT NULL,
  `model` varchar(16) character set ascii NOT NULL,
  `foreign_key` binary(36) NOT NULL,
  `name` varchar(16) character set ascii NOT NULL,
  `value` char(16) character set ascii NOT NULL,
  `created` datetime default NULL,
  `created_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_TOKEN` (`model`,`foreign_key`,`name`),
  UNIQUE KEY `value` (`value`),
  KEY `created` (`created`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` binary(36) NOT NULL,
  `username` varchar(64) character set ascii NOT NULL,
  `email` varchar(128) character set ascii NOT NULL,
  `promo_code` char(6) character set ascii NOT NULL,
  `promo_rate` int(2) unsigned NOT NULL default '5',
  `status` varchar(16) character set ascii NOT NULL default 'new',
  `tos` tinyint(1) NOT NULL default '0',
  `passwd` char(40) character set ascii NOT NULL,
  `name` varchar(64) default NULL,
  `payment_method` varchar(16) character set ascii default NULL,
  `payment_target` varchar(255) default NULL,
  `geocode_id` binary(36) default NULL,
  `role` varchar(16) character set ascii default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `modified_by` binary(36) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `promo_code` (`promo_code`),
  KEY `status` (`status`),
  KEY `tos` (`tos`),
  KEY `passwd` (`passwd`),
  KEY `payment_method` (`payment_method`),
  KEY `geocode_id` (`geocode_id`),
  KEY `role` (`role`),
  KEY `created` (`created`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

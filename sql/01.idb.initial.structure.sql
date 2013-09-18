--
-- Struktura tabeli dla  `acl`
--

CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `privilege` varchar(64) DEFAULT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'allow',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `language_domains`
--

CREATE TABLE IF NOT EXISTS `language_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(64) NOT NULL,
  `lang` varchar(64) NOT NULL,
  `description` varchar(255) DEFAULT '',
  `active` smallint(6) NOT NULL DEFAULT '0',
  `layout` varchar(64) DEFAULT 'layout',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_UNIQUE` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(64) NOT NULL,
  `shortcode` varchar(8) NOT NULL,
  `code` varchar(16) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` smallint(6) NOT NULL DEFAULT '1',
  `defaultlang` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(64) NOT NULL,
  `controller` varchar(65) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `blocked` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_role_idx` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `routename` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `type` varchar(45) NOT NULL,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `regexp` text NOT NULL,
  `reverse` varchar(255) NOT NULL DEFAULT '',
  `language` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `stock_orders`
--

CREATE TABLE IF NOT EXISTS `stock_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer` varchar(512) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `limit_value` float NOT NULL,
  `number` int(11) DEFAULT NULL,
  `ticker` varchar(255) DEFAULT NULL,
  `stoploss_value` float DEFAULT NULL,
  `notes` text,
  `stockprice_now` float NOT NULL,
  `broker` int(11) NOT NULL,
  `limit_value_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `user_account_requests`
--

CREATE TABLE IF NOT EXISTS `user_account_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_idx` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

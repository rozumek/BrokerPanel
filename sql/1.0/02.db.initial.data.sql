--
-- Zrzut danych tabeli `acl`
--

INSERT INTO `acl` (`id`, `resource`, `role`, `privilege`, `type`, `description`) VALUES
(37, 1, 2, 'login', 'allow', 'Logowanie '),
(38, 1, 2, 'logout', 'allow', 'Wylogowanie'),
(39, 1, 2, 'index', 'allow', 'Stona główna'),
(40, 3, 2, 'index', 'allow', 'Zarzadzanie zamowieniami'),
(41, 3, 2, 'add', 'allow', NULL),
(42, 3, 2, 'view', 'allow', NULL),
(43, 3, 2, 'edit', 'allow', NULL),
(44, 3, 2, 'edit-own', 'allow', NULL),
(45, 3, 2, 'delete', 'allow', NULL),
(46, 3, 2, 'delete-own', 'allow', NULL),
(47, 3, 2, 'view-all', 'allow', NULL),
(48, 1, NULL, 'accountrequest', 'allow', 'Prosba o zalozenie konta');

--
-- Zrzut danych tabeli `language_domains`
--

INSERT INTO `language_domains` (`id`, `domain`, `lang`, `description`, `active`, `layout`) VALUES
(1, 'broker.local', 'en', 'Lokalna domena en', 1, 'layout'),
(2, 'broker.no.local', 'no', 'Lokalna domena no', 1, 'layout'),
(3, 'broker.en.local', 'en', 'Lokalna domena en 2', 1, 'layout'),
(4, 'aktivsec.no', 'en', 'Domena produkcyjna', 1, 'layout');

--
-- Zrzut danych tabeli `languages`
--

INSERT INTO `languages` (`id`, `language`, `shortcode`, `code`, `description`, `active`, `defaultlang`) VALUES
(1, 'NO', 'no', 'nb_NO', NULL, 1, 0),
(2, 'EN', 'en', 'en_GB', NULL, 1, 1);

--
-- Zrzut danych tabeli `resources`
--

INSERT INTO `resources` (`id`, `module`, `controller`, `description`) VALUES
(1, 'default', 'index', NULL),
(2, 'default', 'users', NULL),
(3, 'default', 'stock-orders', NULL),
(4, 'default', 'account-requests', NULL);

--
-- Zrzut danych tabeli `roles`
--

INSERT INTO `roles` (`id`, `name`, `parent`, `blocked`) VALUES
(1, 'Guest', NULL, 0),
(2, 'Broker', 1, 0),
(3, 'Administrator', NULL, 0);

--
-- Zrzut danych tabeli `routes`
--

INSERT INTO `routes` (`id`, `routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`) VALUES
(1, 'default-index-index', '/', 'static', 'default', 'index', 'index', '', '', '', 'en'),
(2, 'default-index-login', '/login', 'static', 'default', 'index', 'login', '', '', '', 'en'),
(3, 'default-index-logout', '/logout', 'static', 'default', 'index', 'logout', '', '', '', 'en'),
(4, 'default-users-index', '/users/*', '', 'default', 'users', 'index', '', '', '', 'en'),
(5, 'default-users-add', '/users/add/*', '', 'default', 'users', 'add', '', '', '', 'en'),
(6, 'default-users-edit', '/users/edit/*', '', 'default', 'users', 'edit', '', '', '', 'en'),
(7, 'default-users-delete', '/users/delete/*', '', 'default', 'users', 'delete', '', '', '', 'en'),
(8, 'default-users-changestate', '/users/changestate/*', '', 'default', 'users', 'changestate', '', '', '', 'en'),
(9, 'default-users-loginidentity', '/users/login-as/*', '', 'default', 'users', 'loginidentity', '', '', '', 'en'),
(92, 'default-stock-orders-index', '/stock-orders/*', '', 'default', 'stock-orders', 'index', '', '', '', 'en'),
(93, 'default-stock-orders-add', '/stock-orders/add', '', 'default', 'stock-orders', 'add', '', '', '', 'en'),
(94, 'default-stock-orders-edit', '/stock-orders/edit/*', '', 'default', 'stock-orders', 'edit', '', '', '', 'en'),
(95, 'default-stock-orders-delete', '/stock-orders/delete/*', '', 'default', 'stock-orders', 'delete', '', '', '', 'en'),
(96, 'default-stock-orders-view', '/stock-orders/view/*', '', 'default', 'stock-orders', 'view', '', '', '', 'en'),
(97, 'default-index-accountrequest', '/ask-for-account', 'static', 'default', 'index', 'accountrequest', '', '', '', 'en'),
(98, 'default-account-requests-index', '/user-account-requests/*', '', 'default', 'account-requests', 'index', '', '', '', 'en'),
(99, 'default-account-requests-delete', '/user-account-requests/delete/*', '', 'default', 'account-requests', 'delete', '', '', '', 'en'),
(101, 'default-stock-orders-rank', '/stock-orders/ranks/*', '', 'default', 'stock-orders', 'rank', '', '', '', 'en'),
(102, 'default-stock-orders-export', '/stock-orders-export/', '', 'default', 'stock-orders', 'export', '', '', '', 'en');

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `active`, `created`, `role`, `name`, `parent`) VALUES
(13, 'admin', 'marcin.wyrozumski@joinnet.pl', 'efe6398127928f1b2e9ef3207fb82663', 1, '2013-03-23 11:23:24', 3, 'Super Administrator', NULL),
(14, 'broker1', 'broker@wp.pl', 'efe6398127928f1b2e9ef3207fb82663', 1, '2013-03-24 12:09:28', 2, 'Broker 1', NULL),
(15, 'rozumek', 'rozumek2000@wp.pl', 'efe6398127928f1b2e9ef3207fb82663', 1, '2013-03-26 18:16:17', 2, 'Kuba Wasiak', NULL),
(16, 'broker2', 'broker2@wp.pl', 'efe6398127928f1b2e9ef3207fb82663', 1, '2013-03-27 19:07:01', 2, 'Broker 2', NULL);

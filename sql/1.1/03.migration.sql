/**
 * Migration script for version 1.1
 */

-- create new table
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fee` float NOT NULL DEFAULT '0.3',
  `broker` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- copying clients from stock_orders to  clients table
insert into clients(name, email, broker) select distinct customer, 'no-email@email.com', broker from stock_orders;

-- changing customer name to id
update stock_orders s set customer = (select id from clients c where c.name = customer and c.broker = s.broker);

-- changing customer column type from varchar to int
alter table stock_orders modify customer int;

-- add routes
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`) VALUES
('default-clients-index', '/clients/*', ' ', 'default', 'clients', 'index', '', '', '', 'en'),
('default-clients-add', '/clients/add/*', '', 'default', 'clients', 'add', '', '', '', 'en'),
('default-clients-edit', '/clients/edit/*', '', 'default', 'clients', 'edit', '', '', '', 'en'),
('default-clients-delete', '/clients/delete/*', '', 'default', 'clients', 'delete', '', '', '', 'en'),
('default-index-changelog', '/changelog', '', 'default', 'index', 'changelog', '', '', '', 'en');

-- add resource
INSERT INTO `resources` (`module`, `controller`, `description`) VALUES
('default', 'clients', NULL);

-- aad acls
INSERT INTO `acl` (`resource`, `role`, `privilege`, `type`, `description`) VALUES
(5, 2, 'index', 'allow', 'Lista klientów'),
(5, 2, 'add', 'allow', 'Dodawanie klientów'),
(5, 2, 'edit', 'allow', 'Edycja klientów'),
(1, 2, 'changelog', 'allow', 'Log zmian');

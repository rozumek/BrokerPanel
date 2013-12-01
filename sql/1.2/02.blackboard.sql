-- blackboard table
CREATE  TABLE `blackboard` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `title` VARCHAR(255) NOT NULL ,
    `text` TEXT NOT NULL ,
    `active` TINYINT NOT NULL DEFAULT 0 ,
    `date_from` TIMESTAMP NULL ,
    `date_to` TIMESTAMP NULL ,
    `ordering` INT NOT NULL DEFAULT 0 ,
    PRIMARY KEY (`id`)
);

-- resource blackboard
INSERT INTO `resources` (`module`, `controller`)
VALUES ('default', 'admin');
INSERT INTO `resources` (`module`, `controller`)
VALUES ('default', 'blackboard');

-- new routes for blackboard
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`)
VALUES ('default-admin-index', '/admin', 'static', 'default', 'admin', 'index', '', '', '', 'en');
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`)
VALUES ('default-blackboard-index', '/admin/blackboard/*', '', 'default', 'blackboard', 'index', '', '', '', 'en');
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`)
VALUES ('default-blackboard-add', '/admin/blackboard/add/*', '', 'default', 'blackboard', 'add', '', '', '', 'en');
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`)
VALUES ('default-blackboard-edit', '/admin/blackboard/edit/*', '', 'default', 'blackboard', 'edit', '', '', '', 'en');
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`)
VALUES ('default-blackboard-delete', '/admin/blackboard/delete/*', '', 'default', 'blackboard', 'delete', '', '', '', 'en');
INSERT INTO `routes` (`routename`, `route`, `type`, `module`, `controller`, `action`, `params`, `regexp`, `reverse`, `language`)
VALUES ('default-blackboard-changestate', '/admin/blackboard/changestate/*', '', 'default', 'blackboard', 'changestate', '', '', '', 'en');


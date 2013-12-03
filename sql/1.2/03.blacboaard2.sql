-- new fields
ALTER TABLE `blackboard`
    ADD COLUMN `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `ordering` ,
    ADD COLUMN `broker` INT NOT NULL  AFTER `created` ;

-- acl
INSERT INTO `acl` (`resource`, `role`, `privilege`, `type`, `description`) VALUES ('6', '2', 'index', 'allow', 'Dostep do tablicy');
INSERT INTO `acl` (`resource`, `role`, `privilege`, `type`, `description`) VALUES ('6', '2', 'add', 'allow', 'Dodawanie do tablicy');
INSERT INTO `acl` (`resource`, `role`, `privilege`, `type`, `description`) VALUES ('6', '2', 'edit', 'allow', 'Edytowanie w tablicy');
INSERT INTO `acl` (`resource`, `role`, `privilege`, `type`, `description`) VALUES ('7', '2', 'index', 'allow', 'Dostep dla panelu admina');

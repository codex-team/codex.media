ALTER TABLE `pages` ADD `type` TINYINT(1) NOT NULL DEFAULT '1' AFTER `is_event`;
UPDATE `pages` SET `type` = '5' WHERE `is_event` = '1';
UPDATE `pages` SET `type` = '4' WHERE `is_community` = '1';
ALTER TABLE `pages` DROP `is_event`;
ALTER TABLE `pages` DROP `is_community`;

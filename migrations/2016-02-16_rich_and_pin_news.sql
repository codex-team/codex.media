ALTER TABLE `pages` ADD COLUMN `rich_view` tinyint(1) default '0' AFTER `is_menu_item`;

ALTER TABLE `pages` ADD COLUMN `dt_pin` timestamp NULL DEFAULT NULL AFTER `rich_view`;
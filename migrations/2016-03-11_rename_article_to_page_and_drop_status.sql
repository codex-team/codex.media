ALTER TABLE `comments` CHANGE COLUMN `article_id` `page_id` INT(10) NOT NULL;

ALTER TABLE `comments` DROP COLUMN `status`;
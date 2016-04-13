ALTER TABLE `comments` CHANGE COLUMN `author` `user_id` INT(10) NOT NULL;

ALTER TABLE `comments` DROP COLUMN `status`;
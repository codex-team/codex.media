CREATE TABLE `page_options` (
  `page_id` INT NOT NULL ,
  `type` TINYINT(1) NOT NULL DEFAULT '1',
  `key` TEXT NOT NULL ,
  `value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
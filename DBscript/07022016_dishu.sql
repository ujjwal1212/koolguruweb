ALTER TABLE `package` ADD `relevant_for` VARCHAR(1000) NOT NULL AFTER `image_path`, ADD `advantage` VARCHAR(1000) NOT NULL AFTER `relevant_for`, ADD `ff_classroom` INT(10) NOT NULL DEFAULT '0' AFTER `advantage`;
ALTER TABLE `package` ADD `code` VARCHAR(100) NOT NULL AFTER `id`;
ALTER TABLE `package` CHANGE `name` `title` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
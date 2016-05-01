ALTER TABLE `course` ADD `isdemo` TINYINT(10) NOT NULL AFTER `description`;


ALTER TABLE `package` CHANGE `description` `relevantfor` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `package` ADD `advantage` TEXT NOT NULL AFTER `relevantfor`;

ALTER TABLE `package` ADD `whatuserget` TEXT NOT NULL AFTER `advantage`;

ALTER TABLE `package` ADD `f2f_class` INT(10) NOT NULL AFTER `whatuserget`;
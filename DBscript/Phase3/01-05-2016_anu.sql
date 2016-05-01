ALTER TABLE `questions_options` CHANGE `description` `description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `chapters` ADD `chapter_type` BIGINT(10) NOT NULL AFTER `status`;
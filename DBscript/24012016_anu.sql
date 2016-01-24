--
-- Table structure for table `chapters`
--

CREATE TABLE IF NOT EXISTS `chapters` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subject_id` bigint(10) NOT NULL,
  `code` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `created_at` int(100) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(100) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  `status` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `subjects` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `created_at` int(100) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(100) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  `status` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subject_id` bigint(10) NOT NULL,
  `chapter_id` bigint(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `quiz` ADD `created_at` INT(100) NOT NULL AFTER `title`, ADD `created_by` BIGINT(10) NOT NULL AFTER `created_at`, ADD `updated_at` INT(100) NOT NULL AFTER `created_by`, ADD `updated_by` BIGINT(10) NOT NULL AFTER `updated_at`, ADD `status` TINYINT(10) NOT NULL AFTER `updated_by`;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_level`
--

CREATE TABLE IF NOT EXISTS `quiz_level` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(10) NOT NULL,
  `level_id` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `test`.`chapters` (`id`, `subject_id`, `code`, `title`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES (NULL, '1', 'DMO', 'Demo Chapter', '1453610361', '2', '1453610361', '2', '1')

INSERT INTO `test`.`subjects` (`id`, `code`, `title`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES (NULL, 'KGSUB001', 'Qudratic Equations', '1453610361', '2', '1453610361', '2', '1');


INSERT INTO `test`.`quiz` (`id`, `subject_id`, `chapter_id`, `title`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES (NULL, '1', '1', 'KGQUIZD001', '1453610361', '2', '1453610361', '2', '1');

INSERT INTO `test`.`quiz_level` (`id`, `quiz_id`, `level_id`) VALUES (NULL, '1', '3'), (NULL, '1', '2');

ALTER TABLE `chapters` ADD `isdemo` TINYINT(10) NOT NULL AFTER `title`;

UPDATE `test`.`chapters` SET `isdemo` = '1' WHERE `chapters`.`id` = 1;

ALTER TABLE `chapters` ADD `content` TEXT NOT NULL AFTER `title`;

ALTER TABLE `subjects` ADD `isdemo` TINYINT(10) NOT NULL AFTER `title`;

UPDATE `test`.`subjects` SET `isdemo` = '1' WHERE `subjects`.`id` = 1;



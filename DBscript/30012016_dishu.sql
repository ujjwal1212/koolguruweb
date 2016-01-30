-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_date` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_level`
--

CREATE TABLE IF NOT EXISTS `quiz_level` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(10) NOT NULL,
  `level_id` bigint(10) NOT NULL,
  `category_id` bigint(10) NOT NULL,
  `ques_nos` int(10) NOT NULL DEFAULT '30',
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `quiz_level`
--

INSERT INTO `quiz_level` (`id`, `quiz_id`, `level_id`, `category_id`, `ques_nos`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 3, 0, 30, 0, 0, 0, 0),
(2, 1, 2, 0, 30, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subject_id` bigint(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `code` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `pass_percentage` varchar(100) NOT NULL,
  `start_time` int(10) DEFAULT NULL,
  `end_time` int(10) DEFAULT NULL,
  `is_demo` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  `chapter_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`,`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `subject_id`, `title`, `status`, `code`, `description`, `pass_percentage`, `start_time`, `end_time`, `is_demo`, `created_at`, `created_by`, `updated_at`, `updated_by`, `chapter_id`) VALUES
(1, 1, 'KGQUIZD001', 1, '', NULL, '', NULL, NULL, 0, 1453610361, 2, 1453610361, 2, 1);

ALTER TABLE `chapters` ADD UNIQUE(`title`);

ALTER TABLE `chapters` ADD UNIQUE(`code`);

-- --------------------------------------------------------

--
-- Table structure for table `chapter_quiz_map`
--

CREATE TABLE IF NOT EXISTS `chapter_quiz_map` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint(10) NOT NULL,
  `quiz_id` bigint(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `subjects` ADD `image_path` VARCHAR(100) NULL AFTER `isdemo`;

-- --------------------------------------------------------

--
-- Table structure for table `subject_chapter_map`
--

CREATE TABLE IF NOT EXISTS `subject_chapter_map` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subject_id` bigint(10) NOT NULL,
  `chapter_id` bigint(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `image_path` varchar(100) DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `course_subject_map`
--

CREATE TABLE IF NOT EXISTS `course_subject_map` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(10) NOT NULL,
  `subject_id` bigint(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE IF NOT EXISTS `package` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `price` varchar(100) NOT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_course_map`
--

CREATE TABLE IF NOT EXISTS `package_course_map` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `package_id` bigint(10) NOT NULL,
  `course_id` bigint(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


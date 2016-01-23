
--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `min_marks` float NOT NULL,
  `max_marks` float NOT NULL,
  `level` bigint(10) NOT NULL,
  `type` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint(10) DEFAULT NULL,
  `created_date` int(15) DEFAULT NULL,
  `updated_by` bigint(10) DEFAULT NULL,
  `updated_date` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `name`, `description`, `min_marks`, `max_marks`, `level`, `type`, `status`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(10, 'Basic Question', 'Basic Questions desc', 0, 0, 0, 0, 0, 1, 1451271663, 1, 1451271663),
(11, 'check question 2', 'check question 2 desc', 2, 5, 1, 0, 1, 1, 1451271969, 1, 1451273250);



--
-- Table structure for table `questions_options`
--

CREATE TABLE IF NOT EXISTS `questions_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(500) NOT NULL,
  `questions_id` bigint(10) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `created_date` int(15) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  `updated_date` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `questions_options`
--

INSERT INTO `questions_options` (`id`, `description`, `questions_id`, `is_correct`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(4, 'right option', 10, 1, 1, 1451271663, 1, 1451271663),
(5, 'wrong option', 10, 0, 1, 1451271663, 1, 1451271663),
(16, 'Right answer', 11, 1, 1, 1451273250, 1, 1451273250),
(17, 'another option', 11, 0, 1, 1451273250, 1, 1451273250);


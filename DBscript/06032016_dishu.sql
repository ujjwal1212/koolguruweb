--
-- Table structure for table `package`
--

CREATE TABLE IF NOT EXISTS `package` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `title` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `price` varchar(100) NOT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `relevant_for` varchar(1000) NOT NULL,
  `advantage` varchar(1000) NOT NULL,
  `ff_classroom` varchar(100) NOT NULL DEFAULT '0',
  `whatuserget` varchar(2000) NOT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `code`, `description`, `title`, `status`, `price`, `duration`, `image_path`, `relevant_for`, `advantage`, `ff_classroom`, `whatuserget`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(7, 'KGPKG-000-1', 'System Package', 'The long term planner', 1, '12.5k', '15 to 22 Months', NULL, 'You have more than a year to finish your graduation or like your job enough to stay there for another 12 months', 'Start early,Learn fundamentals, solve problems & revisit the concepts again.,Learn at a comfortable pace.', '1 per Month', '1. Access to detailed course contents,\r\n2. 1 Face-to-Face class per month with our expert faculty,\r\n3. Mock tests', 1457240573, 1, 1457240573, 1),
(8, 'KGPKG-000-8', 'System Package', 'The go-getter', 1, '10k', '8 to 10 Months', NULL, 'You are in your final year and cant wait to do MBA or you hate your work/boss and want to be in your dream B school at the earliest\r\n', 'Learn fundamentals, solve problems & revisit the concepts again., You need to move quickly though\r\n', '1 per Month', '1. Access to detailed course contents,\r\n2. 1 Face-to-Face class per month with our expert faculty,\r\n3. Mock tests', 1457240775, 1, 1457240775, 1),
(9, 'KGPKG-000-9', 'System Package', 'The go-getter (intensive)', 1, '12.5k', '8 to 10 Months', NULL, 'You are in your final year and REALLY cant wait to do MBA or you REALLY hate your work/boss and want to be in your dream B school at the earliest\r\n', 'Learn fundamentals, solve problems & revisit the concepts again., Get intensive coaching with 2 face-to-face classes month to put you on fast track\r\n', '2 Per month', '1. Access to detailed course contents,\r\n2. 2 Face-to-Face class per month with our expert faculty,\r\n3. Mock tests', 1457240939, 1, 1457240939, 1),
(10, 'KGPKG-000-10', 'System Package', 'Fine-tuning', 1, '5K', 'NA', NULL, 'Solve problems and get solutions., Know your strengths and weaknesses., Build up your confidence and fine tune your preparation\r\n', 'You know your stuff and need to test yourself and fine tune things a bit. \r\n', '3-5 Months', '1. Mock tests\r\n2. Preparation Material', 1457241045, 1, 1457241045, 1);


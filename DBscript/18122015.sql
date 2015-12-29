--
-- Table structure for table `recover`
--
CREATE TABLE IF NOT EXISTS `activation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requested_on` varchar(240) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Table structure for table `recover`
--

CREATE TABLE IF NOT EXISTS `recover` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requested_on` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `national_id` varchar(100) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `country` int(10) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `age` int(10) NOT NULL,
  `created_date` int(10) DEFAULT NULL,
  `updated_date` int(10) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `updated_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `national_id`, `fname`, `lname`, `email`, `password`, `status`, `country`, `gender`, `age`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, '1234', 'Dushyant', 'Sharma', 'er.dushyant.30@gmail.com', '6a58ab0fabc3c7ffd7f1ebbde3375e8d70fb59e8', 1, 0, 'M', 25, 1450405750, NULL, NULL, NULL);

UPDATE `test`.`users` SET `status` = '1' WHERE `users`.`user_id` = 1;
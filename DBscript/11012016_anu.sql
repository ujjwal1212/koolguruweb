CREATE TABLE IF NOT EXISTS `student_mobile` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(100) NOT NULL,
  `isregistered` tinyint(10) NOT NULL,
  `student_id` bigint(10) NOT NULL,
  `created` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

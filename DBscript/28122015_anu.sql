--
-- Table structure for table `student_status`
--

CREATE TABLE IF NOT EXISTS `student_status` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(10) NOT NULL,
  `registration_status` tinyint(10) NOT NULL,
  `verbal_reg_status` tinyint(10) NOT NULL,
  `marks_obtain_verbal` int(100) NOT NULL,
  `verbal_reg_created` int(100) NOT NULL,
  `quant_status` tinyint(10) NOT NULL,
  `marks_obtain_quant` int(100) NOT NULL,
  `quant_reg_created` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


--
-- Dumping data for table `student_status`
--

INSERT INTO `student_status` (`id`, `student_id`, `registration_status`, `verbal_reg_status`, `marks_obtain_verbal`, `verbal_reg_created`, `quant_status`, `marks_obtain_quant`, `quant_reg_created`) VALUES
(1, 2, 1, 0, 0, 0, 0, 0, 0);

--
-- Table structure for table `student_reg_verbal_ability`
--

CREATE TABLE IF NOT EXISTS `student_reg_verbal_ability` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(10) NOT NULL,
  `question_id` bigint(10) NOT NULL,
  `max_marks` int(11) NOT NULL,
  `marks_obtain` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `option_selected` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
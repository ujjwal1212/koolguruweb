--
-- Table structure for table `degree`
--

CREATE TABLE IF NOT EXISTS `degree` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `degree_name` varchar(50) NOT NULL,
  `status` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'MTech', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'BTech', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'MBA', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'MCA', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Bsc', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Msc', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Phd', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Intermediate', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'HighScholl', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Bcom', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Mcon', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Diploma', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Masters', '1');
INSERT INTO `test`.`degree` (`id`, `degree_name`, `status`) VALUES (NULL, 'Gradutae', '1');



-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `test`.`state` (`id`, `state_name`) VALUES (NULL, 'Uttar Pradesh'), (NULL, 'Delhi');
INSERT INTO `test`.`state` (`id`, `state_name`) VALUES (NULL, 'Andhra Pradesh'), (NULL, 'Bihar');
INSERT INTO `test`.`state` (`id`, `state_name`) VALUES (NULL, 'Himachal Pradesh'), (NULL, 'Haryana');
INSERT INTO `test`.`state` (`id`, `state_name`) VALUES (NULL, 'Arunachal Pradesh'), (NULL, 'Maharshtra');
INSERT INTO `test`.`state` (`id`, `state_name`) VALUES (NULL, 'Assam'), (NULL, 'Punjab');
INSERT INTO `test`.`state` (`id`, `state_name`) VALUES (NULL, 'Madhya Pradesh'), (NULL, 'Karnatka');


-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `created` int(50) NOT NULL,
  `updated` int(50) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `sex` tinyint(10) NOT NULL,
  `father_occupation` varchar(100) NOT NULL,
  `highest_degree` int(50) NOT NULL,
  `completion_year` int(50) NOT NULL,
  `native_state` int(50) NOT NULL,
  `city` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--------------------------------------------------------------------

ALTER TABLE `student` ADD `email` VARCHAR(100) NOT NULL AFTER `mobile`;

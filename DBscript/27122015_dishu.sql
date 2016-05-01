
--
-- Table structure for table `level`
--

CREATE TABLE IF NOT EXISTS `level` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `created_date` int(15) NOT NULL,
  `created_by` int(15) NOT NULL,
  `updated_date` int(15) NOT NULL,
  `updated_by` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id`, `name`, `description`, `created_date`, `created_by`, `updated_date`, `updated_by`) VALUES
(1, 'Level 2', 'this is first level edit', 1450754329, 1, 1451199237, 1);

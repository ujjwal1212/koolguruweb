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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

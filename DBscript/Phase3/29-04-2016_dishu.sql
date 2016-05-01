--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(10) NOT NULL,
  `package_id` bigint(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `subscription`
--

CREATE TABLE IF NOT EXISTS `subscription` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  `package_id` bigint(10) NOT NULL,
  `is_expired` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` int(10) NOT NULL,
  `end_date` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


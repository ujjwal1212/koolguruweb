
CREATE TABLE IF NOT EXISTS `blog_likes` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(10) NOT NULL,
  `is_student` tinyint(10) NOT NULL,
  `status` tinyint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  `created_at` int(100) NOT NULL,
  `updated_at` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


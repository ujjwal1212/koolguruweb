CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL,
  `post` text NOT NULL,
  `is_student` tinyint(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `created_at` int(100) NOT NULL,
  `updated_by` bigint(10) NOT NULL,
  `updated_at` int(100) NOT NULL,
  `like_count` int(100) NOT NULL,
  `reply_count` int(100) NOT NULL,
  `post_id` bigint(10) NOT NULL,
  `status` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `blog_post`
--

INSERT INTO `blog_post` (`id`, `title`, `post`, `is_student`, `created_by`, `created_at`, `updated_by`, `updated_at`, `like_count`, `reply_count`, `post_id`, `status`) VALUES
(1, 'What is Koolguru', 'Chis cialis commercial actors ; plavix side effects of stopping ; clomid instructions ; clomidgeneric-online ; plavix half life Coyier of CSS Tricks gave a talk at the recent', 1, 2, 1459360304, 2, 1459360304, 0, 0, 0, 0),
(2, 'asdasdasdasdasdasdasdas', 'I am the don', 1, 2, 1459360628, 2, 1459360628, 0, 0, 0, 0),
(3, 'Test', '1111111111111111111\r\n11111111111111\r\n11111111111111111', 1, 2, 1459360703, 2, 1459360703, 0, 0, 0, 0),
(4, 'a', 'hh', 1, 2, 1459360767, 2, 1459360767, 0, 0, 0, 0),
(5, 'My test Blog1', 'My test Blog1', 1, 2, 1459576819, 2, 1459576819, 0, 0, 0, 0),
(6, 'My test Blog2', 'My test Blog2', 1, 2, 1459576827, 2, 1459576827, 0, 0, 0, 0),
(7, 'My test Blog3', 'My test Blog3', 1, 2, 1459576834, 2, 1459576834, 0, 0, 0, 0),
(8, 'My test Blog4', 'My test Blog4', 1, 2, 1459576840, 2, 1459576840, 0, 0, 0, 0),
(9, 'My test Blog5', 'My test Blog5', 1, 2, 1459576846, 2, 1459576846, 0, 0, 0, 0),
(10, 'My test Blog6', 'My test Blog6', 1, 2, 1459576853, 2, 1459576853, 0, 0, 0, 0),
(11, 'My test Blog7', 'My test Blog7', 1, 2, 1459576860, 2, 1459576860, 0, 0, 0, 0),
(12, 'My test Blog8', 'My test Blog8', 1, 2, 1459576868, 2, 1459576868, 0, 0, 0, 0);
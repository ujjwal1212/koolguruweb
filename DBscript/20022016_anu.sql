--
-- Table structure for table `testimonial`
--

CREATE TABLE IF NOT EXISTS `testimonial` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `short_description` text NOT NULL,
  `description` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` tinyint(10) NOT NULL,
  `created_at` int(100) NOT NULL,
  `created_by` int(100) NOT NULL,
  `updated_at` int(100) NOT NULL,
  `updated_by` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `testimonial`
--

INSERT INTO `testimonial` (`id`, `name`, `degree`, `short_description`, `description`, `image`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Natarajan Ramachandran', 'IIM Bangalore – 2016', 'With more than 3 years of work experience in the Information Technology industry, I was determined to pursue an MBA, more specifically from an Indian Institute of Management. Two failed attempts did not deter me from this dream.', 'With more than 3 years of work experience in the Information Technology industry, I was determined to pursue an MBA, more specifically from an Indian Institute of Management. Two failed attempts did not deter me from this dream. However I realized that aptitude tests such as the CAT have evolved from being regular pattern based to becoming more concept based. These tests no longer have all those tough problems which test the need for a candidate to have solved a similar problem in the past. All problems are based on fundamental concepts and test the ability of the candidate to understand the grass-roots of the same. It was then, I met Ankur Sir and Vatan Sir. I found their teaching "unconventional" and "fantastic". I found a distinct difference in their way of teaching when compared to the established coaching enterprises. Not only were the fundamentals made very clear, but also all concepts were linked and interpreted in a new way. For instance, I was able to solve questions from algebra, by visualizing them as a geometric figure. All these ways, helped me to gain a significant edge over my peer in competitive exams (CAT, XAT, IIFT, NMAT, SNAP) Thanks to them, I cracked the CAT and joined IIM B in 2014. Never had I been so happy. I attribute my success to them and strongly urge all aspirants of CAT/Any competitive test to try out the training programs designed by them and their team.', 'natarajan.jpg', 1, 1455345995, 2, 1455345995, 2),
(2, 'Renu Chowdary', 'IIM Trichy - 2016', 'Ankur Sir and his team have been a set of brilliant mentors to help me prepare for my MBA.', 'Ankur Sir and his team have been a set of brilliant mentors to help me prepare for my MBA. They have helped me with preparation materials, guided me along every step. They are a team of dedicated, enthusiastic individuals who always go an extra mile to help students achieve their dreams. I would recommend them if you are looking for a team that''s passionate about your dream.', 'renu.jpg', 1, 1455345995, 2, 1455345995, 2),
(3, 'Ritika Agarwal', 'XIMB - 2015', 'Preparing for CAT and other B- school entrance exams was less of studies and more of fun for me. During my final year of B.Tech, attending coaching classes and solving Maths questions was a stress-buster for me.', 'Preparing for CAT and other B- school entrance exams was less of studies and more of fun for me. During my final year of B.Tech, attending coaching classes and solving Maths questions was a stress-buster for me. The credit for making Quantitative Ability, Logical Reasoning and Data Interpretation so engrossing goes to Ankur Sir and his team. Not just this, I got tremendous confidence by solving the mock test papers. Getting geared up for GD and PI, all were enabled by the rich guidance and support that I got here. Selecting which B-School to apply, how to prepare for its written and GD-PI, and many more implicit knowledge and insights were given to me. Put in the hard work and the mentors will ensure that your hard work is paid off. All the Best', 'ritika.jpg', 1, 1455345995, 2, 1455345995, 2),
(4, 'SrinivasRadhakrishnan', 'SCMHRD – 2016', 'Exactly a year ago, here I was frantically preparing for my GD’s and PI’s to get through a dream B-School that would open doors for a world of opportunities.', 'Exactly a year ago, here I was frantically preparing for my GD’s and PI’s to get through a dream B-School that would open doors for a world of opportunities. But reaching this stage would not have been possible without the guidance and attention given to me by Vatan Sir, Ankur Sir and team. Classes and materials provided by them molded me to face any googly thrown at me during CAT/SNAP and the ensuing GD’s/PI’s. I would like to offer my gratitude to Vatan Sir and Ankur Sir who were instrumental in my growth and were available whenever I needed them. They made me feel confident of cracking the exams through series of Mock tests, Interviews, GD’s and made sure that I was ready to win the battle. Behind every successful B-School student is a Mentor, a Guru.', 'srinivas.jpg', 1, 1455345995, 2, 1455345995, 2);

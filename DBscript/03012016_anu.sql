CREATE TABLE IF NOT EXISTS `student_reg_quant_ability` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(10) NOT NULL,
  `question_id` bigint(10) NOT NULL,
  `max_marks` int(100) NOT NULL,
  `marks_obtain` int(100) NOT NULL,
  `created` int(100) NOT NULL,
  `option_selected` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;


INSERT INTO `level` (`id`, `name`, `description`, `created_date`, `created_by`, `updated_date`, `updated_by`) VALUES
(1, 'Level 2', 'Verbal Ability', 1450754329, 1, 1451816686, 1),
(2, 'Level 3', '', 1451722260, 2, 1451722260, 2),
(3, 'Level 6', 'Quantitative Questions', 1451816668, 2, 1451816668, 2);

INSERT INTO `questions` (`id`, `name`, `description`, `min_marks`, `max_marks`, `level`, `type`, `status`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(10, 'Where United Nations Exist ?', 'Where United Nations Exist ?', 0, 1, 1, 0, 1, 1, 1451271663, 1, 1451723991),
(11, 'Which city is the capital of Uttar Pradesh ?', 'Which city is the capital of Uttar Pradesh ?', 0, 1, 1, 0, 1, 1, 1451271969, 1, 1451723820),
(12, '(2+5)^2 = ?', '(2+5)^2 = ?', 0, 1, 3, 0, 1, 2, 1451817062, 2, 1451817062),
(13, 'If one Mango price is 4 Rs then how much the price of 10 Mango ?', 'If one Mango price is Rs 4 then how much the price of 10 Mango ?', 0, 1, 3, 0, 1, 2, 1451817172, 2, 1451817172);


INSERT INTO `questions_options` (`id`, `description`, `questions_id`, `is_correct`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(27, 'Lucknow', 11, 1, 1, 1451723820, 1, 1451723820),
(28, 'Allahabad', 11, 0, 1, 1451723820, 1, 1451723820),
(29, 'Noida', 11, 0, 1, 1451723820, 1, 1451723820),
(30, 'Kanpur', 11, 0, 1, 1451723820, 1, 1451723820),
(33, 'USA', 10, 1, 1, 1451723991, 1, 1451723991),
(34, 'Japan', 10, 0, 1, 1451723991, 1, 1451723991),
(35, 'India', 10, 0, 1, 1451723991, 1, 1451723991),
(36, 'Brazil', 10, 0, 1, 1451723991, 1, 1451723991),
(37, '36', 12, 0, 2, 1451817062, 2, 1451817062),
(38, '49', 12, 1, 2, 1451817062, 2, 1451817062),
(39, '81', 12, 0, 2, 1451817062, 2, 1451817062),
(40, '144', 12, 0, 2, 1451817062, 2, 1451817062),
(41, '18', 13, 0, 2, 1451817172, 2, 1451817172),
(42, '20', 13, 0, 2, 1451817172, 2, 1451817172),
(43, '36', 13, 0, 2, 1451817172, 2, 1451817172),
(44, '40', 13, 1, 2, 1451817172, 2, 1451817172);
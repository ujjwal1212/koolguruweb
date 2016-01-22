ALTER TABLE `student_status` ADD `marks_total_verbal` INT(100) NOT NULL AFTER `quant_reg_created`, ADD `verbal_perc` FLOAT NOT NULL AFTER `marks_total_verbal`, ADD `marks_total_quant` INT(100) NOT NULL AFTER `verbal_perc`, ADD `quant_perc` FLOAT NOT NULL AFTER `marks_total_quant`;


---------------------------------

CREATE TABLE IF NOT EXISTS `carrier_path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `min_verbal_perc` int(11) NOT NULL,
  `max_verbal_perc` int(11) NOT NULL,
  `min_quant_perc` int(11) NOT NULL,
  `max_quant_perc` int(11) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_by` bigint(20) NOT NULL,
  `updated_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


INSERT INTO `carrier_path` (`id`, `name`, `min_verbal_perc`, `max_verbal_perc`, `min_quant_perc`, `max_quant_perc`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(1, 'MBA', 30, 60, 40, 70, 2, 1453448660, 2, 1453448660),
(2, 'MCA', 20, 40, 50, 70, 2, 1453448758, 2, 1453448758);



--------------------------

INSERT INTO `level` (`id`, `name`, `description`, `created_date`, `created_by`, `updated_date`, `updated_by`) VALUES
(1, '3', 'Difficulty Level 3', 1450754329, 1, 1453451330, 1),
(2, '2', 'Difficulty Level 2', 1451722260, 2, 1453451313, 2),
(3, '1', 'Difficulty Level 1', 1451816668, 2, 1453451297, 2),
(4, '4', 'Difficulty Level 4', 1453451346, 2, 1453451346, 2),
(5, '5', 'Difficulty Level 5', 1453451366, 2, 1453451366, 2),
(6, '6', 'Difficulty Level 6', 1453451386, 2, 1453451386, 2);



--------------------


INSERT INTO `questions` (`id`, `name`, `description`, `min_marks`, `max_marks`, `level`, `type`, `status`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(10, 'Correct the sentence from the options - He considers me as a fool.', 'Correct the sentence from the options - He considers me as a fool.', 0, 1, 3, 0, 1, 1, 1451271663, 1, 1453454766),
(11, 'From the four choices provided, choose the analogy that is most similar to  wealth : poverty', 'From the four choices provided, choose the analogy that is most similar to  wealth : poverty', 0, 1, 3, 0, 1, 1, 1451271969, 1, 1453454672),
(12, 'The sharp cracking of a twig mixed with a bird’s ___ made a great impact on him.', 'The sharp cracking of a twig mixed with a bird’s ___ made a great impact on him.', 0, 1, 3, 0, 1, 2, 1451817062, 2, 1453454533),
(13, 'Choose the word that is similar in meaning to the word SOLICITOUS', 'Choose the word that is similar in meaning to the word SOLICITOUS', 0, 1, 3, 0, 1, 2, 1451817172, 2, 1453454388),
(14, 'Form coherent passage from jumbled sentences: A.      It is turning off the tap. B.      And with no consensus of the exit policy, the government is damned if it supports loss-making units and damned ', 'Form coherent passage from jumbled sentences:\r\nA.      It is turning off the tap.\r\nB.      And with no consensus of the exit policy, the government is damned if it supports loss-making units and damned if it doesn’t.\r\nC.      The private sector did the same in the past because securing legal sanction for closure was virtually impossible.\r\nD.      After years of funding the losses of public sector companies, the government is doing the unthinkable.', 0, 1, 3, 0, 1, 2, 1453454899, 2, 1453454899),
(15, 'If Akshay is the brother of the son of Sunil’s son, then how is Akshay related to Sunil?', ' If Akshay is the brother of the son of Sunil’s son, then how is Akshay related to Sunil?', 0, 1, 2, 0, 1, 2, 1453458775, 2, 1453458891),
(16, 'A number x is increased by 10% and then reduced by 10 %. The resultant number is', 'A number x is increased by 10% and then reduced by 10 %. The resultant number is', 0, 1, 2, 0, 1, 2, 1453458874, 2, 1453458874),
(17, 'The sum of the ages of father and his son is 45 years. Five years ago, the product of their ages was 4 times the age of the father at that time. The present age of the father, is', 'The sum of the ages of father and his son is 45 years. Five years ago, the product of their ages was 4 times the age of the father at that time. The present age of the father, is', 0, 1, 2, 0, 1, 2, 1453458981, 2, 1453458981),
(18, 'Facing towards north, Mohit walked 20 m. He then turned to his left and walked 15 m. He again turned to his left and walked 20 m. Finally, he turned to his left and walked 35 m. How far is Mohit from', 'Facing towards north, Mohit walked 20 m. He then turned to his left and walked 15 m. He again turned to his left and walked 20 m. Finally, he turned to his left and walked 35 m. How far is Mohit from his starting point?', 0, 1, 2, 0, 1, 2, 1453459075, 2, 1453459293),
(19, 'Choose the number that different from remaining numbers.', 'Choose the number that different from remaining numbers.', 0, 1, 2, 0, 1, 2, 1453459143, 2, 1453459307);



---------------------------


INSERT INTO `questions_options` (`id`, `description`, `questions_id`, `is_correct`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(93, 'Nonchalant', 13, 0, 2, 1453454388, 2, 1453454388),
(94, 'Firm', 13, 0, 2, 1453454388, 2, 1453454388),
(95, 'Reverential', 13, 0, 2, 1453454388, 2, 1453454388),
(96, 'Worried', 13, 1, 2, 1453454388, 2, 1453454388),
(97, 'cry', 12, 0, 2, 1453454533, 2, 1453454533),
(98, 'shriek', 12, 0, 2, 1453454533, 2, 1453454533),
(99, 'squawk', 12, 1, 2, 1453454533, 2, 1453454533),
(100, 'scream', 12, 0, 2, 1453454533, 2, 1453454533),
(101, 'part : whole', 11, 0, 1, 1453454672, 1, 1453454672),
(102, 'good : excellent', 11, 0, 1, 1453454672, 1, 1453454672),
(103, 'prodigal : chary', 11, 1, 1, 1453454672, 1, 1453454672),
(104, 'wicked : sinful', 11, 0, 1, 1453454672, 1, 1453454672),
(105, 'I as a fool', 10, 0, 1, 1453454766, 1, 1453454766),
(106, 'me for a fool', 10, 0, 1, 1453454766, 1, 1453454766),
(107, 'I for a fool', 10, 0, 1, 1453454766, 1, 1453454766),
(108, 'me a fool', 10, 1, 1, 1453454767, 1, 1453454767),
(109, 'DACB', 14, 1, 2, 1453454899, 2, 1453454899),
(110, 'CADB', 14, 0, 2, 1453454899, 2, 1453454899),
(111, 'DCAB', 14, 0, 2, 1453454899, 2, 1453454899),
(112, 'BDAC', 14, 0, 2, 1453454899, 2, 1453454899),
(117, '1% more than x', 16, 0, 2, 1453458874, 2, 1453458874),
(118, '1% less than x', 16, 1, 2, 1453458874, 2, 1453458874),
(119, 'equal to x', 16, 0, 2, 1453458874, 2, 1453458874),
(120, 'None of these', 16, 0, 2, 1453458874, 2, 1453458874),
(121, 'Nephew', 15, 0, 2, 1453458891, 2, 1453458891),
(122, 'Brother', 15, 0, 2, 1453458891, 2, 1453458891),
(123, 'Cousin', 15, 0, 2, 1453458891, 2, 1453458891),
(124, 'Grandson', 15, 1, 2, 1453458891, 2, 1453458891),
(125, '30 years', 17, 0, 2, 1453458981, 2, 1453458981),
(126, '31 years', 17, 0, 2, 1453458981, 2, 1453458981),
(127, '36 years', 17, 1, 2, 1453458981, 2, 1453458981),
(128, '41 years', 17, 0, 2, 1453458981, 2, 1453458981),
(137, '20 m', 18, 1, 2, 1453459293, 2, 1453459293),
(138, '35 m', 18, 0, 2, 1453459293, 2, 1453459293),
(139, '75 m', 18, 0, 2, 1453459293, 2, 1453459293),
(140, '50 m', 18, 0, 2, 1453459294, 2, 1453459294),
(141, '289', 19, 0, 2, 1453459307, 2, 1453459307),
(142, '169', 19, 0, 2, 1453459307, 2, 1453459307),
(143, '81', 19, 1, 2, 1453459308, 2, 1453459308),
(144, '25', 19, 0, 2, 1453459308, 2, 1453459308);

-----------------------
CREATE TABLE IF NOT EXISTS `carrier_oriented_question` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `note` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


------------------

CREATE TABLE IF NOT EXISTS `carrier_oriented_question_options` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `question_id` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



---------------------

CREATE TABLE IF NOT EXISTS `carrier_oriented_message` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `question_id` int(100) NOT NULL,
  `option_id` int(100) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


----------------------

INSERT INTO `test`.`carrier_oriented_question` (`id`, `title`, `note`) VALUES (NULL, 'Which of these famous personality are you inspired by?', ''), (NULL, 'Which occupation is close to your heart?', '');
INSERT INTO `test`.`carrier_oriented_question` (`id`, `title`, `note`) VALUES (NULL, 'What does an entry level management job pay when u pass out of a premier B school?', ''), (NULL, 'Match the No of Test Takers with respective Competitive Exam?', '');
UPDATE `test`.`carrier_oriented_question` SET `note` = 'Note : Plz jumble the Options and use this question as Match the following Question' WHERE `carrier_oriented_question`.`id` = 4;
INSERT INTO `test`.`carrier_oriented_question` (`id`, `title`, `note`) VALUES (NULL, 'Are moral values getting lost in today’s society?', 'Essay type with limit of 120 Words'), (NULL, 'Select multiple, if applicable: Given a choice, what will do on a Saturday', '');


----------------------------
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '1', 'Bill Gates'), (NULL, '1', 'Mahatma Gandhi');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '1', 'Sachin Tendulkar'), (NULL, '1', 'Barack Obama');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '1', 'Amitabh Bachchan'), (NULL, '1', 'Sundar Pichai - Google CEO');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '2', 'Entrepreneur'), (NULL, '2', 'Artistic Job(Painter, Writer, SInger)');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '2', 'Corporate'), (NULL, '2', 'Engineer');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '2', 'Government');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '3', '3-5 Lacs'), (NULL, '3', '5-10 Lacs');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '3', '10-20 Lacs'), (NULL, '3', '20-50 Lacs');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '3', 'Above 50 Lacs');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '4', 'CAT - 2 lacs'), (NULL, '4', 'Civil - 4.5 lacs');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '4', 'Bank PO - 17 lacs'), (NULL, '4', 'IIT-JEE - 4 Lacs');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '6', 'Travel/Pub/Friends hangout'), (NULL, '6', 'Finish a book');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '6', 'Watch movie/sitcom/game'), (NULL, '6', 'Attend seminar about career option/talk to somebody/research');
INSERT INTO `test`.`carrier_oriented_question_options` (`id`, `question_id`, `title`) VALUES (NULL, '6', 'Prepare for an entrance exam'), (NULL, '6', 'Catch up on news/events');

---------------------------------------------

INSERT INTO `test`.`carrier_oriented_message` (`id`, `question_id`, `option_id`, `message`) VALUES (NULL, '1', '1', 'You are influenced by visionaries who think out of the Box which is a skill required by an established manager.'), (NULL, '1', '2', 'You believe in strong principles and moral values - a must have in a leader and good manager.');
INSERT INTO `test`.`carrier_oriented_message` (`id`, `question_id`, `option_id`, `message`) VALUES (NULL, '1', '3', 'High focus, Sincerity and Dedication is what drives you. Good qualities to have while preparing for competitive exams.'), (NULL, '1', '4', 'You have an aptitude for Power and as a Manager it is a good to have quality.');
INSERT INTO `test`.`carrier_oriented_message` (`id`, `question_id`, `option_id`, `message`) VALUES (NULL, '1', '5', 'You are/desire to be the centre of attraction of the group. You have a will to be the best in whatever you do and this attitude will help you crack the toughest of the exams.'), (NULL, '1', '6', 'To reach the top notch position is a desire which will not allow to settle for the second best. You will aim at the best and will achieve it.');








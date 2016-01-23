ALTER TABLE `student_status` ADD `carrier_status` TINYINT(10) NOT NULL AFTER `quant_perc`, ADD `carrier_reg_created` INT NOT NULL AFTER `carrier_status`;

-----------------------
UPDATE `test`.`carrier_oriented_question` SET `note` = 'Please jumble the Options and use this question as Match the following Question' WHERE `carrier_oriented_question`.`id` = 4;

----------------
ALTER TABLE `carrier_path` ADD `msg` VARCHAR(255) NOT NULL AFTER `name`;

--------------

UPDATE `test`.`carrier_path` SET `msg` = 'All 10 Correct : You have an aptitude for CAT and similar exams and with proper guidance and polishing you can make it to a top notch B school.' WHERE `carrier_path`.`id` = 1;

UPDATE `test`.`carrier_path` SET `msg` = 'Quants - 4 or 5 Correct (But less than 5 in Eng)- “You are good at Quantitative ability and have an aptitude for CAT and similar Quants Oriented Exams”' WHERE `carrier_path`.`id` = 2;

UPDATE `test`.`carrier_path` SET `msg` = 'Less than 4 correct - Either it''s a bad day to work on your quants skills or you need to work hard on this section. In both situations we can help you focus and build this section' WHERE `carrier_path`.`id` = 3;

UPDATE `test`.`carrier_path` SET `msg` = 'English- 3 or 4 or 5 Correct(But Less than 5 in Quants) - Your verbal abilities are at par and makes you competitive for B - Schools entrance exams' WHERE `carrier_path`.`id` = 4;

UPDATE `test`.`carrier_path` SET `msg` = 'Less than 3 Corrects-Either it''s a bad day to work on your verbal skills or you need to work hard on this section. In both situations we can help you focus and build this section.' WHERE `carrier_path`.`id` = 5;
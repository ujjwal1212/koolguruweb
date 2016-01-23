ALTER TABLE `student_status` ADD `carrier_status` TINYINT(10) NOT NULL AFTER `quant_perc`, ADD `carrier_reg_created` INT NOT NULL AFTER `carrier_status`;

-----------------------
UPDATE `test`.`carrier_oriented_question` SET `note` = 'Please jumble the Options and use this question as Match the following Question' WHERE `carrier_oriented_question`.`id` = 4;
ALTER TABLE `team` ADD `isvisible_on_front` TINYINT(10) NOT NULL AFTER `status`;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 1;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 2;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 3;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 4;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 5;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 6;

UPDATE `koolguru_test`.`team` SET `isvisible_on_front` = '1' WHERE `team`.`id` = 8;
--
-- Dumping data for table `permission_name`
--
INSERT INTO `test`.`permission` (`id`, `permission_name`, `resource_id`) VALUES (NULL, 'index', '7');

--
-- Dumping data for table `permission_name`
--

INSERT INTO `test`.`role_permission` (`id`, `role_id`, `permission_id`) VALUES (NULL, '1', '16');

INSERT INTO `test`.`permission` (`id`, `permission_name`, `resource_id`) VALUES (NULL, 'studentRegistration', '3');

INSERT INTO `test`.`role_permission` (`id`, `role_id`, `permission_id`) VALUES (NULL, '1', '17');

ALTER TABLE `student` ADD `isprofilecompleted` TINYINT(1) NOT NULL AFTER `status`;

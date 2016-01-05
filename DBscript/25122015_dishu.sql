--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `resource_name`) VALUES
(1, 'Application\\Controller\\Index'),
(2, 'ZF2AuthAcl\\Controller\\Index'),
(3, 'Student\\Contoller\\Student'),
(4, 'Admin\\Controller\\Admin'),
(5, 'Questionarie\\Controller\\Level'),
(6, 'User\\Controller\\User');


--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `permission_name`, `resource_id`) VALUES
(1, 'index', 1),
(2, 'locateus', 1),
(3, 'aboutus', 1),
(4, 'vision', 1),
(5, 'mission', 1),
(6, 'contactus', 1),
(7, 'index', 5),
(8, 'add', 5),
(9, 'edit', 5),
(10, 'delete', 5),
(11, 'view', 5),
(12, 'index', 3),
(13, 'index', 6),
(14, 'add', 6),
(15, 'edit', 6);


--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`) VALUES (NULL, '1', '1');
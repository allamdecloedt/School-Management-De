INSERT INTO `menus` (`displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
('social', 'social', 0, 'dripicons-network-1', 1, 1, 1, 1, 1, 0, 0,  20, 0, 'social');


INSERT INTO `menus` (`displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
('wall', 'wall', 136, NULL, 1, 1, 1, 1, 1, 0, 0,  10, 0, 'wall'),
('people', 'people', 136, NULL,  1, 1, 1, 1, 1, 0, 0,  10, 0, 'people'),
('spaces', 'spaces', 136, NULL, 1, 1, 1, 1, 1, 0, 0,  10, 0, 'spaces');

INSERT INTO `menus` (`displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
('chat', 'chat', 0, 'dripicons-message', 1, 1, 1, 1, 1, 0, 0,  61, 0, 'chat');


--ajout la colonne humhub_space_id dans la table classes--
ALTER TABLE classes
ADD COLUMN humhub_space_id INT NULL AFTER price;

--ajout la colonne humhub_id dans la table users--
ALTER TABLE users
ADD COLUMN humhub_id INT NULL AFTER email;
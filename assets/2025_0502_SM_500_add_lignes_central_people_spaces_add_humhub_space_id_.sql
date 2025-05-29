INSERT INTO `menus` (`displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
('social', 'social', 0, 'dripicons-network-1', 1, 1, 1, 1, 1, 0, 0,  20, 0, 'social');


INSERT INTO `menus` (`displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
('central', 'central', 136, NULL, 1, 1, 1, 1, 1, 0, 0,  10, 0, 'central'),
('people', 'people', 136, NULL,  1, 1, 1, 1, 1, 0, 0,  10, 0, 'people'),
('spaces', 'spaces', 136, NULL, 1, 1, 1, 1, 1, 0, 0,  10, 0, 'spaces');

INSERT INTO `menus` (`displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
('chat', 'chat', 0, 'dripicons-message', 1, 1, 1, 1, 1, 0, 0,  61, 0, 'chat');

--updates menus--

--UPDATE menus SET parent = 0 WHERE unique_identifier = 'humhub';
-- ID de humhub = 136 (exemple)
-- UPDATE menus SET parent = 136 WHERE unique_identifier = 'central';

-- -- Récupération de l’ID de central
-- SET @central_id = (SELECT id FROM menus WHERE unique_identifier = 'central');

-- UPDATE menus 
--   SET parent = @central_id 
--  WHERE unique_identifier IN ('people', 'spaces');


--new insertion--

-- INSERT INTO `menus`
--   (`displayed_name`, `route_name`, `parent`, `icon`, `status`,`superadmin_access`, `admin_access`, `teacher_access`, `student_access`,`accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`)
-- VALUES
-- -- Central, enfant de Humhub (ID = 136)
--   ('central', 'central', 136, NULL, 1,1, 1, 1, 1,0, 0, 10, 0, 'central'),
--   -- People, enfant de Central (récupéré dynamiquement)
--   ('people', 'people',
--    (SELECT id FROM `menus` WHERE `unique_identifier` = 'central'),NULL, 1,1, 1, 1, 1,0, 0, 10, 0, 'people'),
--   -- Spaces, enfant de Central
--   ('spaces', 'spaces',
--    (SELECT id FROM `menus` WHERE `unique_identifier` = 'central'),NULL, 1,1, 1, 1, 1,0, 0, 10, 0, 'spaces');


--ajout la colonne humhub_space_id dans la table classes--
ALTER TABLE classes
ADD COLUMN humhub_space_id INT NULL AFTER price;
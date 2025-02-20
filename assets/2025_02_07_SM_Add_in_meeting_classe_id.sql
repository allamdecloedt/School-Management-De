
ALTER TABLE sessions_meetings
ADD COLUMN class_id int(11) NULL;


ALTER TABLE sessions_meetings ADD UNIQUE (meeting_id);

CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meeting_id VARCHAR(255) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    username VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (meeting_id) REFERENCES sessions_meetings(meeting_id) ON DELETE CASCADE ON UPDATE CASCADE    
);




CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    user_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE ON UPDATE CASCADE
    
);



ALTER TABLE sessions_meetings
ADD COLUMN room_id int(11) NULL;


INSERT INTO `menus` (`id`, `displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
(129, 'Live Classes / Virtual Room', NULL, 0, 'dripicons-store', 1, 1, 1, 1, 1, 0, 0, 20, 0, 'Live_Classes');



INSERT INTO `menus` (`id`, `displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
(130, 'Create & Join Session', 'Create_Join', 129, NULL, 1, 1, 1, 1, 1, 0, 0, 10, 0, 'Create_Join');

INSERT INTO `menus` (`id`, `displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
(131, 'Session Schedule (consulter)', 'Session_Schedule', 129, NULL, 1, 1, 1, 1, 1, 0, 0, 10, 0, 'Session_Schedule'),
(132, 'Recordings', 'Create_Join', 129, NULL, 1, 1, 1, 1, 1, 0, 0, 10, 0, 'Create_Join'),
(133, 'Content Sharing', 'Content_Sharing', 129, NULL, 1, 1, 1, 1, 1, 0, 0, 10, 0, 'Content_Sharing'),
(134, 'Chat & Discussions', 'Chat_Discussions', 129, NULL, 1, 1, 1, 1, 1, 0, 0, 10, 0, 'Chat_Discussions'),
(135, 'Join Session', 'Join_Session', 129, NULL, 1, 0, 0, 0, 1, 0, 0, 10, 0, 'Join_Session')
;

ALTER TABLE rooms
ADD COLUMN Etat int(11) NULL DEFAULT '1';

               

                

        

              
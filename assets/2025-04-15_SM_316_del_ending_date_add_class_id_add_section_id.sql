ALTER TABLE exams
DROP COLUMN ending_date,
ADD COLUMN class_id INT(11) NULL AFTER starting_date,
ADD COLUMN section_id INT(11) NULL AFTER class_id;
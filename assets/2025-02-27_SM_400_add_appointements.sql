CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    start_date DATETIME NOT NULL,
    description TEXT
);


ALTER TABLE appointments
ADD COLUMN room_id int(11) NULL;

ALTER TABLE appointments
ADD COLUMN classe_id int(11) NULL;

-- ALTER TABLE appointments
-- ADD COLUMN section_id int(11) NULL;



ALTER TABLE appointments
ADD COLUMN Etat int(11) NULL DEFAULT '1';



ALTER TABLE appointments
DROP COLUMN section_id;

ALTER TABLE appointments
ADD COLUMN sections_id VARCHAR(255) NULL;

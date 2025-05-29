CREATE TABLE `exam_questions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` TEXT COLLATE utf8_general_ci DEFAULT NULL,
  `exam_id` INT(11) UNSIGNED DEFAULT NULL,
  `type` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `number_of_options` INT(11) DEFAULT NULL,
  `options` TEXT COLLATE utf8_general_ci DEFAULT NULL,
  `correct_answers` TEXT COLLATE utf8_general_ci DEFAULT NULL,
  `order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  CONSTRAINT `exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
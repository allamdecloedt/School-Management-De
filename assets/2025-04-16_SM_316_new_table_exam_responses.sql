CREATE TABLE `exam_responses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `exam_id` INT(11) UNSIGNED NOT NULL,
  `exam_question_id` INT(11) UNSIGNED NOT NULL,
  `submitted_answers` TEXT COLLATE utf8_general_ci NOT NULL,
  `correct_answers` TEXT COLLATE utf8_general_ci NOT NULL,
  `submitted_answer_status` TINYINT(4) NOT NULL,
  `date_submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `exam_id` (`exam_id`),
  KEY `exam_question_id` (`exam_question_id`),
  CONSTRAINT `exam_responses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exam_responses_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exam_responses_ibfk_3` FOREIGN KEY (`exam_question_id`) REFERENCES `exam_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
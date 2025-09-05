CREATE TABLE IF NOT EXISTS `students` (
  `id` VARCHAR(255) PRIMARY KEY NOT NULL,
  `display_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `questions` (
  `year` INTEGER NOT NULL,
  `number` INTEGER NOT NULL,
  `category` ENUM ('system', 'processing', 'medical') NOT NULL,
  `edition` INTEGER NOT NULL,
  `section` INTEGER NOT NULL,
  `chapter` INTEGER NOT NULL,
  `item` INTEGER NOT NULL,
  `body` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`year`, `number`, `category`)
);

CREATE TABLE IF NOT EXISTS `options` (
  `number` INTEGER NOT NULL,
  `question_year` INTEGER NOT NULL,
  `question_number` INTEGER NOT NULL,
  `question_category` ENUM ('system', 'processing', 'medical') NOT NULL,
  `correct` BOOLEAN NOT NULL,
  `body` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`number`, `question_year`, `question_number`, `question_category`)
);

CREATE TABLE IF NOT EXISTS `chosen_options` (
  `score_id` VARCHAR(255) NOT NULL,
  `result_number` INTEGER NOT NULL,
  `number` INTEGER NOT NULL,
  `question_year` INTEGER NOT NULL,
  `question_number` INTEGER NOT NULL,
  `question_category` ENUM ('system', 'processing', 'medical') NOT NULL,
  PRIMARY KEY (`score_id`, `result_number`)
);

CREATE TABLE IF NOT EXISTS `results` (
  `score_id` VARCHAR(255) NOT NULL,
  `number` INTEGER NOT NULL,
  `question_year` INTEGER NOT NULL,
  `question_number` INTEGER NOT NULL,
  `question_category` ENUM ('system', 'processing', 'medical') NOT NULL,
  `correctness` BOOLEAN NOT NULL,
  PRIMARY KEY (`score_id`, `number`)
);

CREATE TABLE IF NOT EXISTS `scores` (
  `id` VARCHAR(255) PRIMARY KEY NOT NULL,
  `student_id` VARCHAR(255) NOT NULL,
  `score` INTEGER NOT NULL,
  `amount` INTEGER NOT NULL,
  `createad_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ALTER TABLE `chosen_options` ADD FOREIGN KEY (`question_year`, `question_number`, `question_category`, `number`) REFERENCES `options` (`question_year`, `question_number`, `question_category`, `number`) ON DELETE CASCADE;

-- ALTER TABLE `chosen_options` ADD FOREIGN KEY (`result_number`, `score_id`) REFERENCES `results` (`number`, `score_id`);

ALTER TABLE `chosen_options` ADD FOREIGN KEY (`question_year`, `question_number`, `question_category`) REFERENCES `questions` (`year`, `number`, `category`) ON DELETE CASCADE;

ALTER TABLE `chosen_options` ADD FOREIGN KEY (`score_id`, `result_number`) REFERENCES `results` (`score_id`, `number`);

ALTER TABLE `results` ADD FOREIGN KEY (`question_year`, `question_number`, `question_category`) REFERENCES `questions` (`year`, `number`, `category`) ON DELETE CASCADE;

ALTER TABLE `options` ADD FOREIGN KEY (`question_year`, `question_number`, `question_category`) REFERENCES `questions` (`year`, `number`, `category`) ON DELETE CASCADE;

ALTER TABLE `results` ADD FOREIGN KEY (`score_id`) REFERENCES `scores` (`id`);

ALTER TABLE `scores` ADD FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;


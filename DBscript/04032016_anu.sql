ALTER TABLE `course` DROP `isdemo`;

ALTER TABLE `subjects` DROP `isdemo`;

ALTER TABLE `quiz` DROP `is_demo`;

TRUNCATE TABLE `subject_chapter_map`;

TRUNCATE TABLE `chapters`;

TRUNCATE TABLE `quiz`;

TRUNCATE TABLE `quiz_level`;
UPDATE `event_types` SET `name` = 'primary raid' WHERE `event_types`.`id` = 1;
UPDATE `event_types` SET `name` = 'dungeon raid' WHERE `event_types`.`id` = 2;
INSERT INTO `event_types` (`id`, `enabled`, `name`) VALUES (NULL, '1', 'off-night raid');
INSERT INTO `attendance_values`(`event_type_id`, `attendance_type_id`, `value`) VALUES (7, 1, 0.5), (7, 2, 0), (7, 3, 0.5), (7, 4, 0), (7, 5, 0), (7, 6, 0), (7, 7, 0);
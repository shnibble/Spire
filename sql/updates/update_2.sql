UPDATE `security` SET `name` = 'Mod', `description` = 'Moderator security level.' WHERE `security`.`id` = 1;
INSERT INTO `security` (`name`, `description`) VALUES ('User', 'Standard security level for all users.');
UPDATE `security` SET `id` = '0' WHERE `security`.`id` = 4;
UPDATE `users` SET `security` = 0 WHERE `users`.`security` = 1;
UPDATE `log` SET `security_level` = 0 WHERE `log`.`security_level` = 1;
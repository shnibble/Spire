ALTER TABLE `events` ADD `log_attendance` BOOLEAN NOT NULL DEFAULT TRUE AFTER `meetup_instructions`;
INSERT INTO `badges` (`enabled`, `name`, `description`) VALUES ('1', 'Windfury', 'Participated in the guild\'s defeat of Prince Thunderaan <The Wind Seeker> to claim Cosa\'s very own Thunderfury.');
INSERT INTO `user_badges` (`user_id`, `badge_id`) SELECT `user_id`, 5 FROM `characters` WHERE `name` IN ("Lazarak", "Demonwarrior", "Zephyris", "Cosa", "Bar", "Apozih", "Cavi", "Fyar", "Tilz", "Hematologist", "Braikingboss", "Samuraix", "Krollzhul", "Dejavoo", "Gorgonite", "Vasqua", "Walduccio", "Missfugazi", "Pwek", "Deviatia", "Rugoba", "Cyne", "Huntmachine", "Skynyrd", "Alanine", "Lurttz", "Metzko", "Retlef", "Jaegeren", "Blulululu", "Taurus", "Ranajin", "Elila", "Omero", "Novalyfe", "Solvena", "Polz", "Itwasntme", "Theplague", "Synthic", "Bigbalz");
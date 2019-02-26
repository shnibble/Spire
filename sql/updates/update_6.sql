RENAME TABLE `spire`.`raid_template` TO `spire`.`raid_roster_templates`;
RENAME TABLE `spire`.`raid_templates` TO `spire`.`raid_rosters`;
RENAME TABLE `spire`.`raid_template_slots` TO `spire`.`raid_roster_slots`;
RENAME TABLE `spire`.`raid_template_slot_types` TO `spire`.`raid_roster_slot_types`;
ALTER TABLE `raid_rosters` CHANGE `raid_template_id` `raid_roster_template_id` INT(6) NOT NULL;
ALTER TABLE `raid_rosters` ADD `confirmed` BOOLEAN NOT NULL DEFAULT FALSE AFTER `event_id`;
ALTER TABLE `raid_roster_slots` CHANGE `raid_template_id` `raid_roster_id` INT(6) NOT NULL;

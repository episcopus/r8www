######################################################################
# Stats
######################################################################

USE invocare_r8;

CREATE TABLE IF NOT EXISTS `Stats`
    (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `lsc` INT UNSIGNED NOT NULL,
     `msc` INT UNSIGNED NOT NULL,
     `rsc` INT UNSIGNED NOT NULL,
     `tc` INT UNSIGNED NOT NULL,
     `eme` INT UNSIGNED NOT NULL,
     `ptm` INT UNSIGNED NOT NULL,
     `mp` INT UNSIGNED NOT NULL,
     `cp` INT UNSIGNED NOT NULL,
     `runId` INT UNSIGNED NOT NULL,
     PRIMARY KEY (`id`),
     CONSTRAINT FOREIGN KEY (`runId`) REFERENCES `Runs` (`id`) ON DELETE CASCADE);


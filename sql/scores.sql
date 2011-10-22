######################################################################
# Scores
######################################################################

USE invocare_r8;

CREATE TABLE IF NOT EXISTS `Scores`
    (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `initials` VARCHAR(3) NOT NULL,
     `longname` VARCHAR(20) NOT NULL,
     `score` INT UNSIGNED NOT NULL,
     `runId` INT UNSIGNED NOT NULL,
     PRIMARY KEY (`id`),
     CONSTRAINT FOREIGN KEY (`runId`) REFERENCES `Runs` (`id`) ON DELETE CASCADE);


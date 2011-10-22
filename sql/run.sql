######################################################################
# Runs
######################################################################

USE invocare_r8;

CREATE TABLE IF NOT EXISTS `Runs`
    (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `createdAt` DATETIME NOT NULL, 
     PRIMARY KEY (`id`));


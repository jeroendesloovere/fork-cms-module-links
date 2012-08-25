CREATE TABLE IF NOT EXISTS `links_categories` (
 `id` INT (11) NOT NULL auto_increment,
 `extra_id` INT (11) NOT NULL,
 `language` VARCHAR (5) NOT NULL,
 `title` VARCHAR (255) NOT NULL,
 `logo` VARCHAR (255) DEFAULT NULL,
 `hidden` enum('Y','N') NOT NULL DEFAULT 'N',
 `sequence` INT (11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links` (
 `id` INT (11) NOT NULL auto_increment,
 `category_id` INT (11) NOT NULL,
 `language` VARCHAR (5) NOT NULL,
 `created_on` DATETIME NOT NULL,
 `title` VARCHAR (255) NOT NULL,
 `url` VARCHAR (255) NOT NULL,
 `description` VARCHAR (255) NOT NULL,
 `hidden` enum('Y','N') NOT NULL DEFAULT 'N',
 `alive` enum('Y','N') NOT NULL DEFAULT 'Y',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `links_categories` (
 `id` INT (11) NOT NULL auto_increment,
 `language` VARCHAR (5) NOT NULL,
 `title` VARCHAR (255) NOT NULL,
 `hidden` VARCHAR ( 1 ) NOT NULL DEFAULT  'N',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links_links` (
 `id` INT (11) NOT NULL auto_increment,
 `category_id` INT (11) NOT NULL,
 `language` VARCHAR (5) NOT NULL,
 `created_on` DATETIME NOT NULL,
 `title` VARCHAR (255) NOT NULL,
 `url` VARCHAR (255) NOT NULL,
 `description` VARCHAR (255) NOT NULL,
 `hidden` VARCHAR ( 1 ) NOT NULL DEFAULT  'N',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links_extra_ids` (
 `id` INT (11) NOT NULL auto_increment,
 `category_id` INT (11) NOT NULL,
 `widget_id` INT (11) NOT NULL,
 `locale_id` INT (11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
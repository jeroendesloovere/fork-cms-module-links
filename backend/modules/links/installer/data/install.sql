CREATE TABLE IF NOT EXISTS `links_categories` (
 `id` int(11) NOT NULL auto_increment,
 `language` varchar(5) NOT NULL,
 `title` varchar(255) NOT NULL,
 `hidden` VARCHAR( 1 ) NOT NULL DEFAULT  'N',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `links_links` (
 `id` int(11) NOT NULL auto_increment,
 `category_id` int(11) NOT NULL,
 `language` varchar(5) NOT NULL,
 `created_on` datetime NOT NULL,
 `title` varchar(255) NOT NULL,
 `adress` varchar(255) NOT NULL,
 `description` varchar(255) NOT NULL,
 `hidden` VARCHAR( 1 ) NOT NULL DEFAULT  'N',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
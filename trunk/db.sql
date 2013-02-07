
CREATE TABLE IF NOT EXISTS `user_data` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `pass_hash` varchar(255) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `edit_date` datetime DEFAULT NULL,
  `last_login_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(40) NOT NULL DEFAULT '',
  `contents` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

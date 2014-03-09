CREATE TABLE IF NOT EXISTS `[prefix]_ep_charaktere` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `uid` int(16) NOT NULL,
  `name` varchar(256) NOT NULL,
  `created` int(32) NOT NULL,
  `changed` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `[prefix]_ep_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL,
  `start` int(32) NOT NULL,
  `ends` int(32) NOT NULL,
  `organizer` int(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `event` varchar(128) NOT NULL,
  `message` text NOT NULL,
  `created` int(32) NOT NULL,
  `changed` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `[prefix]_ep_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aktiv` int(2) NOT NULL,
  `eid` int(8) NOT NULL,
  `uid` int(8) NOT NULL,
  `cid` int(8) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `changed` int(32) NOT NULL,
  `registered` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Balthazar3k
 * @package eventplaner
 */

namespace Eventplaner\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'eventplaner';
    public $author = 'Balthazar3k';
    public $name = array
    (
        'en_EN' => 'Eventplaner',
        'de_DE' => 'Eventplaner',
    );
    public $icon_small = 'eventplaner.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
		return array(	
			'CREATE TABLE IF NOT EXISTS `[prefix]_ep_events` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `status` int(2) NOT NULL,
			  `start` int(32) NOT NULL,
			  `ends` int(32) NOT NULL,
			  `registrations` int(3) NOT NULL,
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
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
		);
    }
}
?>
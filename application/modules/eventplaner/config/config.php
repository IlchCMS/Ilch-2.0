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
            'CREATE TABLE IF NOT EXISTS `prefix_ep_events` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `status` int(2) NOT NULL,
                `start` datetime NOT NULL,
                `ends` datetime NOT NULL,
                `registrations` int(3) NOT NULL,
                `organizer` int(32) NOT NULL,
                `title` varchar(128) NOT NULL,
                `event` varchar(128) NOT NULL,
                `message` text NOT NULL,
                `created` datetime NOT NULL,
                `changed` datetime NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

              INSERT INTO `prefix_ep_events` (`id`, `status`, `start`, `ends`, `registrations`, `organizer`, `title`, `event`, `message`, `created`, `changed`) VALUES
              (1, 1, '2014-03-14 18:00:00', '2014-03-14 20:45:00', 10, 1, 'Mein Name ist Hase', 'Modermine', '0.0.0.0', '2014-03-12 00:00:00', '2014-03-13 00:00:00'),
              (2, 1, '2014-03-14 18:00:00', '2014-03-14 20:45:00', 25, 1, 'Frag den Bauer neben an!', 'ICC', '0.0.0.0', '2014-03-12 00:00:00', '2014-03-13 00:00:00'),
              (3, 1, '2014-03-15 18:00:00', '2014-03-15 20:45:00', 10, 1, 'Sex in the City', 'Naxx', '0.0.0.0', '2014-03-12 00:00:00', '2014-03-13 00:00:00');

              CREATE TABLE IF NOT EXISTS `prefix_ep_registrations` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `aktiv` int(2) NOT NULL,
                `eid` int(8) NOT NULL,
                `uid` int(8) NOT NULL,
                `cid` int(8) NOT NULL,
                `comment` varchar(256) NOT NULL,
                `changed` datetime NOT NULL,
                `registered` datetime NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

              INSERT INTO `prefix_ep_registrations` (`id`, `aktiv`, `eid`, `uid`, `cid`, `comment`, `changed`, `registered`) VALUES
              (1, 1, 1, 1, 0, 'Bin dabei!', '2014-03-14 18:30:00', '2014-03-14 21:30:00'),
              (2, 1, 2, 1, 0, 'LetsRock', '2014-03-14 18:30:00', '2014-03-14 21:30:00');'
        );
    }
}
?>
<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
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
        
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('admin_eventplaner_rowperpage', '15');
        $databaseConfig->set('index_eventplaner_rowperpage', '10');
        $databaseConfig->set('close_registrations_time', '02:00');
        $databaseConfig->set('event_start_time', '18:00');
        $databaseConfig->set('event_ends_time', '21:00');
        $databaseConfig->set('event_time_steps', '00:30');
        $databaseConfig->set('event_status', '{"1":{"status":"active","color":"lime"},"2":{"status":"closed","color":"orange"},"3":{"status":"canceled","color":"blue"},"4":{"status":"removed","color":"red"}}');
    }

    public function uninstall()
    {
        return array(
            "DROP TABLE IF EXISTS `[prefix]_ep_events`",
            "DROP TABLE IF EXISTS `[prefix]_ep_registrations`"
        );
    }

    public function getInstallSql()
    {   /* IST NOCH ZUM TESTEN */
        return array(	
            "CREATE TABLE IF NOT EXISTS `[prefix]_ep_events` (
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
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

              CREATE TABLE IF NOT EXISTS `[prefix]_ep_registrations` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `aktiv` int(2) NOT NULL,
                `eid` int(8) NOT NULL,
                `uid` int(8) NOT NULL,
                `cid` int(8) NOT NULL,
                `comment` varchar(256) NOT NULL,
                `changed` datetime NOT NULL,
                `registered` datetime NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
    }
}
?>

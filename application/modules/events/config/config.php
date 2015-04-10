<?php
/**
 * @package ilch
 */

namespace Modules\Events\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'events',
        'author' => 'Veldscholten Kevin',
        'icon_small' => 'events.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Veranstaltungen',
                'description' => 'Hier kannst du Veranstaltungen erstellen und bearbeiten.',
            ),
            'en_EN' => array
            (
                'name' => 'Events',
                'description' => 'Here you can add and change Events.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_events`;
                                 DROP TABLE `[prefix]_events_entrants`;
                                 DROP TABLE `[prefix]_events_comments`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_events` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `title` varchar(100) NOT NULL,
                  `place` varchar(100) NOT NULL,
                  `image` varchar(255) NOT NULL,
                  `text` LONGTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_events_entrants` (
                  `event_id` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  `status` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_events_comments` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `event_id` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'calendar',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'calendar.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Kalender',
                'description' => 'Hier kannst du den Kalender verwalten.',
            ),
            'en_EN' => array
            (
                'name' => 'Calendar',
                'description' => 'Here you can manage the Calendar.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_calendar`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_calendar` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(100) NOT NULL,
                  `place` varchar(100) DEFAULT NULL,
                  `start` DATETIME NOT NULL,
                  `end` DATETIME DEFAULT NULL,
                  `text` MEDIUMTEXT DEFAULT NULL,
                  `color` VARCHAR(7) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}

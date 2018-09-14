<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'calendar',
        'version' => '1.3',
        'icon_small' => 'fa-calendar',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Kalender',
                'description' => 'Hier kannst du den Kalender verwalten.',
            ],
            'en_EN' => [
                'name' => 'Calendar',
                'description' => 'Here you can manage the calendar.',
            ],
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_calendar`;
                                 DROP TABLE `[prefix]_calendar_events`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_calendar` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(100) NOT NULL,
                  `place` VARCHAR(100) DEFAULT NULL,
                  `start` DATETIME NOT NULL,
                  `end` DATETIME DEFAULT NULL,
                  `text` MEDIUMTEXT DEFAULT NULL,
                  `color` VARCHAR(7) DEFAULT NULL,
                  `period_day` INT(1) DEFAULT NULL,
                  `read_access` VARCHAR(191) NOT NULL DEFAULT \'1,2,3\',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_calendar_events` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `url` VARCHAR(191) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("calendar/events/index/");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `read_access` VARCHAR(191) NOT NULL DEFAULT \'1,2,3\';');
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `period_day` INT(1) DEFAULT NULL AFTER `color`;');
            case "1.2":
                // Change VARCHAR length for new table character.
                $this->db()->query('ALTER TABLE `[prefix]_calendar` MODIFY COLUMN `read_access` VARCHAR(191);');
                $this->db()->query('ALTER TABLE `[prefix]_calendar_events` MODIFY COLUMN `url` VARCHAR(191);');
        }
    }
}

<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'calendar',
        'version' => '1.7.0',
        'icon_small' => 'fa-calendar',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Kalender',
                'description' => 'Ein einfacher Kalender. Termine können im Admincenter eingetragen werden.',
            ],
            'en_EN' => [
                'name' => 'Calendar',
                'description' => 'A simple calendar. Appointments can be entered in the admincenter.',
            ],
        ],
        'ilchCore' => '2.1.26',
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
                  `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_calendar_events` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `url` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("calendar/events/index/");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\';');
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `period_day` INT(1) DEFAULT NULL AFTER `color`;');
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_calendar` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_calendar_events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'calendar' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}

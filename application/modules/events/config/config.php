<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'events',
        'version' => '1.0',
        'icon_small' => 'fa-ticket',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Veranstaltungen',
                'description' => 'Hier kannst du Veranstaltungen erstellen und bearbeiten.',
            ],
            'en_EN' => [
                'name' => 'Events',
                'description' => 'Here you can add and change events.',
            ],
        ],
        'boxes' => [
            'events' => [
                'de_DE' => [
                    'name' => 'Veranstaltungen'
                ],
                'en_EN' => [
                    'name' => 'Events'
                ]
            ]
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('event_boxEventLimit', '5');
        $databaseConfig->set('event_uploadpath', 'application/modules/events/static/upload/image/');
        $databaseConfig->set('event_height', '150');
        $databaseConfig->set('event_width', '450');
        $databaseConfig->set('event_size', '102400');
        $databaseConfig->set('event_filetypes', 'jpg jpeg png gif');
        $databaseConfig->set('event_google_maps_api_key', '');
        $databaseConfig->set('event_google_maps_map_typ', 'ROADMAP');
        $databaseConfig->set('event_google_maps_zoom', '18');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_events`;
                                 DROP TABLE `[prefix]_events_entrants`;
                                 DROP TABLE `[prefix]_events_currencies`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_boxEventLimit';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_uploadpath';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_height';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_width';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_size';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_filetypes';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_google_maps_api_key';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_google_maps_map_typ';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'event_google_maps_zoom';
                                 DELETE FROM `[prefix]_modules_folderrights` WHERE `key` = 'events';");

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'events/events/index/';");
        }
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_events` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `start` DATETIME NOT NULL,
                  `end` DATETIME NOT NULL,
                  `title` VARCHAR(100) NOT NULL,
                  `place` VARCHAR(150) NOT NULL,
                  `lat_long` VARCHAR(100) NULL DEFAULT NULL,
                  `image` VARCHAR(255) NULL DEFAULT NULL,
                  `text` LONGTEXT NOT NULL,
                  `currency` TINYINT(1) NOT NULL,
                  `price` VARCHAR(255) NOT NULL,
                  `price_art` TINYINT(1) NOT NULL,
                  `show` TINYINT(1) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_events_entrants` (
                  `event_id` INT(11) NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  `status` TINYINT(1) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_events_currencies` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (1, "EUR (€)");
                INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (2, "USD ($)");
                INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (3, "GBP (£)");
                INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (4, "AUD ($)");
                INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (5, "NZD ($)");
                INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (6, "CHF");

                INSERT INTO `[prefix]_modules_folderrights` (`key`, `folder`) VALUES ("events", "static/upload/image");';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            return 'INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("events/events/index/");';
        }
    }

    public function getUpdate($installedVersion)
    {

    }
}

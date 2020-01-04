<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Config;

use\Ilch\Config\Database as IlchDatabase;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'events',
        'version' => '1.16.0',
        'icon_small' => 'fa-ticket',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'official' => true,
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
        'ilchCore' => '2.1.26',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new IlchDatabase($this->db());
        $databaseConfig->set('event_add_entries_accesses', '2')
            ->set('event_show_members_accesses', '2,3')
            ->set('event_box_event_limit', '5')
            ->set('event_upcoming_event_limit', '5')
            ->set('event_current_event_limit', '5')
            ->set('event_past_event_limit', '5')
            ->set('event_uploadpath', 'application/modules/events/static/upload/image/')
            ->set('event_height', '150')
            ->set('event_width', '450')
            ->set('event_size', '102400')
            ->set('event_filetypes', 'jpg jpeg png gif')
            ->set('event_google_maps_api_key', '')
            ->set('event_google_maps_map_typ', 'ROADMAP')
            ->set('event_google_maps_zoom', '18');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_events`;
            DROP TABLE `[prefix]_events_entrants`;
            DROP TABLE `[prefix]_events_currencies`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_add_entries_accesses';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_show_members_accesses';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_box_event_limit';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_upcoming_event_limit';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_current_event_limit';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_past_event_limit';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_uploadpath';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_height';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_width';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_size';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_filetypes';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_google_maps_api_key';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_google_maps_map_typ';
            DELETE FROM `[prefix]_config` WHERE `key` = 'event_google_maps_zoom';
            DELETE FROM `[prefix]_modules_folderrights` WHERE `key` = 'events';
            DELETE FROM `[prefix]_comments` WHERE `key` LIKE 'events/%';");

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'events/events/index/';");
        }
    }

    public function getInstallSql()
    {
        $installSql =
            'CREATE TABLE IF NOT EXISTS `[prefix]_events` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `start` DATETIME NOT NULL,
                `end` DATETIME NOT NULL,
                `title` VARCHAR(100) NOT NULL,
                `place` VARCHAR(150) NOT NULL,
                `website` VARCHAR(255) NOT NULL,
                `lat_long` VARCHAR(100) NULL DEFAULT NULL,
                `image` VARCHAR(255) NULL DEFAULT NULL,
                `text` LONGTEXT NOT NULL,
                `currency` TINYINT(1) NOT NULL,
                `price` VARCHAR(255) NOT NULL,
                `price_art` TINYINT(1) NOT NULL,
                `show` TINYINT(1) NOT NULL,
                `user_limit` INT(11) NOT NULL,
                `read_access` VARCHAR(255) NOT NULL DEFAULT \'2,3\',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_events_entrants` (
                `event_id` INT(11) NOT NULL,
                `user_id` INT(11) NOT NULL,
                `status` TINYINT(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_events_currencies` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (1, "EUR (€)");
            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (2, "USD ($)");
            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (3, "GBP (£)");
            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (4, "AUD ($)");
            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (5, "NZD ($)");
            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (6, "CHF");

            INSERT INTO `[prefix]_modules_folderrights` (`key`, `folder`) VALUES ("events", "static/upload/image");';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $installSql.='INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("events/events/index/");';
        }
        return $installSql;
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `website` VARCHAR(255) NOT NULL AFTER `place`;');
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `read_access` VARCHAR(255) NOT NULL DEFAULT \'2,3\' AFTER `show`;');
                unlink(APPLICATION_PATH.'/modules/events/views/show/my.php');
            case "1.1":
            case "1.2":
            case "1.3":
            case "1.4":
            case "1.5":
            case "1.6":
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `user_limit` INT(11) NOT NULL AFTER `show`;');
            case "1.7":
            case "1.8":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_events_entrants` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_events_currencies` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');

                $this->db()->query('INSERT INTO `[prefix]_config` (`key`, `value`) VALUES ("event_show_members_accesses", "2,3");');
            case "1.9.0":
            case "1.10.0":
            case "1.11.0":
                // Add default values for the new settings
                $databaseConfig = new IlchDatabase($this->db());
                $databaseConfig->set('event_upcoming_event_limit', '5')
                    ->set('event_current_event_limit', '5')
                    ->set('event_past_event_limit', '5');
            case "1.12.0":
            case "1.13.0":
                // Remove forbidden file extensions.
                $databaseConfig = new IlchDatabase($this->db());
                $blacklist = explode(' ', $databaseConfig->get('media_extensionBlacklist'));
                $imageExtensions = explode(' ', $databaseConfig->get('event_filetypes'));
                $imageExtensions = array_diff($imageExtensions, $blacklist);
                $databaseConfig->set('event_filetypes', implode(' ', $imageExtensions));
        }
    }
}

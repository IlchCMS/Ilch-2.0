<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Config;

use Ilch\Config\Database as IlchDatabase;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'events',
        'version' => '1.23.5',
        'icon_small' => 'fa-solid fa-ticket',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Veranstaltungen',
                'description' => 'Es können Veranstaltungen erstellt und bearbeitet werden. Diese können auf einer Seite oder in einer Box angezeigt werden.',
            ],
            'en_EN' => [
                'name' => 'Events',
                'description' => 'You can add or modify events. These events can be shown on a page or inside a box.',
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
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3',
        'folderRights' => [
            'static/upload/image'
        ]
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
        $this->db()->drop('events', true);
        $this->db()->drop('events_entrants', true);
        $this->db()->drop('events_currencies', true);

        $databaseConfig = new IlchDatabase($this->db());
        $databaseConfig->delete('event_add_entries_accesses');
        $databaseConfig->delete('event_show_members_accesses');
        $databaseConfig->delete('event_box_event_limit');
        $databaseConfig->delete('event_upcoming_event_limit');
        $databaseConfig->delete('event_current_event_limit');
        $databaseConfig->delete('event_past_event_limit');
        $databaseConfig->delete('event_uploadpath');
        $databaseConfig->delete('event_height');
        $databaseConfig->delete('event_width');
        $databaseConfig->delete('event_size');
        $databaseConfig->delete('event_filetypes');
        $databaseConfig->delete('event_google_maps_api_key');
        $databaseConfig->delete('event_google_maps_map_typ');
        $databaseConfig->delete('event_google_maps_zoom');

        $this->db()->queryMulti("
            DELETE FROM `[prefix]_comments` WHERE `key` LIKE 'events/%';");

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'events/events/index/';");
        }
    }

    public function getInstallSql(): string
    {
        $installSql =
            'CREATE TABLE IF NOT EXISTS `[prefix]_events` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `start` DATETIME NOT NULL,
                `end` DATETIME NOT NULL,
                `title` VARCHAR(100) NOT NULL,
                `place` VARCHAR(150) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
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
            INSERT INTO `[prefix]_events_currencies` (`id`, `name`) VALUES (6, "CHF");';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $installSql .= 'INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("events/events/index/");';
        }
        return $installSql;
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `website` VARCHAR(255) NOT NULL AFTER `place`;');
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `read_access` VARCHAR(255) NOT NULL DEFAULT \'2,3\' AFTER `show`;');
                unlink(APPLICATION_PATH . '/modules/events/views/show/my.php');
                // no break
            case "1.1":
            case "1.2":
            case "1.3":
            case "1.4":
            case "1.5":
            case "1.6":
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `user_limit` INT(11) NOT NULL AFTER `show`;');
                // no break
            case "1.7":
            case "1.8":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_events_entrants` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_events_currencies` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');

                $this->db()->query('INSERT INTO `[prefix]_config` (`key`, `value`) VALUES ("event_show_members_accesses", "2,3");');
                // no break
            case "1.9.0":
            case "1.10.0":
            case "1.11.0":
                // Add default values for the new settings
                $databaseConfig = new IlchDatabase($this->db());
                $databaseConfig->set('event_upcoming_event_limit', '5')
                    ->set('event_current_event_limit', '5')
                    ->set('event_past_event_limit', '5');
                // no break
            case "1.12.0":
            case "1.13.0":
                // Remove forbidden file extensions.
                $databaseConfig = new IlchDatabase($this->db());
                $blacklist = explode(' ', $databaseConfig->get('media_extensionBlacklist'));
                $imageExtensions = explode(' ', $databaseConfig->get('event_filetypes'));
                $imageExtensions = array_diff($imageExtensions, $blacklist);
                $databaseConfig->set('event_filetypes', implode(' ', $imageExtensions));
                // no break
            case "1.14.0":
            case "1.15.0":
            case "1.16.0":
            case "1.17.0":
            case "1.18.0":
                // Add new type column.
                $this->db()->query('ALTER TABLE `[prefix]_events` ADD `type` VARCHAR(255) NOT NULL AFTER `place`;');
                // no break
            case "1.19.0":
            case "1.20.0":
            case "1.21.0":
            case "1.21.1":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'events' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.21.2":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-ticket' WHERE `key` = 'events';");
                // no break
            case "1.22.0":
            case "1.22.1":
            case "1.22.2":
            case "1.23.0":
                // no break
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

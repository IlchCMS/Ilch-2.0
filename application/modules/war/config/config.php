<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'war',
        'version' => '1.14.0',
        'icon_small' => 'fa-shield',
        'author' => 'Stantin, Thomas',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'War',
                'description' => 'Modul zum Verwalten von Wars. Es können eigene Gruppen und Gegner angelegt werden.',
            ],
            'en_EN' => [
                'name' => 'War',
                'description' => 'Module to manage wars. You can add own groups and opponents.',
            ],
        ],
        'boxes' => [
            'nextwar' => [
                'de_DE' => [
                    'name' => 'Next War'
                ],
                'en_EN' => [
                    'name' => 'Next War'
                ]
            ],
            'lastwar' => [
                'de_DE' => [
                    'name' => 'Last War'
                ],
                'en_EN' => [
                    'name' => 'Last War'
                ]
            ]
        ],
        'ilchCore' => '2.1.37',
        'phpVersion' => '7.0'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('war_boxNextWarLimit', '5')
            ->set('war_boxLastWarLimit', '5');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_war`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_groups`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_enemy`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_played`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_accept`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'war_boxNextWarLimit'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'war_boxLastWarLimit'");
        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'war/wars/index/'");
        }
    }

    public function getInstallSql()
    {
        $installSql =
            'CREATE TABLE IF NOT EXISTS `[prefix]_war_groups` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(32) NOT NULL,
              `tag` VARCHAR(20) NOT NULL,
              `image` VARCHAR(255) NOT NULL,
              `desc` VARCHAR(255) NOT NULL,
              `member` INT(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_war_enemy` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(150) NOT NULL,
              `tag` VARCHAR(20) NOT NULL,
              `homepage` VARCHAR(150) NOT NULL,
              `image` VARCHAR(255) NOT NULL,
              `contact_name` VARCHAR(50) NOT NULL,
              `contact_email` VARCHAR(150) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_war` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `enemy` INT(11) NOT NULL,
              `group` INT(11) NOT NULL,
              `time` DATETIME NOT NULL,
              `maps` VARCHAR(255) NOT NULL,
              `server` VARCHAR(255) NOT NULL,
              `password` VARCHAR(255) NOT NULL,
              `xonx` VARCHAR(50) NOT NULL,
              `game` VARCHAR(255) NOT NULL,
              `matchtype` VARCHAR(255) NOT NULL,
              `report` TEXT NOT NULL,
              `status` TINYINT(1) NOT NULL DEFAULT 0,
              `show` TINYINT(1) NOT NULL DEFAULT 0,
              `read_access` VARCHAR(255) NOT NULL DEFAULT \'2,3\',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_war_played` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `war_id` INT(11) DEFAULT NULL,
              `map` VARCHAR(255) NOT NULL DEFAULT "",
              `group_points` MEDIUMINT(9) DEFAULT NULL,
              `enemy_points` MEDIUMINT(9) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_war_accept` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `war_id` INT(11) DEFAULT NULL,
              `user_id` INT(11) DEFAULT NULL,
              `accept` TINYINT(1) DEFAULT NULL,
              `comment` MEDIUMTEXT NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $installSql.='INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("war/wars/index/");';
        }

        return $installSql;
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_war` ADD `show` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;');
                $this->db()->query('ALTER TABLE `[prefix]_war` ADD `read_access` VARCHAR(255) NOT NULL AFTER `show`;');
            case "1.2":
                // On installation of Ilch adding this entry failed. Reinstalling or a later install of this module adds the entry.
                // Add entry on update. Instead of checking if the entry exists, delete entry/entries and add it again.
                if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
                    $this->db()->query("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'war/wars/index/'");
                    $this->db()->query('INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("war/wars/index/");');
                }
            case "1.3":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_war_groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war_enemy` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war_played` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war_accept` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
                $this->db()->query('ALTER TABLE `[prefix]_war_accept` ADD COLUMN `comment` MEDIUMTEXT;');
            case "1.8.0":
            case "1.9.0":
            case "1.10.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'war' AND `locale` = '%s';", $value['description'], $key));
                }
            case "1.11.0":
            case "1.12.0":
            case "1.13.0":
        }
    }
}

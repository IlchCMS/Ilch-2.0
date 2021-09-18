<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'away',
        'version' => '1.5.0',
        'icon_small' => 'fa-calendar-times-o',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Abwesenheit',
                'description' => 'Benutzer können ihre Abwesenheit (z.B. Urlaubsreise) eintragen, welche dann übersichtlich dargestellt wird und im Admincenter verwaltet werden kann.',
            ],
            'en_EN' => [
                'name' => 'Away',
                'description' => 'User can enter when they are away (e.g. on holidays). There is an overview of this and the entries can be mananged in the admincenter.',
            ],
        ],
        'ilchCore' => '2.1.44',
        'phpVersion' => '7.0'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DROP TABLE `[prefix]_away`;
            DROP TABLE `[prefix]_away_groups`;
            DELETE FROM `[prefix]_config` WHERE `key` = 'away_adminNotification';
            DELETE FROM `[prefix]_config` WHERE `key` = 'away_userNotification';");

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'away/aways/index/';");
        }
    }

    public function getInstallSql()
    {
        $installSql = 
            'CREATE TABLE IF NOT EXISTS `[prefix]_away` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NOT NULL,
            `reason` VARCHAR(100) NOT NULL,
            `start` DATE NOT NULL,
            `end` DATE NOT NULL,
            `text` MEDIUMTEXT NOT NULL,
            `status` INT(11) NOT NULL DEFAULT "2",
            `show` INT(11) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_away_groups` (
            `group_id` INT(11) NOT NULL,
            INDEX `FK_[prefix]_away_groups_[prefix]_groups` (`group_id`) USING BTREE,
            CONSTRAINT `FK_[prefix]_away_groups_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            return $installSql.'INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("away/aways/index/");';
        }
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_away` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'away' AND `locale` = '%s';", $value['description'], $key));
                }

                // Create new table for user groups to be notified on new entries.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_away_groups` (
                        `group_id` INT(11) NOT NULL,
                        INDEX `FK_[prefix]_away_groups_[prefix]_groups` (`group_id`) USING BTREE,
                        CONSTRAINT `FK_[prefix]_away_groups_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        }
    }
}

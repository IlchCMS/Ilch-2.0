<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'training',
        'version' => '1.3.0',
        'icon_small' => 'fa-graduation-cap',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Training',
                'description' => 'Hier kann die Trainingsliste verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Training',
                'description' => 'Here you can manage the training list.',
            ],
        ],
        'ilchCore' => '2.1.15',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_training`;
                                 DROP TABLE `[prefix]_training_entrants`;');

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti('DELETE FROM `[prefix]_calendar_events` WHERE `url` = \'training/trainings/index/\';');
        }
    }

    public function getInstallSql()
    {
        $installSql =
            'CREATE TABLE IF NOT EXISTS `[prefix]_training` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(100) NOT NULL,
                `date` DATETIME NOT NULL,
                `time` INT(11) NOT NULL,
                `place` VARCHAR(100) NOT NULL,
                `contact` INT(11) NOT NULL,
                `voice_server` INT(11) NOT NULL,
                `voice_server_ip` VARCHAR(100) NOT NULL,
                `voice_server_pw` VARCHAR(100) NOT NULL,
                `game_server` INT(11) NOT NULL,
                `game_server_ip` VARCHAR(100) NOT NULL,
                `game_server_pw` VARCHAR(100) NOT NULL,
                `text` MEDIUMTEXT NOT NULL,
                `show` TINYINT(1) NOT NULL DEFAULT 0,
                `read_access` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_training_entrants` (
              `train_id` INT(11) NOT NULL,
              `user_id` INT(11) NOT NULL,
              `note` VARCHAR(100) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $installSql.='INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("training/trainings/index/");';
        }
        return $installSql;
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0.0":
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `show` TINYINT(1) NOT NULL DEFAULT 0 AFTER `text`;');
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `read_access` VARCHAR(191) NOT NULL AFTER `show`;');
            case "1.1.0":
                // On installation of Ilch adding this entry failed. Reinstalling or a later install of this module adds the entry.
                // Add entry on update. Instead of checking if the entry exists, delete entry/entries and add it again.
                if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
                    $this->db()->query("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'training/trainings/index/'");
                    $this->db()->query('INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("training/trainings/index/");');
                }
            case "1.2.0":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_training` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }
}

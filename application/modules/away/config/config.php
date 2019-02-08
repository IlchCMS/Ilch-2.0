<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'away',
        'version' => '1.4.0',
        'icon_small' => 'fa-calendar-times-o',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Abwesenheit',
                'description' => 'Hier kann die Abwesenheitsliste verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Away',
                'description' => 'Here you can manage the awaylist.',
            ],
        ],
        'ilchCore' => '2.1.16',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_away`;');

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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';

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
        }
    }
}

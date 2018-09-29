<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Vote\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'vote',
        'version' => '1.7',
        'icon_small' => 'fa-tasks',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Umfrage',
                'description' => 'Hier kann man die Umfragen verwalten.',
            ],
            'en_EN' => [
                'name' => 'Vote',
                'description' => 'Here you can manage the Vote.',
            ]
        ],
        'boxes' => [
            'vote' => [
                'de_DE' => [
                    'name' => 'Umfrage'
                ],
                'en_EN' => [
                    'name' => 'Vote'
                ]
            ]
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
        $this->db()->queryMulti('DROP TABLE `[prefix]_poll`;
            DROP TABLE `[prefix]_poll_res`;
            DROP TABLE `[prefix]_poll_ip`;');
    }

    public function getInstallSql()
    {
        return "CREATE TABLE IF NOT EXISTS `[prefix]_poll` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `question` VARCHAR(255) NOT NULL,
                `key` VARCHAR(255) NOT NULL,
                `groups` VARCHAR(255) NOT NULL DEFAULT '0',
                `read_access` VARCHAR(255) NOT NULL DEFAULT '2,3',
                `status` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_poll_res` (
                `poll_id` INT(11) NOT NULL,
                `reply` VARCHAR(255) NOT NULL,
                `result` INT(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_poll_ip` (
                `poll_id` INT(11) NOT NULL,
                `ip` VARCHAR(255) NOT NULL,
                `user_id` INT(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;";
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_poll` ADD `read_access` VARCHAR(255) NOT NULL DEFAULT \'2,3\' AFTER `group`;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_ip` ADD `user_id` INT(11) NOT NULL AFTER `ip`;');
            case "1.1":
            case "1.2":
            case "1.3":
            case "1.4":
            case "1.5":
                $this->db()->query('ALTER TABLE `[prefix]_poll` CHANGE `group` `groups` VARCHAR(255) NOT NULL DEFAULT \'0\';');
            case "1.6":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_poll` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_res` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_ip` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }
}

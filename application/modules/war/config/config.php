<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'war',
        'version' => '1.0',
        'icon_small' => 'fa-shield',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'War',
                'description' => 'Hier können die Wars verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'War',
                'description' => 'Here you can manage the wars.',
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
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('war_boxNextWarLimit', '5');
        $databaseConfig->set('war_boxLastWarLimit', '5');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_war`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_groups`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_enemy`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_played`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'war_boxNextWarLimit'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'war_boxLastWarLimit'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_war_groups` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(32) NOT NULL,
                  `tag` VARCHAR(20) NOT NULL,
                  `image` VARCHAR(255) NOT NULL,
                  `desc` VARCHAR(255) NOT NULL,
                  `member` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_war_enemy` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(150) NOT NULL,
                  `tag` VARCHAR(20) NOT NULL,
                  `homepage` VARCHAR(150) NOT NULL,
                  `image` VARCHAR(255) NOT NULL,
                  `contact_name` VARCHAR(50) NOT NULL,
                  `contact_email` VARCHAR(150) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

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
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_war_played` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `war_id` INT(11) DEFAULT NULL,
                  `map` VARCHAR(255) NOT NULL DEFAULT "",
                  `group_points` MEDIUMINT(9) DEFAULT NULL,
                  `enemy_points` MEDIUMINT(9) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}

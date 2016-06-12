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
        'author' => 'Stantin, Thomas',
        'icon_small' => 'fa-shield',
        'languages' => [
            'de_DE' => [
                'name' => 'War',
                'description' => 'Hier können die Wars verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'War',
                'description' => 'Here you can manage the wars.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_war`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_groups`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_enemy`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_played`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_war_groups` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(32) NOT NULL,
                  `tag` VARCHAR(20) NOT NULL,
                  `image` VARCHAR(256) NOT NULL,
                  `member` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_war_enemy` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(150) NOT NULL,
                  `tag` VARCHAR(20) NOT NULL,
                  `homepage` VARCHAR(150) NOT NULL,
                  `image` VARCHAR(256) NOT NULL,
                  `land` VARCHAR(50) NOT NULL,
                  `contact_name` VARCHAR(50) NOT NULL,
                  `contact_email` VARCHAR(150) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_war` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `enemy` INT(11) NOT NULL,
                  `group` INT(11) NOT NULL,
                  `time` DATETIME NOT NULL,
                  `maps` VARCHAR(256) NOT NULL,
                  `server` VARCHAR(256) NOT NULL,
                  `password` VARCHAR(256) NOT NULL,
                  `xonx` VARCHAR(50) NOT NULL,
                  `game` VARCHAR(256) NOT NULL,
                  `matchtype` VARCHAR(256) NOT NULL,
                  `report` TEXT NOT NULL,
                  `status` TINYINT(1) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_war_played` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `war_id` INT(11) DEFAULT NULL,
                  `map` VARCHAR(256) NOT NULL DEFAULT "",
                  `group_pionts` MEDIUMINT(9) DEFAULT NULL,
                  `enemy_pionts` MEDIUMINT(9) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}

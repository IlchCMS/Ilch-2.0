<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Config;

class Config extends \Ilch\Config\Install
{
    public $config =
        [
        'key' => 'statistic',
        'icon_small' => 'fa-pie-chart',
        'system_module' => true,
        'languages' =>
            [
            'de_DE' =>
                [
                'name' => 'Statistik',
                'description' => 'Hier könnt ihr die Seiten Statistik einsehen.',
                ],
            'en_EN' =>
                [
                'name' => 'Statistic',
                'description' => 'Here you can show the Site statistic.',
                ],
            ]
        ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_visits_online` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL DEFAULT 0,
                  `site` VARCHAR(255) NOT NULL,
                  `os` VARCHAR(255) NOT NULL,
                  `os_version` VARCHAR(255) NOT NULL,
                  `browser` VARCHAR(255) NOT NULL,
                  `browser_version` VARCHAR(255) NOT NULL,
                  `ip_address` VARCHAR(255) NOT NULL,
                  `lang` VARCHAR(11) NOT NULL,
                  `date_last_activity` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_visits_stats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `os` VARCHAR(255) NOT NULL,
                  `os_version` VARCHAR(255) NOT NULL,
                  `browser` VARCHAR(255) NOT NULL,
                  `browser_version` VARCHAR(255) NOT NULL,
                  `ip_address` VARCHAR(255) NOT NULL,
                  `referer` VARCHAR(255) NOT NULL,
                  `lang` VARCHAR(11) NOT NULL,
                  `date` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}

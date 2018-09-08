<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'statistic',
        'icon_small' => 'fa-pie-chart',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Statistik',
                'description' => 'Hier könnt ihr die Seiten Statistik einsehen.',
            ],
            'en_EN' => [
                'name' => 'Statistic',
                'description' => 'Here you can show the Site statistic.',
            ],
        ],
        'boxes' => [
            'online' => [
                'de_DE' => [
                    'name' => 'Online'
                ],
                'en_EN' => [
                    'name' => 'Online'
                ]
            ],
            'stats' => [
                'de_DE' => [
                    'name' => 'Statistik'
                ],
                'en_EN' => [
                    'name' => 'Statistic'
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
        $databaseConfig->set('statistic_visibleStats', '1,1,1,1,1,1');
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
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

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
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}

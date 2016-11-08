<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'jobs',
        'version' => '1.0',
        'icon_small' => 'fa-briefcase',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Jobs',
                'description' => 'Hier können Jobs erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'Jobs',
                'description' => 'Here you can create jobs.',
            ],
        ],
        'boxes' => [
            'jobs' => [
                'de_DE' => [
                    'name' => 'Jobs'
                ],
                'en_EN' => [
                    'name' => 'Jobs'
                ]
            ]
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_jobs`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_jobs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(150) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `email` VARCHAR(100) NOT NULL,
                  `show` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}

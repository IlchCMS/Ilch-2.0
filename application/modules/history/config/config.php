<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\History\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'history',
        'version' => '1.0',
        'icon_small' => 'fa-history',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Geschichte',
                'description' => 'Hier kann die Geschichte der Seite erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'History',
                'description' => 'Here you can create a history for your site.',
            ],
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
        $this->db()->queryMulti('DROP TABLE `[prefix]_history`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_history` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date` DATE NOT NULL,
                  `title` VARCHAR(100) NOT NULL,
                  `type` VARCHAR(100) NOT NULL,
                  `color` VARCHAR(10) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}

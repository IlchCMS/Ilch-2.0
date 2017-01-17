<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'shoutbox',
        'version' => '1.0',
        'icon_small' => 'fa-bullhorn',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Shoutbox',
                'description' => 'Hier kann die Shoutbox verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Shoutbox',
                'description' => 'Here you can manage your shoutbox.',
            ],
        ],
        'boxes' => [
            'shoutbox' => [
                'de_DE' => [
                    'name' => 'Shoutbox'
                ],
                'en_EN' => [
                    'name' => 'Shoutbox'
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
        $databaseConfig->set('shoutbox_limit', '5');
        $databaseConfig->set('shoutbox_maxwordlength', '10');
        $databaseConfig->set('shoutbox_maxtextlength', '50');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_shoutbox`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'shoutbox_limit'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'shoutbox_maxwordlength'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_shoutbox` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `name` VARCHAR(100) NOT NULL,
                  `textarea` MEDIUMTEXT NOT NULL,
                  `time` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}

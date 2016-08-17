<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'guestbook',
        'version' => '1.0',
        'icon_small' => 'fa-book',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Gästebuch',
                'description' => 'Hier kann das Gästebuch verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Guestbook',
                'description' => 'Here you can manage your guestbook entries.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('gbook_autosetfree', '1');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_gbook`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'gbook_autosetfree'");
    }
    
    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_gbook` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(32) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `datetime` DATETIME NOT NULL,
                  `homepage` VARCHAR(32) NOT NULL,
                  `name` VARCHAR(256) NOT NULL,
                  `setfree` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}


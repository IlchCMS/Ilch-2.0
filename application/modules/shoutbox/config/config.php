<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'shoutbox',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'shoutbox.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Shoutbox',
                'description' => 'Hier kann die Shoutbox verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Shoutbox',
                'description' => 'Here you can manage your shoutbox.',
            ),
        )
    );

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
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `name` varchar(100) NOT NULL,
                  `textarea` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                  `time` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}

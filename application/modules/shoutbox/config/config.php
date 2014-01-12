<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace shoutbox\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'shoutbox';
    public $author = 'Veldscholten Kevin';
    public $name = array
    (
        'en_EN' => 'Shoutbox',
        'de_DE' => 'Shoutbox',
    );
    public $icon_small = 'shoutbox.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('shoutbox_limit', '5');
        $databaseConfig->set('shoutbox_maxwordlength', '10');
    }

    public function uninstall()
    {
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

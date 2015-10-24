<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'guestbook',
        'author' => 'Stantin, Thomas',
        'icon_small' => 'guestbook.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Gästebuch',
                'description' => 'Hier kann das Gästebuch verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Guestbook',
                'description' => 'Here you can manage your guestbook entries.',
            ),
        )
    );

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
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_gbook`
                (
                   `id` int(11) NOT NULL AUTO_INCREMENT,
                   `email` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                   `text` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                   `datetime` datetime NOT NULL,
                   `homepage` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                   `name` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                   `setfree` int(11) NOT NULL DEFAULT 0,
                   PRIMARY KEY (`id`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
    }
}


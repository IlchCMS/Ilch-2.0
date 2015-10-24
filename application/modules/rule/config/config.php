<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'rule',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'rule.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Regeln',
                'description' => 'Hier können neue Regeln erstellt werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Rules',
                'description' => 'Here you can create new rules.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_rules`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_rules` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `paragraph` int(11) NOT NULL DEFAULT 0,
                  `title` varchar(100) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}

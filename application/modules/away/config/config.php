<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'away',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'away.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Abwesenheit',
                'description' => 'Hier kann die Abwesenheitsliste verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Away',
                'description' => 'Here you can manage the Awaylist.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_away`;');
    }

    public function getInstallSql()
    {
        return "CREATE TABLE IF NOT EXISTS `[prefix]_away` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `reason` VARCHAR(100) NOT NULL,
                  `start` DATE NOT NULL,
                  `end` DATE NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `status` INT(11) NOT NULL DEFAULT '2',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Link\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'link',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'link.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Links',
                'description' => 'Hier können die Links verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Links',
                'description' => 'Here you can manage your links.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_links`;
                                 DROP TABLE `[prefix]_link_cats`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_links` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` int(11) NULL DEFAULT 0,
                  `pos` int(11) NOT NULL DEFAULT 0,
                  `name` VARCHAR(100) NOT NULL,
                  `desc` VARCHAR(255) NOT NULL,
                  `banner` VARCHAR(255) NOT NULL,
                  `link` VARCHAR(255) NOT NULL,
                  `hits` int(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_link_cats` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `parent_id` int(11) NULL DEFAULT 0,
                  `pos` int(11) NOT NULL DEFAULT 0,
                  `name` VARCHAR(100) NOT NULL,
                  `desc` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_links` (`id`, `name`, `desc`, `banner`, `link`) VALUES
                (1, "ilch", "Du suchst ein einfach strukturiertes Content Management System? Dann bist du bei ilch genau richtig! ", "http://ilch.de/include/images/linkus/468x60.png", "http://ilch.de");';
    }
}

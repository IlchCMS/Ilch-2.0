<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Partner\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'partner';
    public $author = 'Veldscholten Kevin';
    public $name = array
    (
        'en_EN' => 'Partner',
        'de_DE' => 'Partner',
    );
    public $icon_small = 'partner.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_partners` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `pos` int(11) NOT NULL DEFAULT 0,
                  `name` varchar(100) NOT NULL,
                  `banner` varchar(255) NOT NULL,
                  `link` varchar(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        
                INSERT INTO `[prefix]_partners` (`id`, `name`, `banner`, `link`) VALUES
                (1, "ilch", "http://ilch.de/include/images/linkus/88x31.png", "http://ilch.de");';
    }
}

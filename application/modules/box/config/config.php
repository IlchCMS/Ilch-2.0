<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Box\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'box';
    public $author = 'Meyer Dominik';
    public $name = array
    (
        'en_EN' => 'Boxes',
        'de_DE' => 'Boxen',
    );
    public $icon_small = 'box.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_boxes` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `date_created` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_boxes_content` (
                  `box_id` int(11) NOT NULL,
                  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
                  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}

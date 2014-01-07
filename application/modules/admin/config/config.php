<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'admin';
    public $author = 'Meyer Dominik';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $date = new \Ilch\Date();
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('version', VERSION, 1);
        $databaseConfig->set('locale', $this->getTranslator()->getLocale(), 1);
        $databaseConfig->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1);
        $databaseConfig->set('timezone', $_SESSION['install']['timezone']);
        $databaseConfig->set('default_layout', '2columns');
    }

    public function uninstall()
    {
    }
    
    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_config` (
                  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `value` text COLLATE utf8_unicode_ci NOT NULL,
                  `autoload` tinyint(1) NOT NULL,
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `icon_small` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_names` (
                  `module_id` int(11) NOT NULL,
                  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_menu` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_menu_items` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `menu_id` int(11) NOT NULL,
                  `sort` int(11) NOT NULL,
                  `parent_id` int(11) NOT NULL,
                  `page_id` int(11) NOT NULL,
                  `box_key` varchar(255) NOT NULL,
                  `type` int(11) NOT NULL,
                  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `module_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_boxes` (
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

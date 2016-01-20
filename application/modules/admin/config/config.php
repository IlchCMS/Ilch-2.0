<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Config;

class Config extends \Ilch\Config\Install
{
    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $date = new \Ilch\Date();
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('version', VERSION, 1);
        $databaseConfig->set('locale', $this->getTranslator()->getLocale(), 1);
        $databaseConfig->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1);
        $databaseConfig->set('timezone', $_SESSION['install']['timezone']);
        $databaseConfig->set('default_layout', 'clan3columns');
        $databaseConfig->set('start_page', 'module_article');
        $databaseConfig->set('page_title', 'ilch - Content Manage System');
        $databaseConfig->set('standardMail', $_SESSION['install']['adminEmail']);
        $databaseConfig->set('admin_layout_top_nav', '');
        $databaseConfig->set('maintenance_mode', '0');
        $databaseConfig->set('maintenance_status', '0');
        $databaseConfig->set('maintenance_date', $date->format('Y-m-d H:i:s'));
        $databaseConfig->set('maintenance_text', '<p>Die Seite befindet sich im Wartungsmodus</p>');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_config` (
                  `key` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `value` TEXT COLLATE utf8_unicode_ci NOT NULL,
                  `autoload` TINYINT(1) NOT NULL,
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules` (
                  `key` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `system` INT(11) NOT NULL,
                  `author` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `icon_small` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_content` (
                  `key` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `locale` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `description` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_folderrights` (
                  `key` VARCHAR(255) NOT NULL,
                  `folder` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_menu` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_menu_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `menu_id` INT(11) NOT NULL,
                  `sort` INT(11) NOT NULL,
                  `parent_id` INT(11) NOT NULL,
                  `page_id` INT(11) NOT NULL,
                  `box_id` INT(11) NOT NULL,
                  `box_key` VARCHAR(255) NOT NULL,
                  `type` INT(11) NOT NULL,
                  `title` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `href` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `module_key` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_boxes` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_boxes_content` (
                  `box_id` INT(11) NOT NULL,
                  `content` MEDIUMTEXT COLLATE utf8_unicode_ci NOT NULL,
                  `locale` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                  `title` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}

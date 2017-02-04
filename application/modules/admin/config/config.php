<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'admin',
        'boxes' => [
            'langswitch' => [
                'de_DE' => [
                    'name' => 'Sprachauswahl'
                ],
                'en_EN' => [
                    'name' => 'Language selection'
                ]
            ],
            'layoutswitch' => [
                'de_DE' => [
                    'name' => 'Layoutauswahl'
                ],
                'en_EN' => [
                    'name' => 'Layout selection'
                ]
            ]
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $date = new \Ilch\Date();
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('version', VERSION, 1);
        $databaseConfig->set('master_update_url', 'http://ilch2.de/ftp/current-release-versions.php');
        $databaseConfig->set('master_download_url', 'http://www.ilch2.de/ftp/Master-');
        $databaseConfig->set('locale', $this->getTranslator()->getLocale(), 1);
        $databaseConfig->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1);
        $databaseConfig->set('timezone', $_SESSION['install']['timezone']);
        $databaseConfig->set('default_layout', 'clan3columns');
        $databaseConfig->set('start_page', 'module_article');
        $databaseConfig->set('favicon', '');
        $databaseConfig->set('apple_icon', '');
        $databaseConfig->set('page_title', 'ilch - Content Management System');
        $databaseConfig->set('description', 'Das ilch CMS bietet dir ein einfach erweiterbares Grundsystem, welches keinerlei Kenntnisse in Programmiersprachen voraussetzt.');
        $databaseConfig->set('standardMail', $_SESSION['install']['adminEmail']);
        $databaseConfig->set('defaultPaginationObjects', 20);
        $databaseConfig->set('admin_layout_top_nav', 'navbar-fixed-top');
        $databaseConfig->set('admin_layout_hmenu', 'hmenu-fixed');
        $databaseConfig->set('maintenance_mode', '0');
        $databaseConfig->set('maintenance_status', '0');
        $databaseConfig->set('maintenance_date', $date->format('Y-m-d H:i:s'));
        $databaseConfig->set('maintenance_text', '<p>Die Seite befindet sich im Wartungsmodus</p>');
        $databaseConfig->set('custom_css', '');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_config` (
                  `key` VARCHAR(255) NOT NULL,
                  `value` TEXT NOT NULL,
                  `autoload` TINYINT(1) NOT NULL,
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules` (
                  `key` VARCHAR(255) NOT NULL,
                  `system` TINYINT(1) NOT NULL DEFAULT 0,
                  `author` VARCHAR(255) NULL DEFAULT NULL,
                  `version` VARCHAR(255) NULL DEFAULT NULL,
                  `link` VARCHAR(255) NULL DEFAULT NULL,
                  `icon_small` VARCHAR(255) NOT NULL,
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_content` (
                  `key` VARCHAR(255) NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `description` VARCHAR(255) NOT NULL,
                  `name` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_php_extensions` (
                  `key` VARCHAR(255) NOT NULL,
                  `extension` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_folderrights` (
                  `key` VARCHAR(255) NOT NULL,
                  `folder` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_modules_boxes_content` (
                  `key` VARCHAR(255) NOT NULL,
                  `module` VARCHAR(255) NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `name` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_menu` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_menu_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `menu_id` INT(11) NOT NULL,
                  `sort` INT(11) NOT NULL DEFAULT 0,
                  `parent_id` INT(11) NOT NULL DEFAULT 0,
                  `page_id` INT(11) NOT NULL DEFAULT 0,
                  `box_id` INT(11) NOT NULL DEFAULT 0,
                  `box_key` VARCHAR(255) NULL DEFAULT NULL,
                  `type` TINYINT(1) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `href` VARCHAR(255) NULL DEFAULT NULL,
                  `module_key` VARCHAR(255) NULL DEFAULT NULL,
                  `access` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_boxes` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_boxes_content` (
                  `box_id` INT(11) NOT NULL,
                  `content` MEDIUMTEXT NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `title` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_pages` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_pages_content` (
                  `page_id` INT(11) NOT NULL,
                  `content` MEDIUMTEXT NOT NULL,
                  `description` MEDIUMTEXT NOT NULL,
                  `keywords` MEDIUMTEXT NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `perma` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_backup` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  `date` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_logs` (
                  `user_id` VARCHAR(255) NOT NULL,
                  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `info` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }

    public function getUpdate($installedVersion)
    {
        return 'Update function executed';
    }
}

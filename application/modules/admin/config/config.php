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
        $databaseConfig->set('version', VERSION, 1)
            ->set('updateserver', 'https://ilch2.de/development/updateserver/stable/')
            ->set('locale', $this->getTranslator()->getLocale(), 1)
            ->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1)
            ->set('timezone', $_SESSION['install']['timezone'])
            ->set('default_layout', 'clan3columns')
            ->set('start_page', 'module_article')
            ->set('favicon', '')
            ->set('apple_icon', '')
            ->set('page_title', 'ilch - Content Management System')
            ->set('description', 'Das ilch CMS bietet dir ein einfach erweiterbares Grundsystem, welches keinerlei Kenntnisse in Programmiersprachen voraussetzt.')
            ->set('standardMail', $_SESSION['install']['adminEmail'])
            ->set('defaultPaginationObjects', 20)
            ->set('admin_layout_hmenu', 'hmenu-fixed')
            ->set('maintenance_mode', '0')
            ->set('maintenance_status', '0')
            ->set('maintenance_date', $date->format('Y-m-d H:i:s'))
            ->set('maintenance_text', '<p>Die Seite befindet sich im Wartungsmodus</p>')
            ->set('custom_css', '');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_config` (
                `key` VARCHAR(255) NOT NULL,
                `value` TEXT NOT NULL,
                `autoload` TINYINT(1) NOT NULL,
                UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                `moduleKey` VARCHAR(255) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                `desc` VARCHAR(255) NOT NULL,
                `text` TEXT NOT NULL,
                `locale` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_modules` (
                `key` VARCHAR(255) NOT NULL,
                `system` TINYINT(1) NOT NULL DEFAULT 0,
                `layout` TINYINT(1) NOT NULL DEFAULT 0,
                `hide_menu` TINYINT(1) NOT NULL DEFAULT 0,
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_admin_notifications` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `module` VARCHAR(255) NOT NULL,
                `message` VARCHAR(255) NOT NULL,
                `url` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_admin_notifications_permission` (
                `module` VARCHAR(255) NOT NULL,
                `granted` TINYINT(1) NOT NULL,
                `limit` TINYINT(1) UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_admin_updateservers` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(255) NOT NULL,
                `operator` VARCHAR(255) NOT NULL,
                `country` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
            
            INSERT INTO `[prefix]_admin_updateservers` (`id`, `url`, `operator`, `country`) VALUES (1, "https://ilch2.de/development/updateserver/stable/", "corian (ilch-Team)", "Germany");
            INSERT INTO `[prefix]_admin_updateservers` (`id`, `url`, `operator`, `country`) VALUES (2, "https://www.blackcoder.de/ilch-us/stable/", "blackcoder (ilch-Team)", "Germany");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "2.0.1":
                // Add new hide_menu column
                $this->db()->query('ALTER TABLE `[prefix]_modules` ADD COLUMN `hide_menu` TINYINT(1) NOT NULL DEFAULT 0;');
                $this->db()->query('UPDATE `[prefix]_modules` SET `hide_menu` = 1 WHERE `key` = "comment";');
                break;
            case "2.0.3":
                // Add new top column for the top article feature
                // Add new read_access column to restrict who can read an article
                $this->db()->query('ALTER TABLE `[prefix]_articles` ADD COLUMN `top` TINYINT(1) NOT NULL DEFAULT 0;');
                $this->db()->query('ALTER TABLE `[prefix]_articles` ADD COLUMN `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\';');

                removeDir(ROOT_PATH.'/vendor');
                rename(ROOT_PATH.'/_vendor', ROOT_PATH.'/vendor');
                break;
            case "2.1.1":
                // Remove no longer needed gallery_id column.
                $this->db()->query('ALTER TABLE `[prefix]_users_gallery_items` DROP COLUMN `gallery_id`;');
                break;
            case "2.1.2":
                // Add new votes column for the article rating feature
                $this->db()->query('ALTER TABLE `[prefix]_articles_content` ADD COLUMN `votes` LONGTEXT NOT NULL;');

                removeDir(ROOT_PATH.'/vendor');
                rename(ROOT_PATH.'/_vendor', ROOT_PATH.'/vendor');
                break;
        }

        return 'Update function executed.';
    }
}

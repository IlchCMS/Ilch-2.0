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
            ->set('hideCaptchaFor', '1')
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
                `access` VARCHAR(255) NOT NULL DEFAULT "",
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
            case "2.1.3":
                $this->db()->query('ALTER TABLE `[prefix]_menu_items` MODIFY COLUMN `access` VARCHAR(255) NOT NULL DEFAULT "";');
                $this->db()->query('ALTER TABLE `[prefix]_users` MODIFY COLUMN `locale` VARCHAR(255) NOT NULL DEFAULT "";');
                break;
            case "2.1.4":
                // Add new columns for user profile
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `steam` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `twitch` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `teamspeak` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `discord` VARCHAR(255) NOT NULL;');
                break;
            case "2.1.5":
                removeDir(ROOT_PATH.'/vendor');
                rename(ROOT_PATH.'/_vendor', ROOT_PATH.'/vendor');
                break;
            case "2.1.7":
                // Create statistic_visibleStats and convert old settings into new format
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $visibilitySettings = $databaseConfig->get('statistic_site');

                if ($databaseConfig->get('statistic_site')) {
                    $visibilitySettings .= ',1,1';
                } else {
                    $visibilitySettings .= ',0,0';
                }

                $visibilitySettings .= ','.$databaseConfig->get('statistic_visits');
                $visibilitySettings .= ','.$databaseConfig->get('statistic_browser');
                $visibilitySettings .= ','.$databaseConfig->get('statistic_os');
                $databaseConfig->set('statistic_visibleStats', $visibilitySettings, 0);

                // Remove the no longer needed settings of the statistic module
                $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_site';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_visits';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_browser';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_os';");

                // Add new default rule to the beginning of the current rules (DSGVO)
                $databaseConfig->set('regist_rules', '<p>Mit der Registrierung auf dieser Webseite, akzeptieren Sie die Datenschutzbestimmungen und den Haftungsausschluss.</p>'
                    .$databaseConfig->get('regist_rules'));

                // Add default value for the captcha setting, which indicates that administrators should not need to solve captchas.
                $databaseConfig->set('hideCaptchaFor', '1');
                break;
            case "2.1.8":
                // Imprint module
                // Create new needed column "imprint"
                $this->db()->query('ALTER TABLE `[prefix]_imprint` ADD COLUMN `imprint` MEDIUMTEXT NULL DEFAULT NULL;');

                // Copy previous entered information to the new column "imprint"
                $content = $this->db()->select('*')
                    ->from('imprint')
                    ->execute()
                    ->fetchAssoc();
                $contentString = '<b>'.$content['paragraph'].'</b><br><br>';
                $contentString .= $content['company'].'<br>';
                $contentString .= $content['name'].'<br>';
                $contentString .= $content['address'].'<br>';
                $contentString .= $content['addressadd'].'<br><br>';
                $contentString .= $content['city'].'<br><br>';

                $contentString .= '<b>Kontakt</b><br>';
                $contentString .= 'Telefon: '.$content['phone'].'<br>';
                $contentString .= 'Telefax: '.$content['fax'].'<br>';
                $contentString .= 'E-Mail: '.$content['email'].'<br><br>';

                $contentString .= 'Registergericht: '.$content['registration'].'<br>';
                $contentString .= 'Handelsregisternummer: '.$content['commercialregister'].'<br>';
                $contentString .= 'Umsatzsteuer-ID-Nummer: '.$content['vatid'].'<br>';

                $contentString .= $content['other'].'<br><br>';
                $contentString .= $content['disclaimer'].'<br>';

                $this->db()->query('UPDATE `[prefix]_imprint` SET `imprint` = \''.$contentString.'\';');

                // Delete now unneeded old columns
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `paragraph`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `company`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `name`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `address`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `addressadd`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `city`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `phone`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `fax`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `email`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `registration`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `commercialregister`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `vatid`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `other`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `disclaimer`;');

                // Delete unneeded files and folders
                unlink(ROOT_PATH.'/application/modules/imprint/controllers/admin/Settings.php');
                removeDir(ROOT_PATH.'/application/modules/imprint/views/admin/settings');
                break;
        }

        return 'Update function executed.';
    }
}

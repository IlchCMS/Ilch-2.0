<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'newsletter',
        'version' => '1.1',
        'icon_small' => 'fa-newspaper-o',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Newsletter',
                'description' => 'Hier kannst du Newsletter verschicken.',
            ],
            'en_EN' => [
                'name' => 'Newsletter',
                'description' => 'Here you can send a newsletter.',
            ],
        ],
        'boxes' => [
            'newsletter' => [
                'de_DE' => [
                    'name' => 'Newsletter'
                ],
                'en_EN' => [
                    'name' => 'Newsletter'
                ]
            ]
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_newsletter`;
                                 DROP TABLE `[prefix]_newsletter_mails`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_user_menu_settings_links` WHERE `key` = 'newsletter/index/settings'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_newsletter` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `subject` VARCHAR(100) NOT NULL,
                  `text` LONGTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_newsletter_mails` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(100) NOT NULL,
                  `selector` char(18),
                  `confirmCode` char(64),
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_user_menu_settings_links` (`key`, `locale`, `description`, `name`) VALUES
                ("newsletter/index/settings", "de_DE", "Hier kannst du deine Newsletter Einstellungen bearbeiten.", "Newsletter"),
                ("newsletter/index/settings", "en_EN", "Here you can manage your Newsletter settings.", "Newsletter");';
    }

    public function getUpdate($installedVersion)
    {

    }
}

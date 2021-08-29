<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'newsletter',
        'version' => '1.6.2',
        'icon_small' => 'fa-newspaper-o',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Newsletter',
                'description' => 'Modul zum Verschicken von Newslettern. Besucher können den Newsletter über eine Box abonnieren.',
            ],
            'en_EN' => [
                'name' => 'Newsletter',
                'description' => 'Module to send newsletters. Visitors can subscribe to your newsletter in a box.',
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
        'ilchCore' => '2.1.26',
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
                ("newsletter/index/settings", "en_EN", "Here you can manage your newsletter settings.", "Newsletter");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_newsletter` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_newsletter_mails` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.6.1":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'newsletter' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}

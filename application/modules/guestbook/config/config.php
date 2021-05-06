<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'guestbook',
        'version' => '1.10.0',
        'icon_small' => 'fa-book',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Gästebuch',
                'description' => 'Hier kann das Gästebuch verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Guestbook',
                'description' => 'Here you can manage your guestbook entries.',
            ],
        ],
        'ilchCore' => '2.1.42',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('gbook_autosetfree', '1');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_gbook`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'gbook_autosetfree'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'gbook_notificationOnNewEntry'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'gbook_welcomeMessage'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'gbook_entriesPerPage'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_gbook` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(32) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `datetime` DATETIME NOT NULL,
                  `homepage` VARCHAR(32) NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  `setfree` TINYINT(1) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_gbook` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.9.0":
                // update Captcha
        }
    }
}


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
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Gästebuch',
                'description' => 'Ein Gästebuch mit optionaler Willkommensnachricht. Neue Einträge können auf Wunsch erst nach Freischaltung angezeigt werden.',
            ],
            'en_EN' => [
                'name' => 'Guestbook',
                'description' => 'A guestbook with optional welcome message. New entries can be shown only after approval if wished. ',
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
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
            case "1.9.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'vote' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}


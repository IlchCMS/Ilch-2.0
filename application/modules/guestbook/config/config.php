<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Config;

use Ilch\Config\Database;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'guestbook',
        'version' => '1.14.2',
        'icon_small' => 'fa-solid fa-book',
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
                'description' => 'A guestbook with optional welcome message. New entries can be shown only after approval if wished.',
            ],
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new Database($this->db());
        $databaseConfig->set('gbook_autosetfree', '1');
    }

    public function uninstall()
    {
        $databaseConfig = new Database($this->db());
        $databaseConfig->delete('gbook_autosetfree');
        $databaseConfig->delete('gbook_notificationOnNewEntry');
        $databaseConfig->delete('gbook_welcomeMessage');
        $databaseConfig->delete('gbook_entriesPerPage');

        $this->db()->drop('gbook', true);
    }

    public function getInstallSql(): string
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

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_gbook` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
            case "1.9.0":
            case "1.10.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'guestbook' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.11.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-book' WHERE `key` = 'guestbook';");
                // no break
            case "1.12.0":
            case "1.13.0":
            case "1.13.1":
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

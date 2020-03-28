<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\History\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'history',
        'version' => '1.6.0',
        'icon_small' => 'fa-history',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Geschichte',
                'description' => 'Hier kann die Geschichte der Seite erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'History',
                'description' => 'Here you can create a history for your site.',
            ],
        ],
        'ilchCore' => '2.1.26',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('history_desc_order', '0');

        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'history_desc_order'");

        $this->db()->queryMulti('DROP TABLE `[prefix]_history`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_history` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date` DATE NOT NULL,
                  `title` VARCHAR(100) NOT NULL,
                  `type` VARCHAR(100) NOT NULL,
                  `color` VARCHAR(10) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_history` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.1":
            case "1.1.0":
            case "1.2.0":
            case "1.3.0":
                // Add sort order setting
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('history_desc_order', '0');
            case "1.4.0":
            case "1.5.0":
                // convert type to new icons
                $this->db()->queryMulti("UPDATE `[prefix]_history` SET `type` = 'fas fa-globe' WHERE `type` = 'globe';
                                              UPDATE `[prefix]_history` SET `type` = 'far fa-lightbulb' WHERE `type` = 'idea';
                                              UPDATE `[prefix]_history` SET `type` = 'fas fa-graduation-cap' WHERE `type` = 'cap';
                                              UPDATE `[prefix]_history` SET `type` = 'fas fa-camera' WHERE `type` = 'picture';
                                              UPDATE `[prefix]_history` SET `type` = 'fas fa-video' WHERE `type` = 'video';
                                              UPDATE `[prefix]_history` SET `type` = 'fas fa-map-marker' WHERE `type` = 'location';");
        }
    }
}

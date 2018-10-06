<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'partner',
        'version' => '1.4.0',
        'icon_small' => 'fa-handshake-o',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Partner',
                'description' => 'Hier können neue Partner erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'Partner',
                'description' => 'Here you can create new partners.',
            ],
        ],
        'boxes' => [
            'partner' => [
                'de_DE' => [
                    'name' => 'Partner'
                ],
                'en_EN' => [
                    'name' => 'Partner'
                ]
            ]
        ],
        'ilchCore' => '2.1.15',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('partners_slider', '0')
            ->set('partners_slider_mode', 'vertical')
            ->set('partners_box_height', '90')
            ->set('partners_slider_speed', '6000');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_partners`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'partners_slider';
            DELETE FROM `[prefix]_config` WHERE `key` = 'partners_slider_mode';
            DELETE FROM `[prefix]_config` WHERE `key` = 'partners_box_height';
            DELETE FROM `[prefix]_config` WHERE `key` = 'partners_slider_speed'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_partners` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `pos` INT(11) NOT NULL DEFAULT 0,
            `name` VARCHAR(100) NOT NULL,
            `banner` VARCHAR(255) NOT NULL,
            `link` VARCHAR(255) NOT NULL,
            `target` TINYINT(1) NOT NULL DEFAULT 0,
            `setfree` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        INSERT INTO `[prefix]_partners` (`id`, `name`, `banner`, `link`, `target`, `setfree`) VALUES
        (1, "ilch", "http://www.ilch.de/include/images/linkus/88x31.png", "http://ilch.de", "0", "1");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('partners_slider_mode', 'vertical');
            case "1.1":
            case "1.2":
                $this->db()->query('ALTER TABLE `[prefix]_partners` ADD `target` TINYINT(1) NOT NULL DEFAULT 0 AFTER `link`;');
            case "1.3":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_partners` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }
}

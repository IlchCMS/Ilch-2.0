<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Partner\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'partner',
        'version' => '1.9.0',
        'icon_small' => 'fa-handshake-o',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Partner',
                'description' => 'Es können Partner erstellt werden, welche auf einer Seite oder in einer Box dargestellt werden.',
            ],
            'en_EN' => [
                'name' => 'Partner',
                'description' => 'You can create new partners, which can be shown on a site or inside of a box.',
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
        'ilchCore' => '2.1.16',
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
        (1, "ilch", "https://www.ilch.de/include/images/linkus/88x31.png", "https://ilch.de", "0", "1");';
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
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
                // Update possible existing entry for ilch to use HTTPS.
                $this->db()->query('UPDATE `[prefix]_partners` SET `banner` = \'https://www.ilch.de/include/images/linkus/88x31.png\', `link` = \'https://ilch.de\' WHERE `id` = \'1\' AND `banner` = \'http://www.ilch.de/include/images/linkus/88x31.png\'');

                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'partner' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'gallery',
        'version' => '1.5.0',
        'icon_small' => 'fa-picture-o',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Galerie',
                'description' => 'Hier kann die Galerie verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Gallery',
                'description' => 'Here you can manage the gallery.',
            ],
        ],
        'ilchCore' => '2.1.16',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_gallery_imgs`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_gallery_items`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_gallery_imgs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `image_id` VARCHAR(150) NOT NULL,
                  `image_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `image_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_gallery_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `gallery_id` INT(11) NOT NULL DEFAULT 0,
                  `sort` INT(11) NULL DEFAULT 0,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `type` TINYINT(1) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `description` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
            case "1.3":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_gallery_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }
}


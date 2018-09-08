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
        'version' => '1.3',
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
        'ilchCore' => '2.0.0',
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
                  `image_title` varchar(191) NOT NULL DEFAULT \'\',
                  `image_description` varchar(191) NOT NULL DEFAULT \'\',
                  `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_gallery_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `gallery_id` INT(11) NOT NULL DEFAULT 0,
                  `sort` INT(11) NULL DEFAULT 0,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `type` TINYINT(1) NOT NULL,
                  `title` varchar(191) NOT NULL,
                  `description` varchar(191) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}


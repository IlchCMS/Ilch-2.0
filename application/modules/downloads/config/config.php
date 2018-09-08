<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'downloads',
        'version' => '1.3',
        'icon_small' => 'fa-arrow-circle-o-down',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Downloads',
                'description' => 'Hier können die Downloads verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Downloads',
                'description' => 'Here you can manage the downloads.',
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
        $this->db()->queryMulti('DROP TABLE `[prefix]_downloads_files`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_downloads_items`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_downloads_files` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `file_id` VARCHAR(150) NOT NULL,
                  `file_title` varchar(191) NOT NULL DEFAULT \'\',
                  `file_description` varchar(191) NOT NULL DEFAULT \'\',
                  `file_image` varchar(191) NOT NULL DEFAULT \'\',
                  `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_downloads_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `downloads_id` INT(11) NOT NULL,
                  `sort` INT(11) NULL DEFAULT 0,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `type` INT(11) NOT NULL,
                  `title` varchar(191) NOT NULL,
                  `description` varchar(191) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}


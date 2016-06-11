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
        'author' => 'Stantin, Thomas',
        'icon_small' => 'fa-arrow-circle-o-down',
        'languages' => [
            'de_DE' => [
                'name' => 'Downloads',
                'description' => 'Hier können die Downloads verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Downloads',
                'description' => 'Here you can manage the downloads.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_downloads_imgs`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_downloads_items`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_downloads_files` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `file_id` varchar(150) NOT NULL,
                  `file_title` varchar(255) NOT NULL,
                  `file_description` varchar(255) NOT NULL,
                  `file_image` varchar(255) NOT NULL,
                  `cat` mediumint(9) NOT NULL DEFAULT 0,
                  `visits` int(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_downloads_items` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `downloads_id` int(11) NOT NULL,
                  `sort` int(11) NOT NULL,
                  `parent_id` int(11) NOT NULL,
                  `type` int(11) NOT NULL,
                  `title` varchar(255) NOT NULL,
                  `description` varchar(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}


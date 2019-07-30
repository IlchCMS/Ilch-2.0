<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Announcement\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'announcement',
        'version' => '1.0',
        'icon_small' => 'fa-quote-right',
        'author' => 'Nex4T',
        'link' => '',
        'languages' => [
            'de_DE' => [
                'name' => 'Announcement',
                'description' => 'Announcement-Box',
            ],
        ],
        'boxes' => [
            'announcement' => [
                'de_DE' => [
                    'name' => 'Announcement'
                ],
                'en_EN' => [
                    'name' => 'Announcement'
                ]
            ]
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_announcements` (
	                    `id` INT(11) NOT NULL AUTO_INCREMENT,
	                    `content` LONGTEXT NOT NULL,
	                    `active` TINYINT(4) NOT NULL DEFAULT '0',
	                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB;";

        $this->db()->queryMulti($query);
    }

    public function uninstall()
    {
        $query = "DROP TABLE IF EXISTS [prefix]_announcements";
        $this->db()->queryMulti($query);
    }

    public function getUpdate($installedVersion)
    {
    }
}

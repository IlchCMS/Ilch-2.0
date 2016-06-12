<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Page\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'page',
        'icon_small' => 'fa-pencil',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Seiten',
                'description' => 'Hier können neue Seiten erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'Pages',
                'description' => 'Here you can create pages.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_pages`;
                                 DROP TABLE `[prefix]_pages_content`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_pages` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_pages_content` (
                  `page_id` INT(11) NOT NULL,
                  `content` MEDIUMTEXT NOT NULL,
                  `description` MEDIUMTEXT NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `perma` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }

    public function getUpdate()
    {

    }
}

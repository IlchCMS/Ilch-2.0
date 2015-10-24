<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Page\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'page',
        'icon_small' => 'page.png',
        'system_module' => true,
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Seiten',
                'description' => 'Hier können neue Seiten erstellt werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Pages',
                'description' => 'Here you can create pages.',
            ),
        )
    );

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
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `date_created` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_pages_content` (
                  `page_id` int(11) NOT NULL,
                  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
                  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
                  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `perma` varchar(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}

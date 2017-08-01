<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Clanlayout\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'clanlayout',
        'version' => '1.1',
        'icon_small' => 'fa-credit-card',
        'author' => 'Ilch.de',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Clan-Layout',
                'description' => 'Hier kann das Clan-Layout verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Clan-Layout',
                'description' => 'Here you can manage the Clan-Layout.',
            ],
        ],
        'isLayout' => true,
        'hide_menu' => true,
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_clanlayout_settings`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_modules` WHERE `key` = 'clanlayout'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_modules_content` WHERE `key` = 'clanlayout'");
    }

    public function getInstallSql()
    {
        $menuMapper = new \Modules\Admin\Mappers\Menu();
        $lastMenuId = $menuMapper->getLastMenuId()+1;
        $lastMenuItemId = $menuMapper->getLastMenuItemId()+1;

        return 'CREATE TABLE IF NOT EXISTS `[prefix]_clanlayout_settings` (
                `key` VARCHAR(255) NOT NULL,
                `value` TEXT NOT NULL,
                UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            INSERT INTO `[prefix]_clanlayout_settings` (`key`, `value`) VALUES ("headername", "Clanname");

            INSERT INTO `[prefix]_menu` (`id`, `title`) VALUES ('.$lastMenuId.', "Footermenü");
    
            INSERT INTO `[prefix]_menu_items` (`menu_id`, `sort`, `parent_id`, `page_id`, `box_id`, `type`, `title`, `module_key`) VALUES
                ('.$lastMenuId.', 0, 0, 0, 0, 3, "Menü", ""),
                ('.$lastMenuId.', 10, '.$lastMenuItemId.', 0, 0, 3, "Kontakt", "contact"),
                ('.$lastMenuId.', 20, '.$lastMenuItemId.', 0, 0, 3, "Impressum", "imprint"),
                ('.$lastMenuId.', 30, '.$lastMenuItemId.', 0, 0, 3, "Datenschutz", "privacy")';
    }

    public function getUpdate($installedVersion)
    {

    }
}

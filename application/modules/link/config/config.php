<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'link',
        'version' => '1.10.0',
        'icon_small' => 'fa-external-link',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Links',
                'description' => 'Hiermit können andere Internetseiten mit Banner und Beschreibung verlinkt und Kategorien zugeordnet werden.',
            ],
            'en_EN' => [
                'name' => 'Links',
                'description' => 'With this you can link other websites with banners and descriptions. Further they can be categorized.',
            ],
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_links`;
                                 DROP TABLE `[prefix]_link_cats`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_links` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` INT(11) NULL DEFAULT 0,
                  `pos` INT(11) NOT NULL DEFAULT 0,
                  `name` VARCHAR(100) NOT NULL,
                  `desc` VARCHAR(255) NOT NULL,
                  `banner` VARCHAR(255) NOT NULL,
                  `link` VARCHAR(255) NOT NULL,
                  `hits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_link_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `pos` INT(11) NOT NULL DEFAULT 0,
                  `name` VARCHAR(100) NOT NULL,
                  `desc` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_links` (`id`, `name`, `desc`, `banner`, `link`) VALUES
                (1, "ilch", "Du suchst ein einfach strukturiertes Content Management System? Dann bist du bei ilch genau richtig! ", "https://www.ilch.de/include/images/linkus/468x60.png", "https://ilch.de");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_links` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_link_cats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'link' AND `locale` = '%s';", $value['description'], $key));
                }

                // Update possibly existing default Ilch entry
                $this->db()->query("UPDATE `[prefix]_links` SET `banner` = 'https://www.ilch.de/include/images/linkus/468x60.png', `link` = 'https://ilch.de' WHERE `id` = 1 AND `banner` = 'http://www.ilch.de/include/images/linkus/468x60.png' AND `link` = 'http://ilch.de';");
        }
    }
}

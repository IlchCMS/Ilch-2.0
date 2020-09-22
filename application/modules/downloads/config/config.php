<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'downloads',
        'version' => '1.12.0',
        'icon_small' => 'fa-arrow-circle-o-down',
        'author' => 'Stantin, Thomas',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Downloads',
                'description' => 'Es können Downloads angelegt werden und Informationen ergänzt werden, sowie Kategorien zugeordnet werden.',
            ],
            'en_EN' => [
                'name' => 'Downloads',
                'description' => 'You can create downloads and add information to them. Further you can add them to categories.',
            ],
        ],
        'ilchCore' => '2.1.37',
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
                  `file_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `file_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `file_image` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_downloads_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `downloads_id` INT(11) NOT NULL,
                  `sort` INT(11) NULL DEFAULT 0,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `type` INT(11) NOT NULL,
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
                $this->db()->query('ALTER TABLE `[prefix]_downloads_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_downloads_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'downloads' AND `locale` = '%s';", $value['description'], $key));
                }
            case "1.9.0":
            case "1.10.0":
            case "1.11.0":
        }
    }
}


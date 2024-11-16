<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Config;

use Modules\Comment\Mappers\Comment as CommentMapper;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'downloads',
        'version' => '1.14.2',
        'icon_small' => 'fa-regular fa-circle-down',
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
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->drop('downloads_files', true);
        $this->db()->drop('downloads_items', true);

        $commentMapper = new CommentMapper();
        $commentMapper->deleteByKey('downloads/index/showfile/id/');
    }

    public function getInstallSql(): string
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

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
            case "1.3":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_downloads_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_downloads_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'downloads' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.9.0":
            case "1.10.0":
            case "1.11.0":
            case "1.12.0":
            case "1.13.0":
            case "1.13.1":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-regular fa-circle-down' WHERE `key` = 'downloads';");
                // no break
            case "1.13.2":
            case "1.13.3":
            case "1.13.4":
            case "1.13.5":
            case "1.14.0":
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

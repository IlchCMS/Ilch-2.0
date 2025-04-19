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
        'version' => '1.14.3',
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
        'ilchCore' => '2.2.7',
        'phpVersion' => '7.4'
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
                  `file_id` INT(11) NOT NULL,
                  `file_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `file_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `file_image` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `item_id` INT(11) NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`) USING BTREE,
                  INDEX `FK_[prefix]_downloads_files_[prefix]_downloads_items` (`item_id`) USING BTREE,
                  CONSTRAINT `FK_[prefix]_downloads_files_[prefix]_downloads_items` FOREIGN KEY (`item_id`) REFERENCES `[prefix]_downloads_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_downloads_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `sort` INT(11) NULL DEFAULT 0,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `type` TINYINT(1) NOT NULL,
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
            case "1.14.1":
            case "1.14.2":
                // Remove the unused column "downloads_id" of the "downloads_items" table. This one always had the value 1.
                $this->db()->query('ALTER TABLE `[prefix]_downloads_items` DROP COLUMN `downloads_id`;');

                // Change the data type of the "type" column in the "downloads_items" table from "INT(11)" to "TINYINT(1)".
                $this->db()->query('ALTER TABLE `[prefix]_downloads_items` MODIFY COLUMN `type` TINYINT(1) NOT NULL;');

                // Change the data type of the "cat" column in the "downloads_files" table from "MEDIUMINT(9)" to "INT(11)". Relation with id column of the downloads_items table.
                // Rename the column "cat" to a more fitting name.
                $this->db()->query('ALTER TABLE `[prefix]_downloads_files` CHANGE COLUMN `cat` `item_id` INT(11) NOT NULL;');

                // Change the data type of the "file_id" column in the "downloads_files" table from "VARCHAR(150)" to "INT(11)". Relation with id column of the media table.
                $this->db()->query('ALTER TABLE `[prefix]_downloads_files` MODIFY COLUMN `file_id` INT(11) NOT NULL;');

                // Delete (orphaned) rows with an "item_id" that does not exist in the "downloads_items" table.
                $filesItemIds = $this->db()->select('item_id')
                    ->from('downloads_files')
                    ->execute()
                    ->fetchList();

                $itemsIds = $this->db()->select('id')
                    ->from('downloads_items')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($filesItemIds ?? [], $itemsIds ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('downloads_files')
                        ->where(['item_id' => $orphanedRows])
                        ->execute();
                }

                // Add FKC to the "downloads_files" table.
                if (!$this->db()->ifForeignKeyConstraintExists('downloads_files', 'FK_[prefix]_downloads_files_[prefix]_downloads_items')) {
                    $this->db()->query('ALTER TABLE `[prefix]_downloads_files` ADD CONSTRAINT `FK_[prefix]_downloads_files_[prefix]_downloads_items` FOREIGN KEY (`item_id`) REFERENCES `[prefix]_downloads_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                // no break
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

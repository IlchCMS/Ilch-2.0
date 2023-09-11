<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Config;

use Ilch\Config\Install;
use Modules\Admin\Models\Box;

class Config extends Install
{
    public $config = [
        'key' => 'gallery',
        'version' => '1.22.0',
        'icon_small' => 'fa-regular fa-image',
        'author' => 'Stantin, Thomas',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Galerie',
                'description' => 'Hiermit können Galerien angelegt werden, welche in Kategorien sortiert werden können.',
            ],
            'en_EN' => [
                'name' => 'Gallery',
                'description' => 'Here you can create galleries, which can be sorted in categories.',
            ],
        ],
        'boxes' => [
            'pictureofx' => [
                'de_DE' => [
                    'name' => 'Bild des X'
                ],
                'en_EN' => [
                    'name' => 'Picture of X'
                ]
            ]
        ],
        'ilchCore' => '2.1.49',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('gallery_picturesPerPage', 1)
            ->set('gallery_pictureOfXSource', '')
            ->set('gallery_pictureOfXInterval', '')
            ->set('gallery_pictureOfXRandom', 0)
            ->set('gallery_venoboxOverlayColor', '')
            ->set('gallery_venoboxNumeration', 0)
            ->set('gallery_venoboxInfiniteGallery', 0)
            ->set('gallery_venoboxBgcolor', '')
            ->set('gallery_venoboxBorder', '0px')
            ->set('gallery_venoboxTitleattr', '');
    }

    public function uninstall()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('gallery_picturesPerPage')
            ->delete('gallery_pictureOfXSource')
            ->delete('gallery_pictureOfXInterval')
            ->delete('gallery_pictureOfXRandom')
            ->delete('gallery_venoboxOverlayColor')
            ->delete('gallery_venoboxNumeration')
            ->delete('gallery_venoboxInfiniteGallery')
            ->delete('gallery_venoboxBgcolor')
            ->delete('gallery_venoboxBorder')
            ->delete('gallery_venoboxTitleattr');

        $this->db()->drop('gallery_imgs', true);
        $this->db()->drop('gallery_items', true);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_gallery_items` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `sort` INT(11) NULL DEFAULT 0,
                    `parent_id` INT(11) NULL DEFAULT 0,
                    `type` TINYINT(1) NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_gallery_imgs` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `image_id` INT(11) NOT NULL DEFAULT 0,
                    `image_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                    `image_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                    `gallery_id` INT(11) NOT NULL DEFAULT 0,
                    `visits` INT(11) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`),
                    INDEX `FK_[prefix]_gallery_imgs_[prefix]_gallery_items` (`gallery_id`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_gallery_imgs_[prefix]_gallery_items` FOREIGN KEY (`gallery_id`) REFERENCES `[prefix]_gallery_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate(string $installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
            case "1.3":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_gallery_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.4.0":
            case "1.5.0":
                // Add "picture of x" box to list of boxes.
                $boxMapper = new \Modules\Admin\Mappers\Box();

                $boxes = $boxMapper->getBoxList('de_DE');
                $alreadyInstalled = false;
                foreach ($boxes as $box) {
                    if ($box->getKey() === 'pictureofx' && $box->getModule() === 'gallery') {
                        $alreadyInstalled = true;
                        break;
                    }
                }

                if ($alreadyInstalled) {
                    break;
                }

                if (isset($this->config['boxes'])) {
                    $boxModel = new Box();
                    $boxModel->setModule($this->config['key']);
                    foreach ($this->config['boxes'] as $key => $value) {
                        $boxModel->addContent($key, $value);
                    }
                    $boxMapper->install($boxModel);
                }
                // no break
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
            case "1.9.0":
            case "1.10.0":
            case "1.11.0":
            case "1.12.0":
            case "1.13.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'gallery' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.14.0":
            case "1.15.0":
            case "1.16.0":
            case "1.17.0":
            case "1.18.0":
            case "1.19.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-regular fa-image' WHERE `key` = 'gallery';");

                // Drop useless column that had always the value of 1.
                $this->db()->query('ALTER TABLE `[prefix]_gallery_items` DROP COLUMN `gallery_id`;');

                // Change column name 'cat' to 'gallery_id' in the gallery_imgs table. Change data type to INT(11) NOT NULL DEFAULT 0 as that is the data type for the ids.
                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` CHANGE COLUMN `cat` `gallery_id` INT(11) NOT NULL DEFAULT 0;');

                // Change data type of column image_id of the gallery_imgs table.
                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` CHANGE COLUMN `image_id` `image_id` INT(11) NOT NULL DEFAULT 0;');

                // Add constraint to the gallery_imgs table after deleting orphaned rows in it (rows with an gallery id not
                // existing in the gallery items table) as this would lead to an error. Fixes orphaned entries in gallery_imgs when deleting a gallery.
                $idsGalleries = $this->db()->select('id')
                    ->from('gallery_items')
                    ->where(['type' => 1])
                    ->execute()
                    ->fetchList();

                $galleryIdsImages = $this->db()->select('gallery_id')
                    ->from('gallery_imgs')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($galleryIdsImages ?? [], $idsGalleries ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('gallery_imgs')
                        ->where(['gallery_id' => $orphanedRows])
                        ->execute();
                }

                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` ADD INDEX `FK_[prefix]_gallery_imgs_[prefix]_gallery_items` (`gallery_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` ADD CONSTRAINT `FK_[prefix]_gallery_imgs_[prefix]_gallery_items` FOREIGN KEY (`gallery_id`) REFERENCES `[prefix]_gallery_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                // no break
            case "1.20.0":
            case "1.20.1":
            case "1.21.0":
            case "1.21.1":
        }
    }
}

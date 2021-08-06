<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'gallery',
        'version' => '1.18.0',
        'icon_small' => 'fa-picture-o',
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
        'ilchCore' => '2.1.37',
        'phpVersion' => '7.0'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_gallery_imgs`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_gallery_items`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_gallery_imgs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `image_id` VARCHAR(150) NOT NULL,
                  `image_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `image_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_gallery_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `gallery_id` INT(11) NOT NULL DEFAULT 0,
                  `sort` INT(11) NULL DEFAULT 0,
                  `parent_id` INT(11) NULL DEFAULT 0,
                  `type` TINYINT(1) NOT NULL,
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
                $this->db()->query('ALTER TABLE `[prefix]_gallery_imgs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_gallery_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
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
                    $boxModel = new \Modules\Admin\Models\Box();
                    $boxModel->setModule($this->config['key']);
                    foreach ($this->config['boxes'] as $key => $value) {
                        $boxModel->addContent($key, $value);
                    }
                    $boxMapper->install($boxModel);
                }
            case "1.6.0":
            case "1.7.0":
            case "1.8.0":
            case "1.9.0":
            case "1.10.0":
            case "1.11.0":
            case "1.12.0":
            case "1.13.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'gallery' AND `locale` = '%s';", $value['description'], $key));
                }
            case "1.14.0":
            case "1.15.0":
            case "1.16.0":
        }
    }
}


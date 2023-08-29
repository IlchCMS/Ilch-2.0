<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\History\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'history',
        'version' => '1.9.0',
        'icon_small' => 'fa-solid fa-clock-rotate-left',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Geschichte',
                'description' => 'Hiermit kann die Geschichte der Seite, des Vereins usw. erzählt werden.',
            ],
            'en_EN' => [
                'name' => 'History',
                'description' => 'With this module you can tell the story of your website, club etc.',
            ],
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('history_desc_order', '0');

        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('history_desc_order');

        $this->db()->drop('history', true);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_history` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date` DATE NOT NULL,
                  `title` VARCHAR(100) NOT NULL,
                  `type` VARCHAR(100) NOT NULL,
                  `color` VARCHAR(10) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_history` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.1":
                // no break
            case "1.1.0":
                // no break
            case "1.2.0":
                // no break
            case "1.3.0":
                // Add sort order setting
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('history_desc_order', '0');
                // no break
            case "1.4.0":
                // no break
            case "1.5.0":
                // convert type to new icons
                $this->db()->query("UPDATE `[prefix]_history` SET `type` = 'fas fa-globe' WHERE `type` = 'globe';");
                $this->db()->query("UPDATE `[prefix]_history` SET `type` = 'far fa-lightbulb' WHERE `type` = 'idea';");
                $this->db()->query("UPDATE `[prefix]_history` SET `type` = 'fas fa-graduation-cap' WHERE `type` = 'cap';");
                $this->db()->query("UPDATE `[prefix]_history` SET `type` = 'fas fa-camera' WHERE `type` = 'picture';");
                $this->db()->query("UPDATE `[prefix]_history` SET `type` = 'fas fa-video' WHERE `type` = 'video';");
                $this->db()->query("UPDATE `[prefix]_history` SET `type` = 'fas fa-map-marker' WHERE `type` = 'location';");

                // remove no longer needed images
                removeDir(APPLICATION_PATH . '/modules/history/static/img');
                // no break
            case "1.6.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'history' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.7.0":
                // no break
            case "1.8.0":
                $this->db()->update('modules', ['icon_small' => $this->config['icon_small']], ['key' => $this->config['key']])->execute();

                foreach (['fas' => 'fa-solid', 'far' => 'fa-regular', 'fab' => 'fa-brands'] as $key => $replace) {
                    $replaceTypes = $this->db()->select(['id', 'type'])
                        ->from('history')
                        ->where(['type LIKE' => $key . ' fa-%'])
                        ->execute()
                        ->fetchRows();
                    foreach ($replaceTypes ?? [] as $entries) {
                        var_dump($entries);
                        $this->db()->update('history', ['type' => preg_replace('/' . $key . ' fa-(.*)/', $replace . ' fa-$1', $entries['type'])], ['id' => $entries['id']])->execute();
                    }
                }
                // no break
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

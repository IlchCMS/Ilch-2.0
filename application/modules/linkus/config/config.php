<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Linkus\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'linkus',
        'version' => '1.6.0',
        'icon_small' => 'fa-link',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Link Us',
                'description' => 'Hiermit können Sie HTML-Code oder BBCode zum Verlinken Ihrer Internetseite zur Verfügung stellen.',
            ],
            'en_EN' => [
                'name' => 'Link Us',
                'description' => 'Provides HTML code or BBCode for others to link to your website.',
            ],
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('linkus_html', '1');
        $databaseConfig->set('linkus_bbcode', '1');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_linkus`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'linkus_html'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'linkus_bbcode'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_linkus` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(100) NOT NULL,
                  `banner` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_linkus` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.1.0":
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'linkus' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}

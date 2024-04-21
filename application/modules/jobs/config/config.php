<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Jobs\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'jobs',
        'version' => '1.6.1',
        'icon_small' => 'fa-solid fa-briefcase',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Jobs',
                'description' => 'Mit diesem Modul können offene Stellen/Jobangebote veröffentlicht werden.',
            ],
            'en_EN' => [
                'name' => 'Jobs',
                'description' => 'With this module you can show job openinings on your website.',
            ],
        ],
        'boxes' => [
            'jobs' => [
                'de_DE' => [
                    'name' => 'Jobs'
                ],
                'en_EN' => [
                    'name' => 'Jobs'
                ]
            ]
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
        $this->db()->drop('jobs', true);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_jobs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(150) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `email` VARCHAR(100) NOT NULL,
                  `show` TINYINT(1) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_jobs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.3.0":
            case "1.4.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'jobs' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.5.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-briefcase' WHERE `key` = 'jobs';");
                // no break
            case "1.6.0":
                // no break
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

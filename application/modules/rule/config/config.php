<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'rule',
        'version' => '1.2',
        'icon_small' => 'fa-gavel',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Regeln',
                'description' => 'Hier können neue Regeln erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'Rules',
                'description' => 'Here you can create new rules.',
            ],
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_rules`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_rules` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `paragraph` varchar(191) NOT NULL,
            `title` VARCHAR(100) NOT NULL,
            `text` MEDIUMTEXT NOT NULL,
            `position` INT(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD `position` INT(11) NOT NULL DEFAULT 0;');
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_rules` MODIFY COLUMN `paragraph` varchar(191) NOT NULL;');
        }
    }
}

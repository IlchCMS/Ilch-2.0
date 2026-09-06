<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'kvticket',
        'version' => '1.6.1',
        'icon_small' => 'fa-solid fa-ticket',
        'author' => 'Veldscholten, Kevin',
        'languages' => [
            'de_DE' => [
                'name' => 'Tickets',
                'description' => 'Mit diesem Module können deine Nutzer Tickets/Bugs melden.',
            ],
            'en_EN' => [
                'name' => 'Tickets',
                'description' => 'With this module, your users can report tickets/bugs.',
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
        $this->db()->queryMulti('DROP TABLE `[prefix]_kvticket`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_kvticket_cat`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_kvticket` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `text` MEDIUMTEXT NOT NULL,
                `status` INT(11) NOT NULL DEFAULT 0,
                `editor` INT(11) NOT NULL DEFAULT 0,
                `creator` INT(11) NOT NULL DEFAULT 0,
                `cat` INT(11) NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_kvticket_cat` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // Change text to MEDIUMTEXT
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` MODIFY COLUMN `text` MEDIUMTEXT NOT NULL;');
                // Add ticket editor
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` ADD `editor` INT(11) NOT NULL DEFAULT 0 AFTER `status`;');
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` ADD `cat` INT(11) NOT NULL DEFAULT 0 AFTER `editor`;');
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_kvticket_cat` (
                                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                                        `title` VARCHAR(255) NOT NULL,
                                        PRIMARY KEY (`id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');
            case "1.2":
                // Add ticket creator
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` ADD `creator` INT(11) NOT NULL DEFAULT 0 AFTER `editor`;');
            case "1.3.0":
                // Add created_at and updated_at
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` ADD `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `cat`;');
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` ADD `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;');
                // Convert old datetime to new created_at
                $entries = $this->db()
                    ->select('*')
                    ->from('kvticket')
                    ->execute()
                    ->fetchRows();
                foreach ($entries as $entry) {
                    $this->db()
                        ->update('kvticket')
                        ->values(['created_at' => $entry['datetime'], 'updated_at' => $entry['datetime']])
                        ->where(['id' => $entry['id']])
                        ->execute();
                }
                // Remove no longer used datetime.
                $this->db()->query('ALTER TABLE `[prefix]_kvticket` DROP COLUMN `datetime`;');
            case "1.4.0":
                // Update icon for FontAwesome 6.
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");
            case "1.5.0":
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'shoutbox',
        'version' => '1.5.1',
        'icon_small' => 'fa-solid fa-bullhorn',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Shoutbox',
                'description' => 'Zum Einbinden einer Shoutbox als Seite oder Box. Mit Unterstützung für Schreibrechte (z.B. nur Mitglieder).',
            ],
            'en_EN' => [
                'name' => 'Shoutbox',
                'description' => 'A shoutbox you can show as a site or box. Supports setting write access (for example only members).',
            ],
        ],
        'boxes' => [
            'shoutbox' => [
                'de_DE' => [
                    'name' => 'Shoutbox'
                ],
                'en_EN' => [
                    'name' => 'Shoutbox'
                ]
            ]
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('shoutbox_limit', '5')
            ->set('shoutbox_maxtextlength', '50')
            ->set('shoutbox_writeaccess', '1,2');
    }

    public function uninstall()
    {
        $this->db()->drop('shoutbox', true);

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('shoutbox_limit')
            ->delete('shoutbox_maxtextlength')
            ->delete('shoutbox_writeaccess');
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_shoutbox` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `textarea` MEDIUMTEXT NOT NULL,
            `time` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                // no break
            case "1.1":
                // no break
            case "1.2":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_shoutbox` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.3.0":
                // no break
            case "1.4.0":
                // no break
            case "1.4.1":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'shoutbox' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.4.2":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");
                // no break
            case "1.5.0":
                // no break
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->delete('shoutbox_limit')
                    ->delete('shoutbox_maxwordlength');
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

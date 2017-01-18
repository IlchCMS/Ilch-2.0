<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'teams',
        'version' => '1.0',
        'icon_small' => 'fa-users',
        'author' => 'Veldscholten, Kevin',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Teams',
                'description' => 'Hier kannst du deine Teams erstellen und bearbeiten.',
            ],
            'en_EN' => [
                'name' => 'Teams',
                'description' => 'Here you can add and change your Teams.',
            ],
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('teams_uploadpath', 'application/modules/teams/static/upload/');
        $databaseConfig->set('teams_height', '80');
        $databaseConfig->set('teams_width', '530');
        $databaseConfig->set('teams_filetypes', 'jpg jpeg png');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_teams`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'teams_uploadpath'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'teams_height'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'teams_width'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'teams_filetypes'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_modules_folderrights` WHERE `key` = 'teams'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_teams` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(100) NOT NULL,
                  `img` VARCHAR(255) NOT NULL,
                  `leader` INT(11) NOT NULL,
                  `coleader` INT(11) NULL DEFAULT NULL,
                  `groupid` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_modules_folderrights` (`key`, `folder`) VALUES
                ("teams", "static/upload/image");';
    }

    public function getUpdate($installedVersion)
    {

    }
}

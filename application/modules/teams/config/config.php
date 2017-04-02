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
        $databaseConfig->set('teams_accept_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>deine Bewerbung auf <i>{sitetitle}</i> im Team <i>{teamname}</i> wurde angenommen.</p>
                              <p>Es wurde mit der Annahme ein Account erstellt, klicken Sie bitte auf folgenden Link um ein Passwort zu vergeben.</p>
                              <p>{confirm}</p>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>');
        $databaseConfig->set('teams_accept_user_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>deine Bewerbung auf <i>{sitetitle}</i> im Team <i>{teamname}</i> wurde angenommen.</p>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>');
        $databaseConfig->set('teams_reject_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>deine Bewerbung auf <i>{sitetitle}</i> im Team <i>{teamname}</i> wurde leider abgelehnt.</p>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_teams`;
                                 DROP TABLE `[prefix]_teams_joins`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'teams_uploadpath';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'teams_height';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'teams_width';
                                 DELETE FROM `[prefix]_config` WHERE `key` = 'teams_filetypes';
                                 DELETE FROM `[prefix]_modules_folderrights` WHERE `key` = 'teams'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_teams` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  `img` VARCHAR(255) NOT NULL,
                  `leader` VARCHAR(255) NOT NULL,
                  `coLeader` VARCHAR(255) NULL DEFAULT NULL,
                  `groupId` INT(11) NOT NULL,
                  `optIn` INT(1) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_teams_joins` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `userId` INT(11) NULL DEFAULT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  `email` VARCHAR(255) NOT NULL,
                  `place` VARCHAR(255) NOT NULL,
                  `birthday` DATE NOT NULL,
                  `gender` INT(1) NOT NULL,
                  `skill` INT(1) NOT NULL,
                  `teamId` INT(11) NOT NULL,
                  `dateCreated` DATETIME NOT NULL,
                  `text` LONGTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_modules_folderrights` (`key`, `folder`) VALUES
                ("teams", "static/upload/image");';
    }

    public function getUpdate($installedVersion)
    {

    }
}

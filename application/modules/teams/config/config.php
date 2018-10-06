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
        'version' => '1.10.0',
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
        'ilchCore' => '2.1.15',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('teams_uploadpath', 'application/modules/teams/static/upload/')
            ->set('teams_height', '80')
            ->set('teams_width', '530')
            ->set('teams_filetypes', 'jpg jpeg png');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_teams`;
             DROP TABLE `[prefix]_teams_joins`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'teams_uploadpath';
             DELETE FROM `[prefix]_config` WHERE `key` = 'teams_height';
             DELETE FROM `[prefix]_config` WHERE `key` = 'teams_width';
             DELETE FROM `[prefix]_config` WHERE `key` = 'teams_filetypes';
             DELETE FROM `[prefix]_modules_folderrights` WHERE `key` = 'teams';
             DELETE FROM `[prefix]_emails` WHERE `moduleKey` = 'teams'");
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
                `optShow` TINYINT(1) NOT NULL,
                `optIn` TINYINT(1) NOT NULL,
                `position` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

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
                `locale` VARCHAR(255) NOT NULL,
                `dateCreated` DATETIME NOT NULL,
                `text` LONGTEXT NOT NULL,
                `decision` TINYINT(1) NOT NULL,
                `undecided` TINYINT(1) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            INSERT INTO `[prefix]_modules_folderrights` (`key`, `folder`) VALUES
            ("teams", "static/upload/image");

            INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
            ("teams", "teams_accept_mail", "Bewerbung annehmen", "<p>Hallo <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>deine Bewerbung auf <i>{sitetitle}</i> im Team <i>{teamname}</i> wurde angenommen.</p>
                  <p>Es wurde mit der Annahme ein Account erstellt, klicken Sie bitte auf folgenden Link um ein Passwort zu vergeben.</p>
                  <p>{confirm}</p>
                  <p>&nbsp;</p>
                  <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                  <p>Administrator</p>", "de_DE"),
            ("teams", "teams_accept_mail", "Accept application", "<p>Hello <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>Your application at <i>{sitetitle}</i> in Team <i>{teamname}</i> was accepted.</p>
                  <p>An account has been created with the acceptance, please click on the following link to assign a password</p>
                  <p>{confirm}</p>
                  <p>&nbsp;</p>
                  <p>Best regards</p>
                  <p>Administrator</p>", "en_EN"),
            ("teams", "teams_accept_user_mail", "User Bewerbung annehmen", "<p>Hallo <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>deine Bewerbung auf <i>{sitetitle}</i> im Team <i>{teamname}</i> wurde angenommen.</p>
                  <p>&nbsp;</p>
                  <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                  <p>Administrator</p>", "de_DE"),
            ("teams", "teams_accept_user_mail", "Accept user application", "<p>Hello <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>Your application at <i>{sitetitle}</i> in Team <i>{teamname}</i> was accepted.</p>
                  <p>&nbsp;</p>
                  <p>Best regards</p>
                  <p>Administrator</p>", "en_EN"),
            ("teams", "teams_reject_mail", "Bewerbung ablehnen", "<p>Hallo <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>deine Bewerbung auf <i>{sitetitle}</i> im Team <i>{teamname}</i> wurde leider abgelehnt.</p>
                  <p>&nbsp;</p>
                  <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                  <p>Administrator</p>", "de_DE"),
            ("teams", "teams_reject_mail", "Reject application", "<p>Hello <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>Your application at <i>{sitetitle}</i> in Team <i>{teamname}</i> was unfortunately rejected.</p>
                  <p>&nbsp;</p>
                  <p>Best regards</p>
                  <p>Administrator</p>", "en_EN");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                // Add new decision and undecided columns needed for the application/joins-history
                $this->db()->query('ALTER TABLE `[prefix]_teams_joins` ADD COLUMN `decision` TINYINT NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_teams_joins` ADD COLUMN `undecided` TINYINT NOT NULL DEFAULT 1;');

                $this->db()->query('ALTER TABLE `[prefix]_teams` ADD COLUMN `position` INT NOT NULL DEFAULT 0;');
                $this->db()->query('ALTER TABLE `[prefix]_teams` MODIFY `optIn` TINYINT NOT NULL;');
            case "1.1":
            case "1.2":
            case "1.3":
                $this->db()->query('ALTER TABLE `[prefix]_teams` ADD COLUMN `optShow` TINYINT(1) NOT NULL AFTER `groupId`;');
            case "1.4":
            case "1.5":
            case "1.6":
            case "1.7":
            case "1.8":
            case "1.9":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_teams` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_teams_joins` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }
}

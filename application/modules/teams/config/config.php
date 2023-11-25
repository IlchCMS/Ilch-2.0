<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Config;

use Ilch\Config\Database as IlchDatabase;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'teams',
        'version' => '1.23.1',
        'icon_small' => 'fa-solid fa-users',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Teams',
                'description' => 'Es können Teams erstellt und bearbeitet werden, sowie Bewerbungen für diese verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Teams',
                'description' => 'You can add or edit teams and manage applications for these teams.',
            ],
        ],
        'ilchCore' => '2.1.52',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $this->db()->query('INSERT INTO `[prefix]_modules_folderrights` (`key`, `folder`) VALUES ("teams", "static/upload/image");');

        $databaseConfig = new IlchDatabase($this->db());
        $databaseConfig->set('teams_uploadpath', 'application/modules/teams/static/upload/')
            ->set('teams_height', '80')
            ->set('teams_width', '530')
            ->set('teams_filetypes', 'jpg jpeg png');
    }

    public function uninstall()
    {
        $this->db()->drop('teams', true);
        $this->db()->drop('teams_joins', true);

        $databaseConfig = new IlchDatabase($this->db());
        $databaseConfig->delete('teams_uploadpath')
            ->delete('teams_height')
            ->delete('teams_width')
            ->delete('teams_filetypes');

        $this->db()->queryMulti("DELETE FROM `[prefix]_modules_folderrights` WHERE `key` = 'teams';
            DELETE FROM `[prefix]_emails` WHERE `moduleKey` = 'teams'");
    }

    /**
     * @return string
     */
    public function getInstallSql(): string
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
                `notifyLeader` TINYINT(1) NOT NULL,
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
                  <p>Administrator</p>", "en_EN"),
            ("teams", "teams_notifyLeader", "Neue Bewerbung", "<p>Hallo <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>Es ist eine neue Bewerbung auf <i>{sitetitle}</i> für das Team <i>{teamname}</i> vorhanden.</p>
                  <p>&nbsp;</p>
                  <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                  <p>Administrator</p>", "de_DE"),
            ("teams", "teams_notifyLeader", "New application", "<p>Hello <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>There is a new application at <i>{sitetitle}</i> for the team <i>{teamname}</i> available.</p>
                  <p>&nbsp;</p>
                  <p>Best regards</p>
                  <p>Administrator</p>", "en_EN");';
    }

    /**
     * @param string $installedVersion
     * @return string
     */
    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                // Add new decision and undecided columns needed for the application/joins-history
                $this->db()->query('ALTER TABLE `[prefix]_teams_joins` ADD COLUMN `decision` TINYINT NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_teams_joins` ADD COLUMN `undecided` TINYINT NOT NULL DEFAULT 1;');

                $this->db()->query('ALTER TABLE `[prefix]_teams` ADD COLUMN `position` INT NOT NULL DEFAULT 0;');
                $this->db()->query('ALTER TABLE `[prefix]_teams` MODIFY `optIn` TINYINT NOT NULL;');
                // no break
            case "1.1":
                // no break
            case "1.2":
                // no break
            case "1.3":
                $this->db()->query('ALTER TABLE `[prefix]_teams` ADD COLUMN `optShow` TINYINT(1) NOT NULL AFTER `groupId`;');
                // no break
            case "1.4":
                // no break
            case "1.5":
                // no break
            case "1.6":
                // no break
            case "1.7":
                // no break
            case "1.8":
                // no break
            case "1.9":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_teams` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_teams_joins` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.10.0":
                // no break
            case "1.11.0":
                // no break
            case "1.12.0":
                // no break
            case "1.13.0":
                // no break
            case "1.14.0":
                // no break
            case "1.15.0":
                // Add notifyLeader column
                $this->db()->query('ALTER TABLE `[prefix]_teams` ADD COLUMN `notifyLeader` TINYINT(1) NOT NULL AFTER `optIn`;');

                // Add new mails for notifying the leader about new applications.
                $this->db()->queryMulti('INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
            ("teams", "teams_notifyLeader", "Neue Bewerbung", "<p>Hallo <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>Es ist eine neue Bewerbung auf <i>{sitetitle}</i> für das Team <i>{teamname}</i> vorhanden.</p>
                  <p>&nbsp;</p>
                  <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                  <p>Administrator</p>", "de_DE"),
            ("teams", "teams_notifyLeader", "New application", "<p>Hello <b>{name}</b>,</p>
                  <p>&nbsp;</p>
                  <p>There is a new application at <i>{sitetitle}</i> for the team <i>{teamname}</i> available.</p>
                  <p>&nbsp;</p>
                  <p>Best regards</p>
                  <p>Administrator</p>", "en_EN");');

                // Remove forbidden file extensions.
                $databaseConfig = new IlchDatabase($this->db());
                $blacklist = explode(' ', $databaseConfig->get('media_extensionBlacklist'));
                $imageExtensions = explode(' ', $databaseConfig->get('teams_filetypes'));
                $imageExtensions = array_diff($imageExtensions, $blacklist);
                $databaseConfig->set('teams_filetypes', implode(' ', $imageExtensions));
                // no break
            case "1.16.0":
                // no break
            case "1.17.0":
                // no break
            case "1.18.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'teams' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.19.0":
                // no break
            case "1.20.0":
                // no break
            case "1.21.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-users' WHERE `key` = 'teams';");
                // no break
            case "1.22.0":
                // no break
            case "1.23.0":
                // no break
        }
        return 'Update function executed.';
    }
}

<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Config;

use Ilch\Config\Database;
use Ilch\Config\Install;

class Config extends Install
{
    public $config = [
        'key' => 'newsletter',
        'version' => '1.8.2',
        'icon_small' => 'fa-regular fa-newspaper',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Newsletter',
                'description' => 'Modul zum Verschicken von Newslettern. Besucher können den Newsletter über eine Box abonnieren.',
            ],
            'en_EN' => [
                'name' => 'Newsletter',
                'description' => 'Module to send newsletters. Visitors can subscribe to your newsletter in a box.',
            ],
        ],
        'boxes' => [
            'newsletter' => [
                'de_DE' => [
                    'name' => 'Newsletter'
                ],
                'en_EN' => [
                    'name' => 'Newsletter'
                ]
            ]
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $databaseConfig = new Database($this->db());
        $databaseConfig->set('newsletter_doubleOptIn', '1');
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->drop('[prefix]_newsletter');
        $this->db()->drop('[prefix]_newsletter_mails');
        $this->db()->queryMulti("DELETE FROM `[prefix]_user_menu_settings_links` WHERE `key` = 'newsletter/index/settings';
            DELETE FROM `[prefix]_emails` WHERE `moduleKey` = 'newsletter';");

        $databaseConfig = new Database($this->db());
        $databaseConfig->delete('newsletter_doubleOptIn');
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_newsletter` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `subject` VARCHAR(100) NOT NULL,
                  `text` LONGTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_newsletter_mails` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(100) NOT NULL,
                  `selector` char(18),
                  `confirmCode` char(64),
                  `doubleOptInDate` DATETIME NOT NULL,
                  `doubleOptInConfirmed` TINYINT(1) NOT NULL DEFAULT 1,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_user_menu_settings_links` (`key`, `locale`, `description`, `name`) VALUES
                ("newsletter/index/settings", "de_DE", "Hier kannst du deine Newsletter Einstellungen bearbeiten.", "Newsletter"),
                ("newsletter/index/settings", "en_EN", "Here you can manage your newsletter settings.", "Newsletter");

                INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                    ("newsletter", "newsletter_doubleOptIn", "Bestätigung Newsletter", "<p>Vielen Dank für Ihr Interesse an den Newsletter von <i>{sitetitle}</i>.</p>
                        <p>Um die Registrierung für den Newsletter zu bestätigen, klicken Sie bitte innerhalb von 24 Stunden auf folgenden Link:</p>
                        <p>{confirm}</p>
                        <p>Sollten Sie sich nicht für den Newsletter eingetragen haben, können Sie diese E-Mail ignorieren.
                        <p>&nbsp;</p>
                        <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                        <p>Administrator</p>", "de_DE"),
                    ("newsletter", "newsletter_doubleOptIn", "Confirmation newsletter", "<p>Thank you for the interest in the newsletter of <i>{sitetitle}</i>.</p>
                        <p>To confirm the registration for the newsletter, please click the following link within 24 hours:</p>
                        <p>{confirm}</p>
                        <p>You can ignore this e-mail if you haven\'t subscribed to the newsletter.
                        <p>&nbsp;</p>
                        <p>Best regards</p>
                        <p>Administrator</p>", "en_EN");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_newsletter` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_newsletter_mails` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
            case "1.6.1":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'newsletter' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.6.2":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");

                // Add e-mail templates for newsletter double opt-in.
                $this->db()->query('INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                    ("newsletter", "newsletter_doubleOptIn", "Bestätigung Newsletter", "<p>Vielen Dank für Ihr Interesse an den Newsletter von <i>{sitetitle}</i>.</p>
                        <p>Um die Registrierung für den Newsletter zu bestätigen, klicken Sie bitte innerhalb von 24 Stunden auf folgenden Link:</p>
                        <p>{confirm}</p>
                        <p>Sollten Sie sich nicht für den Newsletter eingetragen haben, können Sie diese E-Mail ignorieren.
                        <p>&nbsp;</p>
                        <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                        <p>Administrator</p>", "de_DE"),
                    ("newsletter", "newsletter_doubleOptIn", "Confirmation newsletter", "<p>Thank you for the interest in the newsletter of <i>{sitetitle}</i>.</p>
                        <p>To confirm the registration for the newsletter, please click the following link within 24 hours:</p>
                        <p>{confirm}</p>
                        <p>You can ignore this e-mail if you haven\'t subscribed to the newsletter.
                        <p>&nbsp;</p>
                        <p>Best regards</p>
                        <p>Administrator</p>", "en_EN");');

                // Add new columns to 'newsletter_mails' table.
                $this->db()->query('ALTER TABLE `[prefix]_newsletter_mails` ADD COLUMN `doubleOptInDate` DATETIME NOT NULL AFTER `confirmCode`;');
                $this->db()->query('ALTER TABLE `[prefix]_newsletter_mails` ADD COLUMN `doubleOptInConfirmed` TINYINT(1) NOT NULL DEFAULT 1 AFTER `doubleOptInDate`;');
                // no break
            case "1.7.0":
            case "1.7.1":
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Config;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Service\Password as PasswordService;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'user',
        'icon_small' => 'fa-user',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Benutzer',
                'description' => 'Hier können neue Benutzer erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'User',
                'description' => 'Here you can create the users.',
            ],
        ],
        'boxes' => [
            'login' => [
                'de_DE' => [
                    'name' => 'Login'
                ],
                'en_EN' => [
                    'name' => 'Login'
                ]
            ]
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('regist_accept', '1')
            ->set('regist_confirm', '1')
            ->set('regist_setfree', '0')
            ->set('regist_rules', '<p>Mit der Registrierung auf dieser Webseite, akzeptieren Sie die Datenschutzbestimmungen und den Haftungsausschluss.</p>
                  <p>Die Registrierung ist völlig kostenlos.</p>
                  <p>Die Betreiber der Seite übernehmen keine Haftung.</p>
                  <p>Bitte verhalten Sie sich angemessen und mit Respekt gegenüber den anderen Community Mitgliedern.</p>')
            ->set('avatar_uploadpath', 'application/modules/user/static/upload/avatar/')
            ->set('avatar_height', '120')
            ->set('avatar_width', '120')
            ->set('avatar_size', '51200')
            ->set('avatar_filetypes', 'jpg jpeg png gif')
            ->set('usergallery_allowed', '1')
            ->set('usergallery_uploadpath', 'application/modules/user/static/upload/gallery/')
            ->set('usergallery_filetypes', 'jpg jpeg png gif')
            ->set('userdeletetime', '5');

        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();
        $dateCreated = new \Ilch\Date();

        $userModel = new UserModel();
        $userModel->setName($_SESSION['install']['adminName'])
            ->setPassword((new PasswordService())->hash($_SESSION['install']['adminPassword']))
            ->setEmail($_SESSION['install']['adminEmail'])
            ->addGroup($groupMapper->getGroupById(1))
            ->addGroup($groupMapper->getGroupById(2))
            ->setLocale($this->getTranslator()->getLocale())
            ->setDateConfirmed($dateCreated)
            ->setDateCreated($dateCreated);
        $userMapper->save($userModel);
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2;

            CREATE TABLE IF NOT EXISTS `[prefix]_users` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `first_name` VARCHAR(255) NOT NULL DEFAULT "",
                `last_name` VARCHAR(255) NOT NULL DEFAULT "",
                `gender` TINYINT(1) NOT NULL DEFAULT 0,
                `city` VARCHAR(255) NOT NULL DEFAULT "",
                `birthday` DATE NULL DEFAULT NULL,
                `avatar` VARCHAR(255) NOT NULL DEFAULT "",
                `signature` VARCHAR(255) NOT NULL DEFAULT "",
                `locale` VARCHAR(255) NOT NULL DEFAULT "",
                `opt_mail` TINYINT(1) DEFAULT 1,
                `opt_gallery` TINYINT(1) DEFAULT 1,
                `date_created` DATETIME NOT NULL,
                `date_confirmed` DATETIME NULL DEFAULT NULL,
                `date_last_activity` DATETIME NULL DEFAULT NULL,
                `confirmed` TINYINT(1) DEFAULT 1,
                `confirmed_code` VARCHAR(255) NULL DEFAULT NULL,
                `selector` char(18),
                `expires` DATETIME,
                `locked` TINYINT(1) NOT NULL DEFAULT 0,
                `selectsdelete` DATETIME,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_groups` (
                `user_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
                `group_id` INT(11) NOT NULL,
                `page_id` INT(11) DEFAULT 0,
                `module_key` VARCHAR(191) DEFAULT 0,
                `article_id` INT(11) DEFAULT 0,
                `box_id` INT(11) DEFAULT 0,
                `access_level` INT(11) DEFAULT 0,
                PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_profile_fields` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `key` VARCHAR(255) NOT NULL,
                `type` TINYINT(1) NOT NULL,
                `icon` VARCHAR(255) NOT NULL DEFAULT \'\',
                `addition` VARCHAR(255) NOT NULL DEFAULT \'\',
                `show` TINYINT(1) NOT NULL DEFAULT 1,
                `hidden` TINYINT(1) NOT NULL DEFAULT 0,
                `position` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_profile_content` (
                `field_id` INT(11) NOT NULL,
                `user_id` INT(11) NOT NULL,
                `value` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
                `field_id` INT(11) NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_user_friends` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `friend_user_id` INT(11) NOT NULL,
                `approved` TINYINT(1) NOT NULL DEFAULT 2,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_user_menu` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `key` VARCHAR(255) NOT NULL,
                `icon` VARCHAR(255) NOT NULL,
                `position` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_user_menu_settings_links` (
                `key` VARCHAR(255) NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog` (
                `c_id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_one` INT(11) UNSIGNED NOT NULL,
                `user_two` INT(11) UNSIGNED NOT NULL,
                `time` DATETIME NOT NULL,
                PRIMARY KEY (`c_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_hidden` (
                `c_id` INT(11) UNSIGNED NOT NULL,
                `user_id` INT(11) UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_reply` (
                `cr_id` INT(11) NOT NULL AUTO_INCREMENT,
                `reply` TEXT,
                `user_id_fk` INT(11) UNSIGNED NOT NULL,
                `c_id_fk` INT(11) NOT NULL,
                `time` DATETIME NOT NULL,
                `read` TINYINT(1) DEFAULT 0,
                PRIMARY KEY (`cr_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_media` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `name` VARCHAR(50) NOT NULL DEFAULT 0,
                `url` VARCHAR(150) NOT NULL DEFAULT 0,
                `url_thumb` VARCHAR(150) NOT NULL DEFAULT 0,
                `ending` VARCHAR(5) NOT NULL DEFAULT 0,
                `datetime` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_imgs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `image_id` VARCHAR(150)NOT NULL,
                `image_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                `image_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                `visits` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `sort` INT(11) NOT NULL DEFAULT 0,
                `parent_id` INT(11) NOT NULL DEFAULT 0,
                `type` TINYINT(1) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_auth_tokens` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `selector` CHAR(12),
                `token` CHAR(64),
                `userid` INT(11) UNSIGNED NOT NULL,
                `expires` DATETIME,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_cookie_stolen` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_auth_providers` (
                `key` VARCHAR(45) NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                `icon` VARCHAR(255) DEFAULT NULL,
                `module` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`key`),
                UNIQUE KEY `key_UNIQUE` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_auth_providers_modules` (
                `module` VARCHAR(50) NOT NULL,
                `provider` VARCHAR(50) NOT NULL,
                `auth_controller` VARCHAR(255) DEFAULT NULL,
                `auth_action` VARCHAR(255) DEFAULT NULL,
                `unlink_controller` VARCHAR(255) DEFAULT NULL,
                `unlink_action` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`module`, `provider`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_auth_providers` (
                `user_id` INT(11) NOT NULL,
                `provider` VARCHAR(50) NOT NULL,
                `identifier` VARCHAR(255) NOT NULL,
                `screen_name` VARCHAR(255) DEFAULT NULL,
                `oauth_token` VARCHAR(255) DEFAULT NULL,
                `oauth_token_secret` VARCHAR(255) DEFAULT NULL,
                `created_at` VARCHAR(45) DEFAULT NULL,
                PRIMARY KEY (`user_id`, `provider`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                (1, "Administrator"),
                (2, "User"),
                (3, "Guest");

            INSERT INTO `[prefix]_profile_fields` (`id`, `key`, `type`, `icon`, `addition`, `show`, `position`) VALUES
                (1, "website", 2, "fa-globe", "", 1, 0),
                (2, "facebook", 2, "fa-facebook", "https://www.facebook.com/", 1, 1),
                (3, "twitter", 2, "fa-twitter", "https://twitter.com/", 1, 2),
                (4, "google+", 2, "fa-google-plus", "https://plus.google.com/", 1, 3),
                (5, "steam", 2, "fa-steam-square", "https://steamcommunity.com/id/", 1, 4),
                (6, "twitch", 2, "fa-twitch", "https://www.twitch.tv/", 1, 5),
                (7, "teamspeak", 2, "fa-headphones", "ts3server://", 1, 6),
                (8, "discord", 2, "fa-headphones", "https://discord.gg/", 1, 7);

            INSERT INTO `[prefix]_profile_trans` (`field_id`, `locale`, `name`) VALUES
                (1, "de_DE", "Webseite"),
                (1, "en_EN", "Website"),
                (2, "de_DE", "Facebook"),
                (2, "en_EN", "Facebook"),
                (3, "de_DE", "Twitter"),
                (3, "en_EN", "Twitter"),
                (4, "de_DE", "Google+"),
                (4, "en_EN", "Google+"),
                (5, "de_DE", "Steam"),
                (5, "en_EN", "Steam"),
                (6, "de_DE", "Twitch"),
                (6, "en_EN", "Twitch"),
                (7, "de_DE", "Teamspeak"),
                (7, "en_EN", "Teamspeak"),
                (8, "de_DE", "Discord"),
                (8, "en_EN", "Discord");

            INSERT INTO `[prefix]_user_menu` (`id`, `key`, `icon`, `position`) VALUES
                (1, "user/panel/index", "fa-home", 1),
                (2, "user/panel/dialog", "fa-envelope", 2),
                (3, "user/panel/gallery", "fa-picture-o", 3),
                (4, "user/panel/friends", "fa-users", 4),
                (5, "user/panel/settings", "fa-cogs", 5);

            INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                ("user", "regist_confirm_mail", "Registrierbestätigung", "<p>Hallo <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>Willkommen auf <i>{sitetitle}</i>.</p>
                    <p>um die Registrierung erfolgreich abzuschlie&szlig;en klicken Sie Bitte auf folgenden Link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                    <p>Administrator</p>", "de_DE"),
                ("user", "regist_confirm_mail", "Registration confirmation", "<p>Hello <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>Welcome to <i>{sitetitle}</i>.</p>
                    <p>To complete the registration, please click the following link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Best regards</p>
                    <p>Administrator</p>", "en_EN"),
                ("user", "manually_confirm_mail", "Manuelle Registrierbestätigung", "<p>Hallo <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>Willkommen auf <i>{sitetitle}</i>.</p>
                    <p>hiermit wurde dein Account freigeschaltet.</p>
                    <p>&nbsp;</p>
                    <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                    <p>Administrator</p>", "de_DE"),
                ("user", "manually_confirm_mail", "Manual Registration confirmation", "<p>Hello <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>Welcome to <i>{sitetitle}</i>.</p>
                    <p>Your account was activated.</p>
                    <p>&nbsp;</p>
                    <p>Best regards</p>
                    <p>Administrator</p>", "en_EN"),
                ("user", "password_change_mail", "Neues Passwort", "<p>Hallo <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>um Ihr Passwort auf <i>{sitetitle}</i> zu ändern klicken Sie Bitte auf folgenden Link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                    <p>Administrator</p>
                    <p class=\"small text-muted\">Diese Aktion wurde angefordert von der IP-Adresse {remoteaddr}.</p>", "de_DE"),
                ("user", "password_change_mail", "New Password", "<p>Hello <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>to change the Password at <i>{sitetitle}</i> please click at the following link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Best regards</p>
                    <p>Administrator</p>
                    <p class=\"small text-muted\">This action was requested from ip address {remoteaddr}.</p>", "en_EN"),
                ("user", "password_change_fail_mail", "Passwort ändern fehlgeschlagen", "<p>Hallo,</p>
                    <p>&nbsp;</p>
                    <p>Sie oder jemand anderes hat versucht Ihr Passwort zu ändern, aber es wurde kein Benutzer mit dieser E-Mail-Adresse auf {siteurl} gefunden.</p>
                    <p>Wenn Sie auf dieser Seite registriert sind und diese E-Mail erwartet haben, probieren Sie es bitte erneut mit
                    der E-Mail Adresse mit der Sie sich registriert haben.</p>
                    <p>Wenn Sie nicht auf dieser Seite registriert sind, ignorieren Sie diese E-Mail bitte.</p>
                    <p>&nbsp;</p>
                    <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                    <p>Administrator</p>
                    <p class=\"small text-muted\">Diese Aktion wurde angefordert von der IP-Adresse {remoteaddr}.</p>", "de_DE"),
                ("user", "password_change_fail_mail", "Password reset failed", "<p>Hello,</p>
                    <p>&nbsp;</p>
                    <p>You or someone else tried to change your password at {siteurl}, but no user was found with your email address.</p>
                    <p>If you are registered at this site and were expecting this email, please try again using the email address
                    you gave when registering.</p>
                    <p>If you are not registered at this site, please ignore this email.</p>
                    <p>&nbsp;</p>
                    <p>Best regards</p>
                    <p>Administrator</p>
                    <p class=\"small text-muted\">This action was requested from ip address {remoteaddr}.</p>", "en_EN"),
                ("user", "assign_password_mail", "Benutzerkonto angelegt", "<p>Hallo <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>es wurde ein Benutzerkonto auf <i>{sitetitle}</i> angelegt.
                    <p>Um Ihr Benutzerkonto zu aktivieren und ein Passwort zu vergeben klicken Sie Bitte auf folgenden Link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                    <p>Administrator</p>", "de_DE"),
                ("user", "assign_password_mail", "New Password", "<p>Hello <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>a user account at <i>{sitetitle}</i> has been created.
                    <p>To activate your user account and assign a password, please click at the following link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Best regards</p>
                    <p>Administrator</p>", "en_EN");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "2.1.1":
                $this->db()->query('INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                ("user", "assign_password_mail", "Benutzerkonto angelegt", "<p>Hallo <b>{name}</b>,</p>
                      <p>&nbsp;</p>
                      <p>es wurde ein Benutzerkonto auf <i>{sitetitle}</i> angelegt.
                      <p>Um Ihr Benutzerkonto zu aktivieren und ein Passwort zu vergeben klicken Sie Bitte auf folgenden Link.</p>
                      <p>{confirm}</p>
                      <p>&nbsp;</p>
                      <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                      <p>Administrator</p>", "de_DE"),
                ("user", "assign_password_mail", "New Password", "<p>Hello <b>{name}</b>,</p>
                      <p>&nbsp;</p>
                      <p>a user account at <i>{sitetitle}</i> has been created.
                      <p>To activate your user account and assign a password, please click at the following link.</p>
                      <p>{confirm}</p>
                      <p>&nbsp;</p>
                      <p>Best regards</p>
                      <p>Administrator</p>", "en_EN");');

                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('regist_setfree', '0');
                break;
            case "2.1.15":
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `homepage`;');
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `facebook`;');
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `twitter`;');
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `google`;');
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `steam`;');
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `twitch`;');
                $this->db()->query('ALTER TABLE `[prefix]_users` DROP COLUMN `teamspeak`;');
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` CHANGE `name` `key` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` ADD COLUMN `icon` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `type`;');
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` ADD COLUMN `addition` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `icon`;');
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` ADD COLUMN `show` TINYINT(1) NOT NULL DEFAULT 1 AFTER `icon`;');
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` ADD COLUMN `hidden` TINYINT(1) NOT NULL DEFAULT 0 AFTER `show`;');
                break;
            case "2.1.17":
                $this->db()->query('INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                ("user", "password_change_fail_mail", "Passwort ändern fehlgeschlagen", "<p>Hallo,</p>
                      <p>&nbsp;</p>
                      <p>Sie oder jemand anderes hat versucht Ihr Passwort zu ändern, aber es wurde kein Benutzer mit dieser E-Mail-Adresse auf {siteurl} gefunden.</p>
                      <p>Wenn Sie auf dieser Seite registriert sind und diese E-Mail erwartet haben, probieren Sie es bitte erneut mit
                      der E-Mail Adresse mit der Sie sich registriert haben.</p>
                      <p>Wenn Sie nicht auf dieser Seite registriert sind, ignorieren Sie diese E-Mail bitte.</p>
                      <p>&nbsp;</p>
                      <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                      <p>Administrator</p>
                      <p class=\"small text-muted\">Diese Aktion wurde angefordert von der IP-Adresse {remoteaddr}.</p>", "de_DE"),
                ("user", "password_change_fail_mail", "Password reset failed", "<p>Hello,</p>
                      <p>&nbsp;</p>
                      <p>You or someone else tried to change your password at {siteurl}, but no user was found with your email address.</p>
                      <p>If you are registered at this site and were expecting this email, please try again using the email address
                      you gave when registering.</p>
                      <p>If you are not registered at this site, please ignore this email.</p>
                      <p>&nbsp;</p>
                      <p>Best regards</p>
                      <p>Administrator</p>
                      <p class=\"small text-muted\">This action was requested from ip address {remoteaddr}.</p>", "en_EN");');
                break;
            case "2.1.19":
                // Add position column and new menu to user_menu and set default order.
                $this->db()->query('ALTER TABLE `[prefix]_user_menu` ADD COLUMN `position` INT(11) NOT NULL DEFAULT 0;');
                $this->db()->query('INSERT INTO `[prefix]_user_menu` (`key`, `icon`, `position`) VALUES ("user/panel/friends", "fa-users", 4);');
                $this->db()->query('UPDATE `[prefix]_user_menu` SET `position` = 1 WHERE `key` = "user/panel/index";');
                $this->db()->query('UPDATE `[prefix]_user_menu` SET `position` = 2 WHERE `key` = "user/panel/dialog";');
                $this->db()->query('UPDATE `[prefix]_user_menu` SET `position` = 3 WHERE `key` = "user/panel/gallery";');
                $this->db()->query('UPDATE `[prefix]_user_menu` SET `position` = 5 WHERE `key` = "user/panel/settings";');

                // Add new table user_friends.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_user_friends` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `user_id` INT(11) NOT NULL,
                    `friend_user_id` INT(11) NOT NULL,
                    `approved` TINYINT(1) NOT NULL DEFAULT 2,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');
                break;
            case "2.1.20":
                // Correct wrong id in user_menu, which ended up wrong on an update, by truncating the table and readding the entries.
                $this->db()->truncate('[prefix]_user_menu');
                $this->db()->query('INSERT INTO `[prefix]_user_menu` (`id`, `key`, `icon`, `position`) VALUES
                (1, "user/panel/index", "fa-home", 1),
                (2, "user/panel/dialog", "fa-envelope", 2),
                (3, "user/panel/gallery", "fa-picture-o", 3),
                (4, "user/panel/friends", "fa-users", 4),
                (5, "user/panel/settings", "fa-cogs", 5);');
                break;
            case "2.1.21":
                // Add new table users_dialog_hidden
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_hidden` (
                    `c_id` INT(11) UNSIGNED NOT NULL,
                    `user_id` INT(11) UNSIGNED NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
                // SelectsDelete update
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `selectsdelete` DATETIME;');
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('userdeletetime', '5');
                break;
        }
    }
}


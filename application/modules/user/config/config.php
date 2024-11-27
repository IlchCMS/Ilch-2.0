<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Config;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Service\Password as PasswordService;

class Config extends \Ilch\Config\Install
{
    const COMMENT_KEY_TPL = 'user/profil/index/user/%d';

    public $config = [
        'key' => 'user',
        'icon_small' => 'fa-solid fa-user',
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
            ->set('userdeletetime', '5')
            ->set('userGroupList_allowed', '0')
            ->set('userAvatarList_allowed', '0')
            ->set('user_commentsOnProfiles', '1');

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

    public function getInstallSql(): string
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
                `opt_comments` TINYINT(1) DEFAULT 1,
                `admin_comments` TINYINT(1) DEFAULT 1,
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
                `user_id` INT(11) UNSIGNED NOT NULL,
                `group_id` INT(11) NOT NULL,
                INDEX `FK_[prefix]_users_groups_[prefix]_users` (`user_id`) USING BTREE,
                INDEX `FK_[prefix]_users_groups_[prefix]_groups` (`group_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_groups_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_users_groups_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
                `group_id` INT(11) NOT NULL,
                `page_id` INT(11) DEFAULT 0,
                `module_key` VARCHAR(191) DEFAULT 0,
                `article_id` INT(11) DEFAULT 0,
                `box_id` INT(11) DEFAULT 0,
                `access_level` INT(11) DEFAULT 0,
                PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_groups_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_profile_fields` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `key` VARCHAR(255) NOT NULL,
                `type` TINYINT(1) NOT NULL,
                `icon` VARCHAR(255) NOT NULL DEFAULT \'\',
                `addition` VARCHAR(255) NOT NULL DEFAULT \'\',
                `options` TEXT NOT NULL DEFAULT \'\',
                `show` TINYINT(1) NOT NULL DEFAULT 1,
                `hidden` TINYINT(1) NOT NULL DEFAULT 0,
                `registration` TINYINT(1) NOT NULL DEFAULT 0,
                `position` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_profile_content` (
                `field_id` INT(11) UNSIGNED NOT NULL,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `value` VARCHAR(255) NOT NULL,
                INDEX `FK_[prefix]_profile_content_[prefix]_profile_fields` (`field_id`) USING BTREE,
                INDEX `FK_[prefix]_profile_content_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_profile_content_[prefix]_profile_fields` FOREIGN KEY (`field_id`) REFERENCES `[prefix]_profile_fields` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_profile_content_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
                `field_id` INT(11) UNSIGNED NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                INDEX `FK_[prefix]_profile_trans_[prefix]_profile_fields` (`field_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_profile_trans_[prefix]_profile_fields` FOREIGN KEY (`field_id`) REFERENCES `[prefix]_profile_fields` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_user_friends` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `friend_user_id` INT(11) UNSIGNED NOT NULL,
                `approved` TINYINT(1) NOT NULL DEFAULT 2,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_user_friends_[prefix]_users` (`user_id`) USING BTREE,
                INDEX `FK_[prefix]_user_friends_[prefix]_users_2` (`friend_user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_user_friends_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_user_friends_[prefix]_users_2` FOREIGN KEY (`friend_user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
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
                PRIMARY KEY (`c_id`) USING BTREE,
                INDEX `FK_[prefix]_users_dialog_[prefix]_users` (`user_one`) USING BTREE,
                INDEX `FK_[prefix]_users_dialog_[prefix]_users_2` (`user_two`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_dialog_[prefix]_users` FOREIGN KEY (`user_one`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_users_dialog_[prefix]_users_2` FOREIGN KEY (`user_two`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_hidden` (
                `c_id` INT(11) UNSIGNED NOT NULL,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `permanent` TINYINT(1) UNSIGNED NOT NULL,
                INDEX `FK_[prefix]_users_dialog_hidden_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_dialog_hidden_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_reply` (
                `cr_id` INT(11) NOT NULL AUTO_INCREMENT,
                `reply` TEXT,
                `user_id_fk` INT(11) UNSIGNED NOT NULL,
                `c_id_fk` INT(11) NOT NULL,
                `time` DATETIME NOT NULL,
                `read` TINYINT(1) DEFAULT 0,
                PRIMARY KEY (`cr_id`) USING BTREE,
                INDEX `FK_[prefix]_users_dialog_reply_[prefix]_users` (`user_id_fk`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_dialog_reply_[prefix]_users` FOREIGN KEY (`user_id_fk`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_media` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `name` VARCHAR(50) NOT NULL DEFAULT 0,
                `url` VARCHAR(150) NOT NULL DEFAULT 0,
                `url_thumb` VARCHAR(150) NOT NULL DEFAULT 0,
                `ending` VARCHAR(5) NOT NULL DEFAULT 0,
                `datetime` DATETIME NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_users_media_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_media_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_imgs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `image_id` VARCHAR(150)NOT NULL,
                `image_title` VARCHAR(255) NOT NULL DEFAULT \'\',
                `image_description` VARCHAR(255) NOT NULL DEFAULT \'\',
                `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                `visits` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_users_gallery_imgs_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_gallery_imgs_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `sort` INT(11) NOT NULL DEFAULT 0,
                `parent_id` INT(11) NOT NULL DEFAULT 0,
                `type` TINYINT(1) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_users_gallery_items_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_gallery_items_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_auth_tokens` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `selector` CHAR(12),
                `token` CHAR(64),
                `userid` INT(11) UNSIGNED NOT NULL,
                `expires` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_auth_tokens_[prefix]_users` (`userid`) USING BTREE,
                CONSTRAINT `FK_[prefix]_auth_tokens_[prefix]_users` FOREIGN KEY (`userid`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
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
                `user_id` INT(11) UNSIGNED NOT NULL,
                `provider` VARCHAR(50) NOT NULL,
                `identifier` VARCHAR(255) NOT NULL,
                `screen_name` VARCHAR(255) DEFAULT NULL,
                `oauth_token` VARCHAR(255) DEFAULT NULL,
                `oauth_token_secret` VARCHAR(255) DEFAULT NULL,
                `created_at` VARCHAR(45) DEFAULT NULL,
                PRIMARY KEY (`user_id`, `provider`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_auth_providers_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_notifications` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `module` VARCHAR(191) NOT NULL,
                `message` VARCHAR(255) NOT NULL,
                `url` VARCHAR(255) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_users_notifications_[prefix]_users` (`user_id`) USING BTREE,
                INDEX `FK_[prefix]_users_notifications_[prefix]_modules` (`module`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_notifications_[prefix]_modules` FOREIGN KEY (`module`) REFERENCES `[prefix]_modules` (`key`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_users_notifications_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_notifications_permission` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `module` VARCHAR(191) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                `granted` TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_users_notifications_permission_[prefix]_users` (`user_id`) USING BTREE,
                INDEX `FK_[prefix]_users_notifications_permission_[prefix]_modules` (`module`) USING BTREE,
                CONSTRAINT `FK_[prefix]_users_notifications_permission_[prefix]_modules` FOREIGN KEY (`module`) REFERENCES `[prefix]_modules` (`key`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_users_notifications_permission_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                (1, "Administrator"),
                (2, "User"),
                (3, "Guest");

            INSERT INTO `[prefix]_profile_fields` (`id`, `key`, `type`, `icon`, `addition`, `options`, `show`, `position`) VALUES
                (1, "website", 2, "fa-solid fa-globe", "", "", 1, 0),
                (2, "facebook", 2, "fa-brands fa-facebook", "https://www.facebook.com/", "", 1, 1),
                (3, "x", 2, "fa-brands fa-x-twitter", "https://x.com/", "", 1, 2),
                (4, "google+", 2, "fa-brands fa-google-plus", "https://plus.google.com/", "", 1, 3),
                (5, "steam", 2, "fa-brands fa-steam-square", "https://steamcommunity.com/id/", "", 1, 4),
                (6, "twitch", 2, "fa-brands fa-twitch", "https://www.twitch.tv/", "", 1, 5),
                (7, "teamspeak", 2, "fa-solid fa-headphones", "ts3server://", "", 1, 6),
                (8, "discord", 2, "fa-brands fa-discord", "https://discord.gg/", "", 1, 7);

            INSERT INTO `[prefix]_profile_trans` (`field_id`, `locale`, `name`) VALUES
                (1, "de_DE", "Webseite"),
                (1, "en_EN", "Website"),
                (2, "de_DE", "Facebook"),
                (2, "en_EN", "Facebook"),
                (3, "de_DE", "X"),
                (3, "en_EN", "X"),
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
                (1, "user/panel/index", "fa-solid fa-house", 1),
                (2, "user/panel/dialog", "fa-solid fa-envelope", 2),
                (3, "user/panel/gallery", "fa-regular fa-image", 3),
                (4, "user/panel/friends", "fa-solid fa-users", 4),
                (5, "user/panel/settings", "fa-solid fa-gears", 5);

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

    public function getUpdate(string $installedVersion): string
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
            case "2.1.42":
                // Add new 'permanent' column to users_dialog_hidden
                $this->db()->query('ALTER TABLE `[prefix]_users_dialog_hidden` ADD COLUMN `permanent` TINYINT(1) UNSIGNED NOT NULL;');
                break;
            case "2.1.43":
                // Convert old icons to new format
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fas fa-globe' WHERE `icon` = 'fa-globe';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fab fa-facebook' WHERE `icon` = 'fa-facebook';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fab fa-twitter' WHERE `icon` = 'fa-twitter';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fab fa-google-plus-g' WHERE `icon` = 'fa-google-plus';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fab fa-steam-square' WHERE `icon` = 'fa-steam-square';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fab fa-twitch' WHERE `icon` = 'fa-twitch';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fas fa-headphones' WHERE `icon` = 'fa-headphones';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fas fa-microphone' WHERE `icon` = 'fa-microphone';");

                // Restore deleted dialogs with unread messages
                $deletedDialogs = $this->db()->select('DISTINCT h.c_id')
                    ->from(['h' => 'users_dialog_hidden'])
                    ->join(['r' => 'users_dialog_reply'], 'h.c_id = r.c_id_fk', 'INNER')
                    ->where(['h.permanent' => 1, 'r.read' => 0])
                    ->execute()
                    ->fetchList();

                if (!empty($deletedDialogs)) {
                    $this->db()->delete('users_dialog_hidden')
                        ->where(['c_id' => $deletedDialogs], 'or')
                        ->execute();
                }

                // Add a composite primary key to the users_dialog_hidden table
                $this->db()->query("ALTER TABLE `[prefix]_users_dialog_hidden` ADD PRIMARY KEY (`c_id`, `user_id`);");

                // Create new tables for the user notifications feature
                $this->db()->queryMulti("CREATE TABLE IF NOT EXISTS `[prefix]_users_notifications` (
                    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `user_id` INT(11) UNSIGNED NOT NULL,
                    `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `module` VARCHAR(191) NOT NULL,
                    `message` VARCHAR(255) NOT NULL,
                    `url` VARCHAR(255) NOT NULL,
                    `type` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`) USING BTREE,
                    INDEX `FK_[prefix]_users_notifications_[prefix]_users` (`user_id`) USING BTREE,
                    INDEX `FK_[prefix]_users_notifications_[prefix]_modules` (`module`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_users_notifications_[prefix]_modules` FOREIGN KEY (`module`) REFERENCES `[prefix]_modules` (`key`) ON UPDATE NO ACTION ON DELETE CASCADE,
                    CONSTRAINT `FK_[prefix]_users_notifications_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_notifications_permission` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `user_id` INT(11) UNSIGNED NOT NULL,
                    `module` VARCHAR(191) NOT NULL,
                    `type` VARCHAR(255) NOT NULL,
                    `granted` TINYINT(1) NOT NULL DEFAULT 1,
                    PRIMARY KEY (`id`) USING BTREE,
                    INDEX `FK_[prefix]_users_notifications_permission_[prefix]_users` (`user_id`) USING BTREE,
                    INDEX `FK_[prefix]_users_notifications_permission_[prefix]_modules` (`module`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_users_notifications_permission_[prefix]_modules` FOREIGN KEY (`module`) REFERENCES `[prefix]_modules` (`key`) ON UPDATE NO ACTION ON DELETE CASCADE,
                    CONSTRAINT `FK_[prefix]_users_notifications_permission_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('userGroupList_allowed', '0');
                break;
            case "2.1.44":
                break;
            case "2.1.45":
                $this->db()->queryMulti("
                SET FOREIGN_KEY_CHECKS = 0;
                ALTER TABLE `[prefix]_users_notifications` CHANGE `module` `module` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
                ALTER TABLE `[prefix]_users_notifications_permission` CHANGE `module` `module` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
                SET FOREIGN_KEY_CHECKS = 1;
                ");
                break;
            case "2.1.47":
                // convert icon to new icons
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-solid fa-globe' WHERE `icon` = 'fa-globe' OR `icon` = 'fas fa-globe';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-facebook' WHERE `icon` = 'fa-facebook' OR `icon` = 'fab fa-facebook';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-twitter' WHERE `icon` = 'fa-twitter' OR `icon` = 'fab fa-twitter';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-google-plus' WHERE `icon` = 'fa-google-plus' OR `icon` = 'fab fa-google-plus';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-steam-square' WHERE `icon` = 'fa-steam-square' OR `icon` = 'fab fa-steam-square';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-twitch' WHERE `icon` = 'fa-twitch' OR `icon` = 'fab fa-twitch';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-solid fa-headphones' WHERE `icon` = 'fa-headphones' OR `icon` = 'fas fa-headphones';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-solid fa-microphone' WHERE `icon` = 'fas fa-microphone';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-discord' WHERE `icon` = 'fab fa-discord';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-discord' WHERE `icon` = 'fa-solid fa-microphone' AND `key` = 'discord';");
                $this->db()->query("UPDATE `[prefix]_profile_fields` SET `icon` = 'fa-brands fa-discord' WHERE `icon` = 'fa-solid fa-headphones' AND `key` = 'discord';");

                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-user' WHERE `key` = 'user';");
                break;
            case "2.1.49":
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('userAvatarList_allowed', '0');
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` ADD COLUMN `options` TEXT NOT NULL DEFAULT \'\' AFTER `addition`;');
                break;
            case "2.1.51":
                // Delete no longer used file. This got replaced by an updated and minified version called jquery.nicescroll.min.js
                unlink(ROOT_PATH . '/application/modules/user/static/js/jquery.nicescroll.js');

                // update icons in user_menu
                $this->db()->query("UPDATE `[prefix]_user_menu` SET `icon` = 'fa-solid fa-house' WHERE `icon` = 'fa-home';");
                $this->db()->query("UPDATE `[prefix]_user_menu` SET `icon` = 'fa-solid fa-envelope' WHERE `icon` = 'fa-envelope';");
                $this->db()->query("UPDATE `[prefix]_user_menu` SET `icon` = 'fa-regular fa-image' WHERE `icon` = 'fa-picture-o';");
                $this->db()->query("UPDATE `[prefix]_user_menu` SET `icon` = 'fa-solid fa-users' WHERE `icon` = 'fa-users';");
                $this->db()->query("UPDATE `[prefix]_user_menu` SET `icon` = 'fa-solid fa-gears' WHERE `icon` = 'fa-cogs';");

                // Rename twitter to X, update url, icon and translation if there isn't already an entry with the key x.
                $exists = (bool)$this->db()->select('key')
                    ->from('profile_fields')
                    ->where(['key' => 'x'])
                    ->execute()
                    ->fetchCell();

                if (!$exists) {
                    $updated = $this->db()->update()
                        ->table('profile_fields')
                        ->values(['icon' => 'fa-brands fa-x-twitter', 'key' => 'x', 'addition' => 'https://x.com/'])
                        ->where(['id' => 3, 'key' => 'twitter'])
                        ->execute();

                    if ($updated) {
                        // Update the translation if the row with the id 3 in profile_fields appear to be twitter.
                        $this->db()->update()
                            ->table('profile_trans')
                            ->values(['name' => 'X'])
                            ->where(['field_id' => 3])
                            ->execute();
                    }
                }
                break;
            case "2.2.3":
                // Add new registration column.
                $this->db()->query('ALTER TABLE `[prefix]_profile_fields` ADD COLUMN `registration` TINYINT(1) NOT NULL DEFAULT 0 AFTER `hidden`;');
                break;
            case "2.2.4":
                $fileConfig = new \Ilch\Config\File();
                $fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');
                $dbname = $fileConfig->get('dbName');

                // Delete orphaned rows in profile_content and profile_trans before adjusting the columns and adding FKCs.
                // Delete rows in profile_content with a field_id for a field that no longer exists.
                $idsProfileFields = $this->db()->select('id')
                    ->from('profile_fields')
                    ->execute()
                    ->fetchList();

                $idsProfileContent = $this->db()->select('field_id')
                    ->from('profile_content')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($idsProfileContent ?? [], $idsProfileFields ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('profile_content')
                        ->where(['field_id' => $orphanedRows])
                        ->execute();
                }

                // Delete rows in profile_content with a user_id for an user that no longer exists.
                $idsUsers = $this->db()->select('id')
                    ->from('users')
                    ->execute()
                    ->fetchList();

                $idsProfileContent = $this->db()->select('user_id')
                    ->from('profile_content')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($idsProfileContent ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('profile_content')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                // Delete rows in profile_trans with a field_id for a field that no longer exists.
                $idsProfileTrans = $this->db()->select('field_id')
                    ->from('profile_trans')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($idsProfileTrans ?? [], $idsProfileFields ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('profile_trans')
                        ->where(['field_id' => $orphanedRows])
                        ->execute();
                }

                // Change column types and add FKCs.
                $this->db()->queryMulti('ALTER TABLE `[prefix]_profile_content` MODIFY COLUMN `field_id` INT(11) UNSIGNED NOT NULL;
                        ALTER TABLE `[prefix]_profile_content` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;
                        ALTER TABLE `[prefix]_profile_trans` MODIFY COLUMN `field_id` INT(11) UNSIGNED NOT NULL;');
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_profile_content' AND constraint_name='FK_[prefix]_profile_content_[prefix]_profile_fields');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_profile_content` ADD CONSTRAINT `FK_[prefix]_profile_content_[prefix]_profile_fields` FOREIGN KEY (`field_id`) REFERENCES `[prefix]_profile_fields` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_profile_content' AND constraint_name='FK_[prefix]_profile_content_[prefix]_users');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_profile_content` ADD CONSTRAINT `FK_[prefix]_profile_content_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_profile_trans' AND constraint_name='FK_[prefix]_profile_trans_[prefix]_profile_fields');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_profile_trans` ADD CONSTRAINT `FK_[prefix]_profile_trans_[prefix]_profile_fields` FOREIGN KEY (`field_id`) REFERENCES `[prefix]_profile_fields` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // Add FKCs for users_groups, users_gallery_imgs, users_gallery_items, users_media, auth_tokens, visits_online, users_friends, users_dialog, users_dialog_reply and users_dialog_hidden.
                // Delete orphaned rows in users_groups.
                $idsGroups = $this->db()->select('id')
                    ->from('groups')
                    ->execute()
                    ->fetchList();

                $userIdsUserGroups = $this->db()->select('user_id')
                    ->from('users_groups')
                    ->execute()
                    ->fetchList();

                $groupIdsUserGroups = $this->db()->select('group_id')
                    ->from('users_groups')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userIdsUserGroups ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_groups')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($groupIdsUserGroups ?? [], $idsGroups ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_groups')
                        ->where(['group_id' => $orphanedRows])
                        ->execute();
                }

                // Find users with missing user_groups and assign them to the default user group.
                $userIdsUserGroups = $this->db()->select('user_id')
                    ->from('users_groups')
                    ->execute()
                    ->fetchList();

                $usersWithoutAssignedGroup = array_diff($idsUsers ?? [], $userIdsUserGroups ?? []);
                if (count($usersWithoutAssignedGroup) > 0) {
                    $neededRows = [];
                    foreach ($usersWithoutAssignedGroup as $userWithoutAssignedGroup) {
                        $neededRows[] = [$userWithoutAssignedGroup, 2];
                    }

                    $neededRows = array_chunk($neededRows, 25);
                    foreach($neededRows as $neededRowsChunk) {
                        $this->db()->insert('users_groups')
                            ->columns(['user_id', 'group_id'])
                            ->values($neededRowsChunk)
                            ->execute();
                    }
                }

                // Delete orphaned rows in auth_tokens.
                $userIdsAuthTokens = $this->db()->select('userid')
                    ->from('auth_tokens')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userIdsAuthTokens ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('auth_tokens')
                        ->where(['userid' => $orphanedRows])
                        ->execute();
                }

                // Delete orphaned rows in user_friends.
                $userIdsUserFriends = $this->db()->select('user_id')
                    ->from('user_friends')
                    ->execute()
                    ->fetchList();

                $userIdsFriendsUserFriends = $this->db()->select('friend_user_id')
                    ->from('user_friends')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userIdsUserFriends ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('user_friends')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($userIdsFriendsUserFriends ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('user_friends')
                        ->where(['friend_user_id' => $orphanedRows])
                        ->execute();
                }

                // Delete orphaned rows in users_dialog, users_dialog_reply and users_dialog_hidden.
                $userOneUsersDialog = $this->db()->select('user_one')
                    ->from('users_dialog')
                    ->execute()
                    ->fetchList();

                $userTwoUsersDialog = $this->db()->select('user_two')
                    ->from('users_dialog')
                    ->execute()
                    ->fetchList();

                $userIdUsersDialogReply = $this->db()->select('user_id_fk')
                    ->from('users_dialog_reply')
                    ->execute()
                    ->fetchList();

                $userIdUsersDialogHidden = $this->db()->select('user_id')
                    ->from('users_dialog_hidden')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userOneUsersDialog ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_dialog')
                        ->where(['user_one' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($userTwoUsersDialog ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_dialog')
                        ->where(['user_two' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($userIdUsersDialogReply ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_dialog_reply')
                        ->where(['user_id_fk' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($userIdUsersDialogHidden ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_dialog_hidden')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                // Delete orphaned rows in users_gallery_imgs, users_gallery_items and users_media.
                $userIdUsersGalleryImgs = $this->db()->select('user_id')
                    ->from('users_gallery_imgs')
                    ->execute()
                    ->fetchList();

                $userIdUsersGalleryItems = $this->db()->select('user_id')
                    ->from('users_gallery_items')
                    ->execute()
                    ->fetchList();

                $userIdUsersMedia = $this->db()->select('user_id')
                    ->from('users_media')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userIdUsersGalleryImgs ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_gallery_imgs')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($userIdUsersGalleryItems ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_gallery_items')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                $orphanedRows = array_diff($userIdUsersMedia ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_media')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                // Delete orphaned rows in users_auth_providers.
                $userIdusersAuthProviders = $this->db()->select('user_id')
                    ->from('users_auth_providers')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userIdusersAuthProviders ?? [], $idsUsers ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('users_auth_providers')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                // Delete orphaned rows in groups_access.
                $groupIdsGroupsAccess = $this->db()->select('group_id')
                    ->from('groups_access')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($groupIdsGroupsAccess ?? [], $idsGroups ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('groups_access')
                        ->where(['group_id' => $orphanedRows])
                        ->execute();
                }

                // Change column types and add FKCs.
                // user_groups
                $this->db()->query('ALTER TABLE `[prefix]_users_groups` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;');
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_groups' AND constraint_name='FK_[prefix]_users_groups_[prefix]_users');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_users_groups` ADD CONSTRAINT `FK_[prefix]_users_groups_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_groups' AND constraint_name='FK_[prefix]_users_groups_[prefix]_groups');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_users_groups` ADD CONSTRAINT `FK_[prefix]_users_groups_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_groups' AND constraint_name='FK_[prefix]_groups_access_[prefix]_groups');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_users_groups` ADD CONSTRAINT `FK_[prefix]_groups_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // auth_tokens
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_auth_tokens' AND constraint_name='FK_[prefix]_auth_tokens_[prefix]_users');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_auth_tokens` ADD CONSTRAINT `FK_[prefix]_auth_tokens_[prefix]_users` FOREIGN KEY (`userid`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // user_friends
                $this->db()->queryMulti('ALTER TABLE `[prefix]_user_friends` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;
                        ALTER TABLE `[prefix]_user_friends` MODIFY COLUMN `friend_user_id` INT(11) UNSIGNED NOT NULL;');
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_user_friends' AND constraint_name='FK_[prefix]_user_friends_[prefix]_users');")) {
                    // user_friends
                    $this->db()->query('ALTER TABLE `[prefix]_user_friends` ADD CONSTRAINT `FK_[prefix]_user_friends_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_user_friends' AND constraint_name='FK_[prefix]_user_friends_[prefix]_users_2');")) {
                    // user_friends
                    $this->db()->query('ALTER TABLE `[prefix]_user_friends` ADD CONSTRAINT `FK_[prefix]_user_friends_[prefix]_users_2` FOREIGN KEY (`friend_user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // users_dialog, users_dialog_reply and users_dialog_hidden
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_dialog' AND constraint_name='FK_[prefix]_users_dialog_[prefix]_users');")) {
                    // users_dialog
                    $this->db()->query('ALTER TABLE `[prefix]_users_dialog` ADD CONSTRAINT `FK_[prefix]_users_dialog_[prefix]_users` FOREIGN KEY (`user_one`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_dialog' AND constraint_name='FK_[prefix]_users_dialog_[prefix]_users_2');")) {
                    // users_dialog
                    $this->db()->query('ALTER TABLE `[prefix]_users_dialog` ADD CONSTRAINT `FK_[prefix]_users_dialog_[prefix]_users_2` FOREIGN KEY (`user_two`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_dialog_reply' AND constraint_name='FK_[prefix]_users_dialog_reply_[prefix]_users');")) {
                    // users_dialog_reply
                    $this->db()->query('ALTER TABLE `[prefix]_users_dialog_reply` ADD CONSTRAINT `FK_[prefix]_users_dialog_reply_[prefix]_users` FOREIGN KEY (`user_id_fk`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_dialog_hidden' AND constraint_name='FK_[prefix]_users_dialog_hidden_[prefix]_users');")) {
                    // users_dialog_hidden
                    $this->db()->query('ALTER TABLE `[prefix]_users_dialog_hidden` ADD CONSTRAINT `FK_[prefix]_users_dialog_hidden_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // users_gallery_imgs, users_gallery_items and users_media
                $this->db()->queryMulti('ALTER TABLE `[prefix]_users_gallery_imgs` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;
                        ALTER TABLE `[prefix]_users_gallery_items` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;
                        ALTER TABLE `[prefix]_users_media` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;');
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_gallery_imgs' AND constraint_name='FK_[prefix]_users_gallery_imgs_[prefix]_users');")) {
                    // users_gallery_imgs
                    $this->db()->query('ALTER TABLE `[prefix]_users_gallery_imgs` ADD CONSTRAINT `FK_[prefix]_users_gallery_imgs_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_gallery_items' AND constraint_name='FK_[prefix]_users_gallery_items_[prefix]_users');")) {
                    // users_gallery_items
                    $this->db()->query('ALTER TABLE `[prefix]_users_gallery_items` ADD CONSTRAINT `FK_[prefix]_users_gallery_items_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_media' AND constraint_name='FK_[prefix]_users_media_[prefix]_users');")) {
                    // users_media
                    $this->db()->query('ALTER TABLE `[prefix]_users_media` ADD CONSTRAINT `FK_[prefix]_users_media_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // users_auth_providers
                $this->db()->query('ALTER TABLE `[prefix]_users_auth_providers` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;');
                if (!$this->db()->queryCell("SELECT EXISTS (SELECT 1 FROM information_schema.table_constraints WHERE table_schema='" . $dbname . "' AND table_name='[prefix]_users_auth_providers' AND constraint_name='FK_[prefix]_users_auth_providers_[prefix]_users');")) {
                    $this->db()->query('ALTER TABLE `[prefix]_users_auth_providers` ADD CONSTRAINT `FK_[prefix]_users_auth_providers_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                }

                // Add new opt_comments and admin_comments column to the users table.
                // opt_comments: Whether the user allows comments on the profile or not.
                // admin_comments: Whether the admin allows comments on the profile of this user or not.
                $this->db()->queryMulti('ALTER TABLE `[prefix]_users` ADD COLUMN `opt_comments` TINYINT(1) DEFAULT 1 AFTER `opt_mail`;
                        ALTER TABLE `[prefix]_users` ADD COLUMN `admin_comments` TINYINT(1) DEFAULT 1 AFTER `opt_comments`;');

                // Add new setting for comments on profiles (globally) and allow them by default.
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('user_commentsOnProfiles', '1');
                break;
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

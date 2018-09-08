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
            ->set('usergallery_filetypes', 'jpg jpeg png gif');

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
                `name` varchar(191) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=2;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) NOT NULL,
                `password` varchar(191) NOT NULL,
                `email` varchar(191) NOT NULL,
                `first_name` varchar(191) NOT NULL DEFAULT "",
                `last_name` varchar(191) NOT NULL DEFAULT "",
                `gender` TINYINT(1) NOT NULL DEFAULT 0,
                `homepage` varchar(191) NOT NULL DEFAULT "",
                `facebook` varchar(191) NOT NULL DEFAULT "",
                `twitter` varchar(191) NOT NULL DEFAULT "",
                `google` varchar(191) NOT NULL DEFAULT "",
                `steam` varchar(191) NOT NULL DEFAULT "",
                `twitch` varchar(191) NOT NULL DEFAULT "",
                `teamspeak` varchar(191) NOT NULL DEFAULT "",
                `discord` varchar(191) NOT NULL DEFAULT "",
                `city` varchar(191) NOT NULL DEFAULT "",
                `birthday` DATE NULL DEFAULT NULL,
                `avatar` varchar(191) NOT NULL DEFAULT "",
                `signature` varchar(191) NOT NULL DEFAULT "",
                `locale` varchar(191) NOT NULL DEFAULT "",
                `opt_mail` TINYINT(1) DEFAULT 1,
                `opt_gallery` TINYINT(1) DEFAULT 1,
                `date_created` DATETIME NOT NULL,
                `date_confirmed` DATETIME NULL DEFAULT NULL,
                `date_last_activity` DATETIME NULL DEFAULT NULL,
                `confirmed` TINYINT(1) DEFAULT 1,
                `confirmed_code` varchar(191) NULL DEFAULT NULL,
                `selector` char(18),
                `locked` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_groups` (
                `user_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
                `group_id` INT(11) NOT NULL,
                `page_id` INT(11) DEFAULT 0,
                `module_key` varchar(191) DEFAULT 0,
                `article_id` INT(11) DEFAULT 0,
                `box_id` INT(11) DEFAULT 0,
                `access_level` INT(11) DEFAULT 0,
                PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_profile_content` (
                `user_id` INT(11) NOT NULL,
                `field_id` INT(11) NOT NULL,
                `value` varchar(191) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_profile_fields` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) NOT NULL,
                `type` TINYINT(1) NOT NULL,
                `position` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_user_menu` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `key` varchar(191) NOT NULL,
                `icon` varchar(191) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_user_menu_settings_links` (
                `key` varchar(191) NOT NULL,
                `locale` varchar(191) NOT NULL,
                `description` varchar(191) NOT NULL,
                `name` varchar(191) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog` (
                `c_id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_one` INT(11) UNSIGNED NOT NULL,
                `user_two` INT(11) UNSIGNED NOT NULL,
                `time` DATETIME NOT NULL,
                PRIMARY KEY (`c_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_reply` (
                `cr_id` INT(11) NOT NULL AUTO_INCREMENT,
                `reply` TEXT,
                `user_id_fk` INT(11) UNSIGNED NOT NULL,
                `c_id_fk` INT(11) NOT NULL,
                `time` DATETIME NOT NULL,
                `read` TINYINT(1) DEFAULT 0,
                PRIMARY KEY (`cr_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_media` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `name` VARCHAR(50) NOT NULL DEFAULT 0,
                `url` VARCHAR(150) NOT NULL DEFAULT 0,
                `url_thumb` VARCHAR(150) NOT NULL DEFAULT 0,
                `ending` VARCHAR(5) NOT NULL DEFAULT 0,
                `datetime` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_imgs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `image_id` VARCHAR(150)NOT NULL,
                `image_title` varchar(191) NOT NULL DEFAULT \'\',
                `image_description` varchar(191) NOT NULL DEFAULT \'\',
                `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                `visits` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `sort` INT(11) NOT NULL DEFAULT 0,
                `parent_id` INT(11) NOT NULL DEFAULT 0,
                `type` TINYINT(1) NOT NULL,
                `title` varchar(191) NOT NULL,
                `description` varchar(191) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
                `field_id` INT(11) NOT NULL,
                `locale` varchar(191) NOT NULL,
                `name` varchar(191) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_auth_tokens` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `selector` CHAR(12),
                `token` CHAR(64),
                `userid` INT(11) UNSIGNED NOT NULL,
                `expires` DATETIME,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_cookie_stolen` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_auth_providers` (
                `key` VARCHAR(45) NOT NULL,
                `name` varchar(191) NOT NULL,
                `icon` varchar(191) DEFAULT NULL,
                `module` varchar(191) DEFAULT NULL,
                PRIMARY KEY (`key`),
                UNIQUE KEY `key_UNIQUE` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_auth_providers_modules` (
                `module` VARCHAR(50) NOT NULL,
                `provider` VARCHAR(50) NOT NULL,
                `auth_controller` varchar(191) DEFAULT NULL,
                `auth_action` varchar(191) DEFAULT NULL,
                `unlink_controller` varchar(191) DEFAULT NULL,
                `unlink_action` varchar(191) DEFAULT NULL,
                PRIMARY KEY (`module`, `provider`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_users_auth_providers` (
                `user_id` INT(11) NOT NULL,
                `provider` VARCHAR(50) NOT NULL,
                `identifier` varchar(191) NOT NULL,
                `screen_name` varchar(191) DEFAULT NULL,
                `oauth_token` varchar(191) DEFAULT NULL,
                `oauth_token_secret` varchar(191) DEFAULT NULL,
                `created_at` VARCHAR(45) DEFAULT NULL,
                PRIMARY KEY (`user_id`, `provider`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                (1, "Administrator"),
                (2, "User"),
                (3, "Guest");
            
            INSERT INTO `[prefix]_user_menu` (`id`, `key`, `icon`) VALUES
                (1, "user/panel/index", "fa-home"),
                (2, "user/panel/dialog", "fa-envelope"),
                (3, "user/panel/gallery", "fa-picture-o"),
                (4, "user/panel/settings", "fa-cogs");
            
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
                    <p>Administrator</p>", "de_DE"),
                ("user", "password_change_mail", "New Password", "<p>Hello <b>{name}</b>,</p>
                    <p>&nbsp;</p>
                    <p>to change the Password at <i>{sitetitle}</i> please click at the following link.</p>
                    <p>{confirm}</p>
                    <p>&nbsp;</p>
                    <p>Best regards</p>
                    <p>Administrator</p>", "en_EN"),
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
        }
    }
}


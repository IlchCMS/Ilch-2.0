<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Config;

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
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('regist_accept', '1');
        $databaseConfig->set('regist_confirm', '1');
        $databaseConfig->set('regist_rules', '<p>Die Registrierung ist völlig kostenlos</p>
                              <p>Die Betreiber der Seite übernehmen keine Haftung.</p>
                              <p>Bitte verhalten Sie sich angemessen und mit Respekt gegenüber den anderen Community Mitgliedern.</p>');
        $databaseConfig->set('regist_confirm_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>Willkommen auf <i>{sitetitle}</i>.</p>
                              <p>um die Registrierung erfolgreich abzuschlie&szlig;en klicken Sie Bitte auf folgenden Link.</p>
                              <p>{confirm}</p>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>');
        $databaseConfig->set('manually_confirm_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>Willkommen auf <i>{sitetitle}</i>.</p>
                              <p>hiermit wurde dein Account freigeschaltet.</p>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>');
        $databaseConfig->set('password_change_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>um Ihr Passwort auf <i>{sitetitle}</i> zu ändern klicken Sie Bitte auf folgenden Link.</p>
                              <p>{confirm}</p>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>');
        $databaseConfig->set('avatar_uploadpath', 'application/modules/user/static/upload/avatar/');
        $databaseConfig->set('avatar_height', '120');
        $databaseConfig->set('avatar_width', '120');
        $databaseConfig->set('avatar_size', '51200');
        $databaseConfig->set('avatar_filetypes', 'jpg jpeg png gif');
        $databaseConfig->set('usergallery_allowed', '1');
        $databaseConfig->set('usergallery_uploadpath', 'application/modules/user/static/upload/gallery/');
        $databaseConfig->set('usergallery_filetypes', 'jpg jpeg png gif');

        $userMapper = new \Modules\User\Mappers\User();
        $userModel = new \Modules\User\Models\User();
        $groupMapper = new \Modules\User\Mappers\Group();
        $dateCreated = new \Ilch\Date();
        $userModel->setName($_SESSION['install']['adminName']);
        $userModel->setPassword((new PasswordService())->hash($_SESSION['install']['adminPassword']));
        $userModel->setEmail($_SESSION['install']['adminEmail']);
        $userModel->addGroup($groupMapper->getGroupById(1));
        $userModel->addGroup($groupMapper->getGroupById(2));
        $userModel->setDateConfirmed($dateCreated);
        $userModel->setDateCreated($dateCreated);
        $userMapper->save($userModel);
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

                CREATE TABLE IF NOT EXISTS `[prefix]_users` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  `password` VARCHAR(255) NOT NULL,
                  `email` VARCHAR(255) NOT NULL,
                  `first_name` VARCHAR(255) NOT NULL DEFAULT "",
                  `last_name` VARCHAR(255) NOT NULL DEFAULT "",
                  `homepage` VARCHAR(255) NOT NULL DEFAULT "",
                  `facebook` VARCHAR(255) NOT NULL DEFAULT "",
                  `twitter` VARCHAR(255) NOT NULL DEFAULT "",
                  `google` VARCHAR(255) NOT NULL DEFAULT "",
                  `city` VARCHAR(255) NOT NULL DEFAULT "",
                  `birthday` DATE NULL DEFAULT NULL,
                  `avatar` VARCHAR(255) NOT NULL DEFAULT "",
                  `signature` VARCHAR(255) NOT NULL DEFAULT "",
                  `opt_gallery` INT(11) DEFAULT 1,
                  `date_created` DATETIME NOT NULL,
                  `date_confirmed` DATETIME NULL DEFAULT NULL,
                  `date_last_activity` DATETIME NULL DEFAULT NULL,
                  `confirmed` INT(11) DEFAULT 1,
                  `confirmed_code` VARCHAR(255) NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_groups` (
                  `user_id` INT(11) NOT NULL,
                  `group_id` INT(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
                  `group_id` INT(11) NOT NULL,
                  `page_id` INT(11) DEFAULT 0,
                  `module_key` VARCHAR(255) DEFAULT 0,
                  `article_id` INT(11) DEFAULT 0,
                  `box_id` INT(11) DEFAULT 0,
                  `access_level` INT(11) DEFAULT 0,
                  PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_profile_content` (
                  `user_id` INT(11) NOT NULL,
                  `field_id` INT(11) NOT NULL,
                  `value` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_profile_fields` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  `type` INT(11) NOT NULL,
                  `position` INT(11) UNSIGNED NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_user_menu` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `key` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_user_menu_settings_links` (
                  `key` VARCHAR(255) NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `description` VARCHAR(255) NOT NULL,
                  `name` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog` (
                  `c_id` INT(10) NOT NULL AUTO_INCREMENT,
                  `user_one` INT(10) UNSIGNED NOT NULL,
                  `user_two` INT(10) UNSIGNED NOT NULL,
                  `time` DATETIME NOT NULL,
                  PRIMARY KEY (`c_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_reply` (
                  `cr_id` INT(10) NOT NULL AUTO_INCREMENT,
                  `reply` TEXT,
                  `user_id_fk` INT(10) unsigned NOT NULL,
                  `c_id_fk` INT(10) NOT NULL,
                  `time` DATETIME NOT NULL,
                  `read` INT(11) DEFAULT 0,
                  PRIMARY KEY (`cr_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_media` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `name` VARCHAR(50) NOT NULL DEFAULT 0,
                  `url` VARCHAR(150) NOT NULL DEFAULT 0,
                  `url_thumb` VARCHAR(150) NOT NULL DEFAULT 0,
                  `ending` VARCHAR(5) NOT NULL DEFAULT 0,
                  `datetime` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_imgs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `image_id` VARCHAR(150)NOT NULL,
                  `image_title` VARCHAR(255) NOT NULL,
                  `image_description` VARCHAR(255) NOT NULL,
                  `cat` MEDIUMINT(9) NOT NULL DEFAULT 0,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `gallery_id` INT(11) NOT NULL,
                  `sort` INT(11) NOT NULL,
                  `parent_id` INT(11) NOT NULL,
                  `type` INT(11) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `description` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
                  `field_id` INT(11) NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `name` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_auth_tokens` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `selector` CHAR(12),
                  `token` CHAR(64),
                  `userid` INT(11) UNSIGNED NOT NULL,
                  `expires` DATETIME,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_cookie_stolen` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `userid` INT(11) UNSIGNED NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                (1, "Administrator"),(2, "User"),(3, "Guest");

                INSERT INTO `[prefix]_user_menu` (`id`, `key`) VALUES
                (1, "user/panel/index"),
                (2, "user/panel/dialog"),
                (3, "user/panel/gallery"),
                (4, "user/panel/settings");';
    }

    public function getUpdate()
    {

    }
}


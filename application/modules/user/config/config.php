<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Config;

use Modules\User\Service\Password as PasswordService;

class Config extends \Ilch\Config\Install
{
    public $config =
        [
        'key' => 'user',
        'icon_small' => 'fa-user',
        'system_module' => true,
        'languages' =>
            [
            'de_DE' =>
                [
                'name' => 'Benutzer',
                'description' => 'Hier können neue Benutzer erstellt werden.',
                ],
            'en_EN' =>
                [
                'name' => 'User',
                'description' => 'Here you can create the users.',
                ],
            ]
        ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $groupMapper = new \Modules\User\Mappers\Group();
        $adminGroup = $groupMapper->getGroupById(1);
        $usersGroup = $groupMapper->getGroupById(2);
        $userMapper = new \Modules\User\Mappers\User();
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
        $user = new \Modules\User\Models\User();
        $user->setName($_SESSION['install']['adminName']);
        $user->setPassword((new PasswordService())->hash($_SESSION['install']['adminPassword']));
        $user->setEmail($_SESSION['install']['adminEmail']);
        $user->addGroup($adminGroup);
        $user->addGroup($usersGroup);
        $dateCreated = new \Ilch\Date();
        $user->setDateConfirmed($dateCreated);
        $user->setDateCreated($dateCreated);
        $userMapper->save($user);
    }

    public function getInstallSql()
    {
        return <<<'SQL'
CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) CHARACTER SET utf8 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
(1, "Administrator"),(2, "User"),(3, "Guest");

CREATE TABLE IF NOT EXISTS `[prefix]_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) NOT NULL,
    `homepage` varchar(255) NOT NULL,
    `facebook` varchar(255) NOT NULL,
    `twitter` varchar(255) NOT NULL,
    `google` varchar(255) NOT NULL,
    `city` varchar(255) NOT NULL,
    `birthday` date NOT NULL,
    `avatar` varchar(255) NOT NULL,
    `signature` varchar(255) NOT NULL,
    `opt_mail` int(11) DEFAULT 1,
    `opt_gallery` int(11) DEFAULT 1,
    `date_created` datetime NOT NULL,
    `date_confirmed` datetime NOT NULL,
    `date_last_activity` datetime NOT NULL,
    `confirmed` int(11) DEFAULT 1,
    `confirmed_code` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_users_groups` (
    `user_id` int(11) NOT NULL,
    `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
    `group_id` int(11) NOT NULL,
    `page_id` int(11) DEFAULT 0,
    `module_key` varchar(255) NOT NULL,
    `article_id` int(11) DEFAULT 0,
    `box_id` int(11) DEFAULT 0,
    `access_level` int(11) DEFAULT 0,
    PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_profile_content` (
    `user_id` int(11) NOT NULL,
    `field_id` int(11) NOT NULL,
    `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_profile_fields` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_user_menu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `key` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_user_menu_settings_links` (
    `key` varchar(255) NOT NULL,
    `locale` varchar(255) NOT NULL,
    `description` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `[prefix]_user_menu` (`id`, `key`) VALUES
(1, "user/panel/index"),
(2, "user/panel/dialog"),
(3, "user/panel/gallery"),
(4, "user/panel/settings");

CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog` (
    `c_id` int(10) NOT NULL AUTO_INCREMENT,
    `user_one` int(10) unsigned NOT NULL,
    `user_two` int(10) unsigned NOT NULL,
    `time` datetime NOT NULL,
    PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_users_dialog_reply` (
    `cr_id` int(10) NOT NULL AUTO_INCREMENT,
    `reply` text,
    `user_id_fk` int(10) unsigned NOT NULL,
    `c_id_fk` int(10) NOT NULL,
    `time` datetime NOT NULL,
    `read` int(11) DEFAULT 0,
    PRIMARY KEY (`cr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_users_media` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `name` varchar(50) NOT NULL DEFAULT 0,
    `url` varchar(150) NOT NULL DEFAULT 0,
    `url_thumb` varchar(150) NOT NULL DEFAULT 0,
    `ending` varchar(5) NOT NULL DEFAULT 0,
    `datetime` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_imgs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `image_id` varchar(150)NOT NULL ,
    `image_title` varchar(255) NOT NULL,
    `image_description` varchar(255) NOT NULL,
    `cat` mediumint(9) NOT NULL DEFAULT 0,
    `visits` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_users_gallery_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `gallery_id` int(11) NOT NULL,
    `sort` int(11) NOT NULL,
    `parent_id` int(11) NOT NULL,
    `type` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
 
CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
    `field_id` int(11) NOT NULL,
    `locale` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_auth_tokens` (
    `id` integer(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `selector` char(12),
    `token` char(64),
    `userid` integer(11) UNSIGNED NOT NULL,
    `expires` datetime,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_cookie_stolen` (
    `id` integer(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `userid` integer(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SQL;
    }
}


<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'user',
        'icon_small' => 'user.png',
        'system_module' => true,
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Benutzer',
                'description' => 'Hier können neue Benutzer erstellt werden.',
            ),
            'en_EN' => array
            (
                'name' => 'User',
                'description' => 'Here you can create the users.',
            ),
        )
    );

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
                              <p>nDie Betreiber der Seite übernehmen keine Haftung.</p>
                              <p>Bitte verhalten Sie sich angemessen und mit Respekt gegenüber den anderen Community Mitgliedern.</p>');
        $databaseConfig->set('regist_confirm_mail', '<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>Willkommen auf <i>{sitetitle}</i>.</p>
                              <p>um die Registrierung erfolgreich abzuschlie&szlig;en klicken Sie Bitte auf folgenden Link.</p>
                              <p>{confirm}</p>
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
        $user = new \Modules\User\Models\User();
        $user->setName($_SESSION['install']['adminName']);
        $user->setPassword(crypt($_SESSION['install']['adminPassword']));
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
    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
(1, "Administrator"),(2, "User");

CREATE TABLE IF NOT EXISTS `[prefix]_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `homepage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `birthday` date NOT NULL,
    `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `signature` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `opt_mail` int(11) DEFAULT 1,
    `date_created` datetime NOT NULL,
    `date_confirmed` datetime NOT NULL,
    `date_last_activity` datetime NOT NULL,
    `confirmed` int(11) DEFAULT 1,
    `confirmed_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `[prefix]_users_groups` (
    `user_id` int(11) NOT NULL,
    `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
    `group_id` int(11) NOT NULL,
    `page_id` int(11) DEFAULT 0,
    `module_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `article_id` int(11) DEFAULT 0,
    `box_id` int(11) DEFAULT 0,
    `access_level` int(11) DEFAULT 0,
    PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_profile_content` (
    `user_id` int(11) NOT NULL,
    `field_id` int(11) NOT NULL,
    `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_profile_fields` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `type` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `[prefix]_user_menu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `key` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
                
INSERT INTO `[prefix]_user_menu` (`id`, `key`, `title`) VALUES
(1, "user/panel/index", "Panel"),
(2, "user/panel/dialog", "Dialog"),
(3, "user/panel/settings", "Einstellungen");

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
 
CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
    `field_id` int(11) NOT NULL,
    `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SQL;
    }
}


<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'user';
    public $author = 'Meyer Dominik';
    public $name = array
    (
        'en_EN' => 'User',
        'de_DE' => 'Benutzer',
    );
    public $description = array
    (
        'en_EN' => 'Usermanagment, here you can create user/groups.',
        'de_DE' => 'Benutzerverwaltung, hier können Benutzer/Gruppen erstellt werden.',
    );

    public $icon_small = 'user.png';
    public $isSystemModule = true;

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        $groupMapper = new \User\Mappers\Group();
        $adminGroup = $groupMapper->getGroupById(1);
        $usersGroup = $groupMapper->getGroupById(2);
        $userMapper = new \User\Mappers\User();
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('regist_accept', '1');
        $databaseConfig->set('regist_confirm', '1');
        $databaseConfig->set('regist_rules', 'Die Registrierung ist völlig Kostenlos.\r\nDie Betreiber der Seite übernehmen keine Haftung.\r\nBitte verhalten Sie sich angemessen und mit Respekt gegenüber den anderen Community Mitgliedern.');
        $user = new \User\Models\User();
        $user->setName($_SESSION['install']['adminName']);
        $user->setPassword(crypt($_SESSION['install']['adminPassword']));
        $user->setEmail($_SESSION['install']['adminEmail']);
        $user->addGroup($adminGroup);
        $user->addGroup($usersGroup);
        $dateCreated = new \Ilch\Date();
        $user->setDateCreated($dateCreated);
        $userMapper->save($user);
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

                INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                (1, "Administrator"),(2, "User");

                CREATE TABLE IF NOT EXISTS `[prefix]_users` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `date_created` datetime NOT NULL,
                  `date_confirmed` datetime NOT NULL,
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
                  `module_id` int(11) DEFAULT 0,
                  `article_id` int(11) DEFAULT 0,
                  `box_id` int(11) DEFAULT 0,
                  `access_level` int(11) DEFAULT 0,
                  PRIMARY KEY (`group_id`, `page_id`, `module_id`, `article_id`, `box_id`)
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

                CREATE TABLE IF NOT EXISTS `[prefix]_profile_trans` (
                  `field_id` int(11) NOT NULL,
                  `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}


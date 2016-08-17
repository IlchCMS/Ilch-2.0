<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'forum',
        'version' => '1.0',
        'icon_small' => 'fa-list',
        'author' => 'Stantin Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Forum',
                'description' => 'Hier kann das Forum verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Forum',
                'description' => 'Here you can manage the forum.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_forum_topics`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_forum_items`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_forum_posts`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_forum_topics` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` INT(11) NOT NULL,
                  `topic_title` VARCHAR(255) NOT NULL,
                  `topic_description` VARCHAR(255) NOT NULL,
                  `text` VARCHAR(255) NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  `creator_id` INT(10) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `forum_id` INT(11) NOT NULL,
                  `type` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_forum_posts` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` VARCHAR(150) NOT NULL,
                  `text` TEXT NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  `user_name` VARCHAR(255) NOT NULL,
                  `user_id` INT(10) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `forum_id` INT(11) NOT NULL,
                  `read` VARCHAR(225) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_forum_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `forum_id` INT(11) NOT NULL,
                  `sort` INT(11) NOT NULL DEFAULT 0,
                  `parent_id` INT(11) NOT NULL DEFAULT 0,
                  `type` INT(11) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `description` VARCHAR(255) NOT NULL,
                  `read_access` VARCHAR(255) NOT NULL,
                  `replay_access` VARCHAR(255) NOT NULL,
                  `create_access` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}


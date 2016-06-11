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
        'author' => 'Stantin Thomas',
        'icon_small' => 'fa-list',
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
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` int(11) NOT NULL,
                  `topic_title` varchar(255) NOT NULL,
                  `topic_description` varchar(255) NOT NULL,
                  `text` varchar(255) NOT NULL,
                  `visits` int(11) NOT NULL DEFAULT 0,
                  `creator_id` int(10) NOT NULL,
                  `date_created` datetime NOT NULL,
                  `forum_id` int(11) NOT NULL,
                  `type` int(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_forum_posts` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` varchar(150) NOT NULL,
                  `text` text NOT NULL,
                  `visits` int(11) NOT NULL DEFAULT 0,
                  `user_name` varchar(255) NOT NULL,
                  `user_id` int(10) NOT NULL,
                  `date_created` datetime NOT NULL,
                  `forum_id` int(11) NOT NULL,
                  `read` varchar(225) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_forum_items` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `forum_id` int(11) NOT NULL,
                  `sort` int(11) NOT NULL,
                  `parent_id` int(11) NOT NULL,
                  `type` int(11) NOT NULL,
                  `title` varchar(255) NOT NULL,
                  `description` varchar(255) NOT NULL,
                  `read_access` varchar(255) NOT NULL,
                  `replay_access` varchar(255) NOT NULL,
                  `create_access` varchar(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}


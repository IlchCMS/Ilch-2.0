<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Config;

class Config extends \Ilch\Config\Install
{
    const EVENT_SAVETOPIC_BEFORE = 'forum_saveTopic_before';
    const EVENT_SAVETOPIC_AFTER = 'forum_saveTopic_after';
    const EVENT_ADDTOPIC_AFTER = 'forum_addTopic_after';
    const EVENT_SAVEPOST_BEFORE = 'forum_savePost_before';
    const EVENT_SAVEPOST_AFTER = 'forum_savePost_after';
    const EVENT_ADDPOST_AFTER = 'forum_addPost_after';
    const EVENT_DELETEPOST_BEFORE = 'forum_deletePost_before';
    const EVENT_DELETEPOST_AFTER = 'forum_deletePost_after';
    const EVENT_DELETETOPIC_BEFORE = 'forum_deleteTopic_before';
    const EVENT_DELETETOPIC_AFTER = 'forum_deleteTopic_after';

    public $config = [
        'key' => 'forum',
        'version' => '1.1',
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
        ],
        'boxes' => [
            'forum' => [
                'de_DE' => [
                    'name' => 'Forum'
                ],
                'en_EN' => [
                    'name' => 'Forum'
                ]
            ]
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
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
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_forum_items` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `forum_id` INT(11) NOT NULL,
                  `sort` INT(11) NOT NULL DEFAULT 0,
                  `parent_id` INT(11) NOT NULL DEFAULT 0,
                  `type` TINYINT(1) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `description` VARCHAR(255) NOT NULL,
                  `prefix` VARCHAR(255) NOT NULL,
                  `read_access` VARCHAR(255) NOT NULL,
                  `replay_access` VARCHAR(255) NOT NULL,
                  `create_access` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_forum_topics` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` INT(11) NOT NULL,
                  `topic_prefix` INT(11) NOT NULL DEFAULT 0,
                  `topic_title` VARCHAR(255) NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  `creator_id` INT(10) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `forum_id` INT(11) NOT NULL,
                  `type` TINYINT(1) NOT NULL DEFAULT 0,
                  `status` TINYINT(1) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_forum_posts` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` VARCHAR(150) NOT NULL,
                  `text` TEXT NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  `user_id` INT(10) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `forum_id` INT(11) NOT NULL DEFAULT 0,
                  `read` VARCHAR(225) NOT NULL DEFAULT \'\',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_forum_items` (`id`, `forum_id`, `sort`, `parent_id`, `type`, `title`, `description`, `read_access`, `replay_access`, `create_access`) VALUES
                (1, 1, 0, 0, 0, "Meine Kategorie", "Meine erste Kategorie", "", "", ""),
                (2, 1, 10, 1, 1, "Mein Forum", "Mein erstes Forum", "2,3", 2, 2);

                INSERT INTO `[prefix]_forum_topics` (`id`, `topic_id`, `topic_title`, `creator_id`, `date_created`, `forum_id`) VALUES
                (1, 2, "Willkommen bei Ilch!", 0, NOW(), 2);

                INSERT INTO `[prefix]_forum_posts` (`id`, `topic_id`, `text`, `user_id`, `date_created`, `forum_id`) VALUES
                (1, 1, "Willkommen im Ilch 2.0 Forum!\n\nBei Fragen oder Probleme im [url=http://www.ilch.de/forum.html]Ilch Forum[/url] melden.\n\nViel Erfolg\nIlch", 0, NOW(), 2);';
    }

    public function getUpdate($installedVersion)
    {

    }
}


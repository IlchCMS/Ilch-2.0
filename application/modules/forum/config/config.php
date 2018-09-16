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
        'version' => '1.11',
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
        'ilchCore' => '2.1.11',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('forum_floodInterval', '0');
        $databaseConfig->set('forum_excludeFloodProtection', '1');
        $databaseConfig->set('forum_postVoting', '0');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_forum_topics`;
            DROP TABLE `[prefix]_forum_items`;
            DROP TABLE `[prefix]_forum_posts`;
            DROP TABLE `[prefix]_forum_ranks`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_forum_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sort` INT(11) NOT NULL DEFAULT 0,
                `parent_id` INT(11) NOT NULL DEFAULT 0,
                `type` TINYINT(1) NOT NULL,
                `title` VARCHAR(191) NOT NULL,
                `description` VARCHAR(191) NOT NULL,
                `prefix` VARCHAR(191) NOT NULL,
                `read_access` VARCHAR(191) NOT NULL,
                `replay_access` VARCHAR(191) NOT NULL,
                `create_access` VARCHAR(191) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_topics` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `topic_id` INT(11) NOT NULL,
                `topic_prefix` INT(11) NOT NULL DEFAULT 0,
                `topic_title` VARCHAR(191) NOT NULL,
                `visits` INT(11) NOT NULL DEFAULT 0,
                `creator_id` INT(10) NOT NULL,
                `date_created` DATETIME NOT NULL,
                `forum_id` INT(11) NOT NULL,
                `type` TINYINT(1) NOT NULL DEFAULT 0,
                `status` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_posts` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `topic_id` VARCHAR(150) NOT NULL,
                `text` TEXT NOT NULL,
                `visits` INT(11) NOT NULL DEFAULT 0,
                `votes` LONGTEXT NOT NULL,
                `user_id` INT(10) NOT NULL,
                `date_created` DATETIME NOT NULL,
                `forum_id` INT(11) NOT NULL DEFAULT 0,
                `read` VARCHAR(225) NOT NULL DEFAULT \'\',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_ranks` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` TEXT NOT NULL,
                `posts` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            INSERT INTO `[prefix]_forum_items` (`id`, `sort`, `parent_id`, `type`, `title`, `description`, `read_access`, `replay_access`, `create_access`) VALUES
                (1, 0, 0, 0, "Meine Kategorie", "Meine erste Kategorie", "", "", ""),
                (2, 10, 1, 1, "Mein Forum", "Mein erstes Forum", "2,3", 2, 2);

            INSERT INTO `[prefix]_forum_topics` (`id`, `topic_id`, `topic_title`, `creator_id`, `date_created`, `forum_id`) VALUES
                (1, 2, "Willkommen bei Ilch!", 0, NOW(), 2);

            INSERT INTO `[prefix]_forum_posts` (`id`, `topic_id`, `text`, `user_id`, `date_created`, `forum_id`) VALUES
                (1, 1, "Willkommen im Ilch 2.0 Forum!\n\nBei Fragen oder Probleme im [url=http://www.ilch.de/forum.html]Ilch Forum[/url] melden.<br /><br />Viel Erfolg<br />Ilch", 0, NOW(), 2);

            INSERT INTO `[prefix]_forum_ranks` (`id`, `title`, `posts`) VALUES
                (1, "Grünschnabel", 0),
                (2, "Jungspund", 25),
                (3, "Mitglied", 50),
                (4, "Eroberer", 75),
                (5, "Doppel-As", 150),
                (6, "Tripel-As", 250),
                (7, "Haudegen", 500),
                (8, "Routinier", 1000),
                (9, "König", 2000),
                (10, "Kaiser", 5000),
                (11, "Legende", 7000),
                (12, "Foren Gott", 10000);';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
            case "1.3":
                $this->db()->query('ALTER TABLE `[prefix]_forum_items` DROP COLUMN `forum_id`;');

                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_forum_ranks` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `title` TEXT NOT NULL,
                    `posts` INT(11) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                INSERT INTO `[prefix]_forum_ranks` (`id`, `title`, `posts`) VALUES
                    (1, "Grünschnabel", 0),
                    (2, "Jungspund", 25),
                    (3, "Mitglied", 50),
                    (4, "Eroberer", 75),
                    (5, "Doppel-As", 150),
                    (6, "Tripel-As", 250),
                    (7, "Haudegen", 500),
                    (8, "Routinier", 1000),
                    (9, "König", 2000),
                    (10, "Kaiser", 5000),
                    (11, "Legende", 7000),
                    (12, "Foren Gott", 10000);');
            case "1.4":
            case "1.5":
            case "1.6":
            case "1.7":
                $databaseConfig = new \Ilch\Config\Database($this->db());

                $databaseConfig->set('forum_postVoting', '0');
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` ADD COLUMN `votes` LONGTEXT NOT NULL AFTER `visits`;');
            case "1.8":
                if (!$this->db()->ifColumnExists('[prefix]_forum_posts', 'votes')) {
                    $this->db()->query('ALTER TABLE `[prefix]_forum_posts` ADD COLUMN `votes` LONGTEXT NOT NULL AFTER `visits`;');
                }
            case "1.9":
                $databaseConfig = new \Ilch\Config\Database($this->db());

                $databaseConfig->set('forum_floodInterval', '0');
                $databaseConfig->set('forum_excludeFloodProtection', '1');
            case "1.10":
                // Change VARCHAR length for new table character.
                $this->db()->query('ALTER TABLE `[prefix]_forum_items` MODIFY COLUMN `title` `description` `prefix` `read_access` `replay_access` `create_access` VARCHAR(191);');
                $this->db()->query('ALTER TABLE `[prefix]_forum_topics` MODIFY COLUMN `topic_title` VARCHAR(191);');
        }
    }
}


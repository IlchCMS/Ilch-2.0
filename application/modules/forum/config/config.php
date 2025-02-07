<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'forum',
        'version' => '1.35.5',
        'icon_small' => 'fa-solid fa-list',
        'author' => 'Stantin Thomas',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Forum',
                'description' => 'Das ab Werk mitgelieferte Forum-Modul mit vielen Funktionen eines Forums.',
            ],
            'en_EN' => [
                'name' => 'Forum',
                'description' => 'The by default supplied forum module with many functions of a forum.',
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
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('forum_floodInterval', '0');
        $databaseConfig->set('forum_excludeFloodProtection', '1');
        $databaseConfig->set('forum_postVoting', '0');
        $databaseConfig->set('forum_topicSubscription', '0');
        $databaseConfig->set('forum_reportingPosts', '1');
        $databaseConfig->set('forum_reportNotificationEMail', '0');
        $databaseConfig->set('forum_DESCPostorder', '0');

        // Add default appearance for admin group
        $appearance[1]['active'] = 'on';
        $appearance[1]['textcolor'] = '#000000';
        $appearance[1]['bold'] = 'on';
        $databaseConfig->set('forum_groupAppearance', json_encode($appearance));

        $defaultCss = '#forum .appearance1 {color: #000000;font-weight: bold;}';
        $filename = uniqid() . '.css';
        file_put_contents(APPLICATION_PATH . '/modules/forum/static/css/groupappearance/' . $filename, $defaultCss);
        $databaseConfig->set('forum_filenameGroupappearanceCSS', $filename);

        $this->db()->query('INSERT INTO `[prefix]_forum_groupranking` (`group_id`,`rank`) VALUES(1,0);');
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DROP TABLE `[prefix]_forum_topics_read` IF EXISTS;
            DROP TABLE `[prefix]_forum_read` IF EXISTS;
            DROP TABLE `[prefix]_forum_votes` IF EXISTS;
            DROP TABLE `[prefix]_forum_remember` IF EXISTS;
            DROP TABLE `[prefix]_forum_posts` IF EXISTS;
            DROP TABLE `[prefix]_forum_topicsubscription` IF EXISTS;
            DROP TABLE `[prefix]_forum_topics` IF EXISTS;
            DROP TABLE `[prefix]_forum_groupranking` IF EXISTS;
            DROP TABLE `[prefix]_forum_accesses` IF EXISTS;
            DROP TABLE `[prefix]_forum_prefixes_items` IF EXISTS;
            DROP TABLE `[prefix]_forum_prefixes` IF EXISTS;
            DROP TABLE `[prefix]_forum_items` IF EXISTS;
            DROP TABLE `[prefix]_forum_ranks` IF EXISTS;
            DROP TABLE `[prefix]_forum_reports` IF EXISTS;
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_floodInterval';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_excludeFloodProtection';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_postVoting';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_topicSubscription';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_groupAppearance';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_filenameGroupappearanceCSS';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_reportingPosts';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_reportNotificationEMail';
            DELETE FROM `[prefix]_config` WHERE `key` = 'forum_DESCPostorder';
            DELETE FROM `[prefix]_emails` WHERE `moduleKey` = 'forum';");
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_forum_groupranking` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `group_id` INT(11) NULL DEFAULT NULL,
                `rank` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sort` INT(11) NOT NULL DEFAULT 0,
                `parent_id` INT(11) NOT NULL DEFAULT 0,
                `type` TINYINT(1) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_prefixes` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `prefix` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_prefixes_items` (
                `item_id` INT(11) NOT NULL,
                `prefix_id` INT(11) NOT NULL,
                INDEX `FK_[prefix]_forum_prefixes_items_[prefix]_forum_items` (`item_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_prefixes_items_[prefix]_forum_prefixes` (`prefix_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_prefixes_items_[prefix]_forum_items` FOREIGN KEY (`item_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_prefixes_items_[prefix]_forum_prefixes` FOREIGN KEY (`prefix_id`) REFERENCES `[prefix]_forum_prefixes` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_accesses` (
                `item_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL,
                `access_type` TINYINT(1) NOT NULL,
                INDEX `FK_[prefix]_forum_items` (`item_id`) USING BTREE,
                INDEX `FK_[prefix]_groups` (`group_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_items` FOREIGN KEY (`item_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_topics` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `forum_id` INT(11) NOT NULL,
                `topic_prefix` INT(11) NOT NULL DEFAULT 0,
                `topic_title` VARCHAR(255) NOT NULL,
                `visits` INT(11) NOT NULL DEFAULT 0,
                `creator_id` INT(10) NOT NULL,
                `date_created` DATETIME NOT NULL,
                `type` TINYINT(1) NOT NULL DEFAULT 0,
                `status` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_forum_topics_[prefix]_forum_items` (`forum_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_topics_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_topicsubscription` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `topic_id` INT(11) NULL DEFAULT NULL,
                `user_id` INT(11) UNSIGNED NOT NULL,
                `last_notification` DATETIME NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_forum_topicsubscription_[prefix]_forum_topics` (`topic_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_topicsubscription_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_topicsubscription_[prefix]_forum_topics` FOREIGN KEY (`topic_id`) REFERENCES `[prefix]_forum_topics` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_topicsubscription_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_posts` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `topic_id` INT(11) NOT NULL,
                `forum_id` INT(11) NOT NULL DEFAULT 0,
                `text` MEDIUMTEXT NOT NULL,
                `user_id` INT(10) NOT NULL,
                `date_created` DATETIME NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_forum_posts_[prefix]_forum_topics` (`topic_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_posts_[prefix]_forum_items` (`forum_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_posts_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_posts_[prefix]_forum_topics` FOREIGN KEY (`topic_id`) REFERENCES `[prefix]_forum_topics` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_ranks` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` TEXT NOT NULL,
                `posts` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_remember` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `date` DATETIME NOT NULL,
                `post_id` INT(11) NOT NULL,
                `note` VARCHAR(255) NOT NULL DEFAULT \'\',
                `user_id` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `FK_[prefix]_forum_remember_[prefix]_forum_posts` (`post_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_remember_[prefix]_users` (`user_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_remember_[prefix]_forum_posts` FOREIGN KEY (`post_id`) REFERENCES `[prefix]_forum_posts` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_remember_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_reports` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `date` DATETIME NOT NULL,
                `post_id` INT(11) NOT NULL,
                `reason` INT(11) NOT NULL,
                `details` TEXT NOT NULL,
                `user_id` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_votes` (
                `post_id` INT(11) NOT NULL,
                `user_id` INT(11) UNSIGNED NOT NULL,
                INDEX `FK_[prefix]_forum_votes_[prefix]_forum_posts` (`post_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_votes_[prefix]_forum_posts` FOREIGN KEY (`post_id`) REFERENCES `[prefix]_forum_posts` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_topics_read` (
                `user_id` INT(11) UNSIGNED NOT NULL,
                `forum_id` INT(11) NOT NULL,
                `topic_id` INT(11) NOT NULL,
                `datetime` DATETIME NOT NULL,
                INDEX `FK_[prefix]_forum_topics_read_[prefix]_users` (`user_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_topics_read_[prefix]_forum_topics` (`topic_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_topics_read_[prefix]_forum_items` (`forum_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_topics_read_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_topics_read_[prefix]_forum_topics` FOREIGN KEY (`topic_id`) REFERENCES `[prefix]_forum_topics` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_topics_read_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_read` (
                `user_id` INT(11) UNSIGNED NOT NULL,
                `forum_id` INT(11) NOT NULL,
                `datetime` DATETIME NOT NULL,
                INDEX `FK_[prefix]_forum_read_[prefix]_users` (`user_id`) USING BTREE,
                INDEX `FK_[prefix]_forum_read_[prefix]_forum_items` (`forum_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_forum_read_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_forum_read_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            INSERT INTO `[prefix]_forum_items` (`id`, `sort`, `parent_id`, `type`, `title`, `description`) VALUES
                (1, 0, 0, 0, "Meine Kategorie", "Meine erste Kategorie"),
                (2, 10, 1, 1, "Mein Forum", "Mein erstes Forum");

            INSERT INTO `[prefix]_forum_topics` (`id`, `topic_title`, `creator_id`, `date_created`, `forum_id`) VALUES
                (1, "Willkommen bei Ilch!", 0, UTC_TIMESTAMP(), 2);

            INSERT INTO `[prefix]_forum_posts` (`id`, `topic_id`, `text`, `user_id`, `date_created`, `forum_id`) VALUES
                (1, 1, "Willkommen im Ilch 2 Forum!\n\nBei Fragen oder Probleme im <a target=\"_blank\" href=\"https://www.ilch.de/forum.html\" rel=\"noopener\">Ilch Forum</a> melden.

                        Viel Erfolg
                        Ilch", 0, UTC_TIMESTAMP(), 2);

            INSERT INTO `[prefix]_forum_ranks` (`id`, `title`, `posts`) VALUES
                (1, "Anfänger", 0),
                (2, "Stammgast", 50),
                (3, "Forum-Mitglied", 100),
                (4, "Forum-Elite", 150),
                (5, "Aufsteigender-Elite", 250),
                (6, "Forum-Legende", 500),
                (7, "Historiker", 1500),
                (8, "Aufsteigender-Historiker", 5000),
                (9, "Dichter", 10000);

            INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                ("forum", "topic_subscription_mail", "Neue Beiträge im abonnierten Thema", "<p>Hallo <b>{name}</b>,</p>
                      <p>&nbsp;</p>
                      <p>es sind neue Beiträge in einem Ihrer abonnierten Themen im Forum auf <i>{sitetitle}</i> geschrieben worden.
                      <p>Um direkt einen Blick auf die neuen Beiträge zu werfen, klicken Sie Bitte auf folgenden Link:</p>
                      <a href=\"{url}\">{topicTitle}</a>
                      <p>&nbsp;</p>
                      <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                      <p>Administrator</p>", "de_DE"),
                ("forum", "topic_subscription_mail", "New posts in your subscribed topic", "<p>Hello <b>{name}</b>,</p>
                      <p>&nbsp;</p>
                      <p>there are new posts in one of your subscribed topics in the forum at <i>{sitetitle}</i>.
                      <p>To take a look at the new posts, please click on the following link:</p>
                      <a href=\"{url}\">{topicTitle}</a>
                      <p>&nbsp;</p>
                      <p>Best regards</p>
                      <p>Administrator</p>", "en_EN"),
                ("forum", "post_reportedPost_mail", "Ein Beitrag wurde gemeldet", "<p>Hallo <b>{name}</b>,</p>
                      <p>&nbsp;</p>
                      <p>ein Beitrag im Forum auf <i>{sitetitle}</i> wurde gemeldet.</p>
                      <p>Sie bekommen diese E-Mail, weil Sie entweder Administrator oder Adminrechte für das Forum haben.</p>
                      <p>Um direkt einen Blick auf den betreffenden Beitrag zu werfen, klicken Sie bitte auf folgenden Link:</p>
                      <a href=\"{url}\">Verwalte gemeldete Beiträge</a>
                      <p>&nbsp;</p>
                      <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                      <p>Administrator</p>", "de_DE"),
                ("forum", "post_reportedPost_mail", "A post got reported.", "<p>Hello <b>{name}</b>,</p>
                      <p>&nbsp;</p>
                      <p>a post got reported in the forum at <i>{sitetitle}</i>.</p>
                      <p>You are receiving this email because you are an administrator or have admin rights for the forum.</p>
                      <p>To take a look at the relevant post, please click on the following link:</p>
                      <a href=\"{url}\">Manage reported posts</a>
                      <p>&nbsp;</p>
                      <p>Best regards</p>
                      <p>Administrator</p>", "en_EN");';
    }

    public function getUpdate(string $installedVersion): string
    {
        //Workaround to fix 1.1 and 1.10 being considered equal.
        if ($installedVersion == "1.10") {
            $installedVersion = "1.10.0";
        }

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
                (1, "Anfänger", 0),
                (2, "Stammgast", 50),
                (3, "Forum-Mitglied", 100),
                (4, "Forum-Elite", 150),
                (5, "Aufsteigender-Elite", 250),
                (6, "Forum-Legende", 500),
                (7, "Historiker", 1500),
                (8, "Aufsteigender-Historiker", 5000),
                (9, "Dichter", 10000);');
                // no break
            case "1.4":
            case "1.5":
            case "1.6":
            case "1.7":
                $databaseConfig = new \Ilch\Config\Database($this->db());

                $databaseConfig->set('forum_postVoting', '0');
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` ADD COLUMN `votes` LONGTEXT NOT NULL AFTER `visits`;');
                // no break
            case "1.8":
                if (!$this->db()->ifColumnExists('[prefix]_forum_posts', 'votes')) {
                    $this->db()->query('ALTER TABLE `[prefix]_forum_posts` ADD COLUMN `votes` LONGTEXT NOT NULL AFTER `visits`;');
                }
                // no break
            case "1.9":
                $databaseConfig = new \Ilch\Config\Database($this->db());

                $databaseConfig->set('forum_floodInterval', '0');
                $databaseConfig->set('forum_excludeFloodProtection', '1');
                // no break
            case "1.10.0":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_forum_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_topics` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_ranks` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');

                // Delete no longer needed file.
                unlink(ROOT_PATH . '/application/modules/forum/controllers/admin/Base.php');
                // no break
            case "1.11.0":
            case "1.12.0":
            case "1.13.0":
            case "1.14.0":
            case "1.15.0":
            case "1.16.0":
            case "1.17.0":
                // Add default appearance for admin group
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $appearance[1]['active'] = 'on';
                $appearance[1]['textcolor'] = '#000000';
                $appearance[1]['bold'] = 'on';
                $databaseConfig->set('forum_groupAppearance', serialize($appearance));

                $defaultCss = '#forum .appearance1 {color: #000000;font-weight: bold;}';
                $filename = uniqid() . '.css';
                file_put_contents(APPLICATION_PATH . '/modules/forum/static/css/groupappearance/' . $filename, $defaultCss);
                $databaseConfig->set('forum_filenameGroupappearanceCSS', $filename);

                // Add table for group ranking, which is needed when deciding which appearance needs to be applied.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_forum_groupranking` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `group_id` INT(11) NULL DEFAULT NULL,
                    `rank` INT(11) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');

                $this->db()->query('INSERT INTO `[prefix]_forum_groupranking` (group_id,rank) VALUES(1,0);');

                // Add table for topic subscriptions
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_forum_topicsubscription` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `topic_id` INT(11) NULL DEFAULT NULL,
                        `user_id` INT(11) NULL DEFAULT NULL,
                        `last_notification` DATETIME NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');

                // Add template for topic subscription email.
                $this->db()->query('INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                    ("forum", "topic_subscription_mail", "Neue Beiträge im abonnierten Thema", "<p>Hallo <b>{name}</b>,</p>
                          <p>&nbsp;</p>
                          <p>es sind neue Beiträge in einem Ihrer abonnierten Themen im Forum auf <i>{sitetitle}</i> geschrieben worden.
                          <p>Um direkt einen Blick auf die neuen Beiträge zu werfen, klicken Sie Bitte auf folgenden Link:</p>
                          <a href=\"{url}\">{topicTitle}</a>
                          <p>&nbsp;</p>
                          <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                          <p>Administrator</p>", "de_DE"),
                    ("forum", "topic_subscription_mail", "New posts in your subscribed topic", "<p>Hello <b>{name}</b>,</p>
                          <p>&nbsp;</p>
                          <p>there are new posts in one of your subscribed topics in the forum at <i>{sitetitle}</i>.
                          <p>To take a look at the new posts, please click on the following link:</p>
                          <a href=\"{url}\">{topicTitle}</a>
                          <p>&nbsp;</p>
                          <p>Best regards</p>
                          <p>Administrator</p>", "en_EN");');
                // no break
            case "1.18.0":
                // Add table for reported posts.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_forum_reports` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `date` DATETIME NOT NULL,
                        `post_id` INT(11) NOT NULL,
                        `reason` INT(11) NOT NULL,
                        `details` TEXT NOT NULL,
                        `user_id` INT(11) NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');

                // Add default settings for reporting of posts
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('forum_reportingPosts', '1');
                $databaseConfig->set('forum_reportNotificationEMail', '0');

                // Add template for the reported post email.
                $this->db()->query('INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                        ("forum", "post_reportedPost_mail", "Ein Beitrag wurde gemeldet", "<p>Hallo <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>ein Beitrag im Forum auf <i>{sitetitle}</i> wurde gemeldet.</p>
                              <p>Sie bekommen diese E-Mail, weil Sie entweder Administrator oder Adminrechte für das Forum haben.</p>
                              <p>Um direkt einen Blick auf den betreffenden Beitrag zu werfen, klicken Sie bitte auf folgenden Link:</p>
                              <a href=\"{url}\">Verwalte gemeldete Beiträge</a>
                              <p>&nbsp;</p>
                              <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                              <p>Administrator</p>", "de_DE"),
                        ("forum", "post_reportedPost_mail", "A post got reported.", "<p>Hello <b>{name}</b>,</p>
                              <p>&nbsp;</p>
                              <p>a post got reported in the forum at <i>{sitetitle}</i>.</p>
                              <p>You are receiving this email because you are an administrator or have admin rights for the forum.</p>
                              <p>To take a look at the relevant post, please click on the following link:</p>
                              <a href=\"{url}\">Manage reported posts</a>
                              <p>&nbsp;</p>
                              <p>Best regards</p>
                              <p>Administrator</p>", "en_EN");');

                // Create possibly missing "groupappearance"-directory and default CSS.
                if (!file_exists(APPLICATION_PATH . '/modules/forum/static/css/groupappearance')) {
                    mkdir(APPLICATION_PATH . '/modules/forum/static/css/groupappearance');

                    // Add default appearance for admin group
                    $databaseConfig = new \Ilch\Config\Database($this->db());
                    $appearance[1]['active'] = 'on';
                    $appearance[1]['textcolor'] = '#000000';
                    $appearance[1]['bold'] = 'on';
                    $databaseConfig->set('forum_groupAppearance', serialize($appearance));

                    $defaultCss = '#forum .appearance1 {color: #000000;font-weight: bold;}';
                    $filename = uniqid() . '.css';
                    file_put_contents(APPLICATION_PATH . '/modules/forum/static/css/groupappearance/' . $filename, $defaultCss);
                    $databaseConfig->set('forum_filenameGroupappearanceCSS', $filename);
                }
                // no break
            case "1.19.0":
            case "1.20.0":
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('forum_DESCPostorder', '0');
                // no break
            case "1.21.0":
                // convert forum_groupAppearance to new format
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $appearance = unserialize($databaseConfig->get('forum_groupAppearance'));
                $databaseConfig->set('forum_groupAppearance', json_encode($appearance));
                // no break
            case "1.22.0":
            case "1.23.0":
            case "1.24.0":
                // Add table for remembered posts.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_forum_remember` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `date` DATETIME NOT NULL,
                            `post_id` INT(11) NOT NULL,
                            `note` VARCHAR(255) NOT NULL DEFAULT \'\',
                            `user_id` INT(11) NOT NULL,
                            PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');

                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'forum' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.25.0":
            case "1.26.0":
            case "1.27.0":
            case "1.28.0":
            case "1.29.0":
            case "1.30.0":
            case "1.30.1":
            case "1.31.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-list' WHERE `key` = 'forum';");
                // no break
            case "1.32.0":
            case "1.33.0":
                // Create new table 'forum_votes' to keep track of what users voted for a post.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_forum_votes` (
                            `post_id` INT(11) NOT NULL,
                            `user_id` INT(11) UNSIGNED NOT NULL,
                            INDEX `FK_[prefix]_forum_votes_[prefix]_forum_posts` (`post_id`) USING BTREE,
                            CONSTRAINT `FK_[prefix]_forum_votes_[prefix]_forum_posts` FOREIGN KEY (`post_id`) REFERENCES `[prefix]_forum_posts` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Get all users that voted for each post.
                $votesRows = $this->db()->select(['id', 'votes'])
                    ->from(['forum_posts'])
                    ->where(['votes !=' => ''])
                    ->execute()
                    ->fetchList('votes', 'id');

                if (!empty($votesRows)) {
                    // Prepare rows for inserting them in chunks later. Remove potential duplicated user ids.
                    $rowsToImport = [];
                    foreach ($votesRows as $postId => $votes) {
                        $userIdsInVotes = array_unique(explode(',', $votes));

                        foreach ($userIdsInVotes as $userId) {
                            $rowsToImport[] = [$postId, $userId];
                        }
                    }

                    // Add votes in chunks of 25 rows to the 'forum_votes' table
                    $chunks = array_chunk($rowsToImport, 25);
                    foreach ($chunks as $chunk) {
                        $this->db()->insert('forum_votes')
                            ->columns(['post_id', 'user_id'])
                            ->values($chunk)
                            ->execute();
                    }
                }

                // Change type of column 'topic_id' of the table 'forum_posts' to INT from VARCHAR.
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` CHANGE COLUMN `topic_id` `topic_id` INT(11) NOT NULL;');

                // Create new table 'forum_accesses' to store the accesses rights for read, create and reply of a forum item.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_forum_accesses` (
                            `item_id` INT(11) NOT NULL,
                            `group_id` INT(11) NOT NULL,
                            `access_type` TINYINT(1) NOT NULL,
                            INDEX `FK_[prefix]_forum_items` (`item_id`) USING BTREE,
                            INDEX `FK_[prefix]_groups` (`group_id`) USING BTREE,
                            CONSTRAINT `FK_[prefix]_forum_items` FOREIGN KEY (`item_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                            CONSTRAINT `FK_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Get all current stored values for 'read_access', 'replay_access' and 'create_access' for each item.
                $currentAccesses = $this->db()->select(['id', 'read_access', 'replay_access', 'create_access'])
                    ->from('forum_items')
                    ->execute()
                    ->fetchRows('id');

                if (!empty($currentAccesses)) {
                    $readAccessesToImport = [];
                    $types = ['read_access' => 0, 'replay_access' => 1, 'create_access' => 2];
                    $columns = ['read_access', 'replay_access', 'create_access'];

                    // Get all existing group ids.
                    $existingGroupIds = $this->db()->select('id')
                        ->from('groups')
                        ->execute()
                        ->fetchList();

                    foreach ($currentAccesses as $item_id => $accesses) {
                        // Prepare rows for inserting them in chunks later. Remove potential duplicates and group ids of groups that no longer exist.
                        foreach ($columns as $column) {
                            $groupIds = array_intersect(array_unique(explode(',', $accesses[$column])), $existingGroupIds);

                            foreach ($groupIds as $groupId) {
                                $readAccessesToImport[] = [$item_id, $groupId, $types[$column]];
                            }
                        }
                    }

                    // Add votes in chunks of 25 rows to the 'forum_accesses' table
                    $chunks = array_chunk($readAccessesToImport, 25);
                    foreach ($chunks as $chunk) {
                        $this->db()->insert('forum_accesses')
                            ->columns(['item_id', 'group_id', 'access_type'])
                            ->values($chunk)
                            ->execute();
                    }
                }

                // Delete no longer needed columns 'read_access', 'replay_access' and 'create_access' of the table 'forum_items'.
                $this->db()->query('ALTER TABLE `[prefix]_forum_items` DROP COLUMN `read_access`, DROP COLUMN `replay_access`, DROP COLUMN `create_access`;');

                // Create new tables
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_forum_topics_read` (
                            `user_id` INT(11) UNSIGNED NOT NULL,
                            `forum_id` INT(11) NOT NULL,
                            `topic_id` INT(11) NOT NULL,
                            `datetime` DATETIME NOT NULL,
                            INDEX `FK_[prefix]_forum_topics_read_[prefix]_users` (`user_id`) USING BTREE,
                            INDEX `FK_[prefix]_forum_topics_read_[prefix]_forum_topics` (`topic_id`) USING BTREE,
                            INDEX `FK_[prefix]_forum_topics_read_[prefix]_forum_items` (`forum_id`) USING BTREE,
                            CONSTRAINT `FK_[prefix]_forum_topics_read_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                            CONSTRAINT `FK_[prefix]_forum_topics_read_[prefix]_forum_topics` FOREIGN KEY (`topic_id`) REFERENCES `[prefix]_forum_topics` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                            CONSTRAINT `FK_[prefix]_forum_topics_read_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                        CREATE TABLE IF NOT EXISTS `[prefix]_forum_read` (
                            `user_id` INT(11) UNSIGNED NOT NULL,
                            `forum_id` INT(11) NOT NULL,
                            `datetime` DATETIME NOT NULL,
                            INDEX `FK_[prefix]_forum_read_[prefix]_users` (`user_id`) USING BTREE,
                            INDEX `FK_[prefix]_forum_read_[prefix]_forum_items` (`forum_id`) USING BTREE,
                            CONSTRAINT `FK_[prefix]_forum_read_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                            CONSTRAINT `FK_[prefix]_forum_read_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Get the needed information of all posts.
                $readPosts = $this->db()->select(['id', 'topic_id', 'date_created', 'forum_id', 'read'])
                    ->from('forum_posts')
                    ->order(['id' => 'DESC'])
                    ->execute()
                    ->fetchRows();

                // Get all userIds
                $existingUserIds = $this->db()->select('id')
                    ->from('users')
                    ->execute()
                    ->fetchList();

                // Find the newest read post of a topic for a user.
                $readTopics = [];
                $toc = [];
                foreach ($readPosts as $post) {
                    foreach (array_intersect(array_unique(explode(',', $post['read'])), $existingUserIds) as $userId) {
                        // Table of content (toc) is used to check if there is already a value for the topic and user.
                        // In this case we already have the newest read post of the topic for the user.
                        if (!isset($toc[$post['topic_id']][$userId])) {
                            $toc[$post['topic_id']][$userId] = true;
                            $readTopics[] = [$userId, $post['forum_id'], $post['topic_id'], $post['date_created']];
                        }
                    }
                }

                // Add read topics in chunks of 25 rows to the 'forum_topics_read' table
                $chunks = array_chunk($readTopics, 25);
                foreach ($chunks as $chunk) {
                    $this->db()->insert('forum_topics_read')
                        ->columns(['user_id', 'forum_id', 'topic_id', 'datetime'])
                        ->values($chunk)
                        ->execute();
                }

                // Delete no longer needed column 'votes' of the table 'forum_posts'.
                // Delete not used column 'visits' of the table 'forum_posts'.
                // Delete no longer needed column 'read' of the table 'forum_posts'.
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` DROP COLUMN `votes`, DROP COLUMN `visits`, DROP COLUMN `read`;');

                // Delete wrong column 'topic_id' of the table 'forum_topics'
                $this->db()->query('ALTER TABLE `[prefix]_forum_topics` DROP COLUMN `topic_id`;');


                // Get ids of existing topics.
                $existingTopics = $this->db()->select('id')
                    ->from('forum_topics')
                    ->execute()
                    ->fetchList();

                // Add FKC for the 'topic_id' column in the 'forum_posts' table after deleting possibly orphaned posts.
                $posts = $this->db()->select('topic_id')
                    ->from('forum_posts')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($posts ?? [], $existingTopics ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_posts')
                        ->where(['topic_id' => $orphanedRows])
                        ->execute();
                }
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` ADD CONSTRAINT `FK_[prefix]_forum_posts_[prefix]_forum_topics` FOREIGN KEY (`topic_id`) REFERENCES `[prefix]_forum_topics` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');


                // Add FKC for the 'topic_id' and 'user_id' columns in the 'forum_topicsubscription' table after deleting possibly orphaned subscriptions.
                $subscriptions = $this->db()->select('topic_id')
                    ->from('forum_topicsubscription')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($subscriptions ?? [], $existingTopics ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_topicsubscription')
                        ->where(['topic_id' => $orphanedRows])
                        ->execute();
                }

                $subscriptions = $this->db()->select('user_id')
                    ->from('forum_topicsubscription')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($subscriptions ?? [], $existingUserIds ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_topicsubscription')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                // Change type of column 'user_id' of the table 'forum_topicsubscription' to match the column 'id' of the table 'users'.
                $this->db()->query('ALTER TABLE `[prefix]_forum_topicsubscription` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL;');

                $this->db()->query('ALTER TABLE `[prefix]_forum_topicsubscription` ADD CONSTRAINT `FK_[prefix]_forum_topicsubscription_[prefix]_forum_topics` FOREIGN KEY (`topic_id`) REFERENCES `[prefix]_forum_topics` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_topicsubscription` ADD CONSTRAINT `FK_[prefix]_forum_topicsubscription_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');


                // Add FKC for the 'topic_id' and 'user_id' columns in the 'forum_remember' table after deleting possibly orphaned remembered posts.
                $existingPosts = $this->db()->select('id')
                    ->from('forum_posts')
                    ->execute()
                    ->fetchList();

                $rememberedPosts = $this->db()->select('post_id')
                    ->from('forum_remember')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($rememberedPosts ?? [], $existingPosts ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_remember')
                        ->where(['post_id' => $orphanedRows])
                        ->execute();
                }

                $rememberedPostsUsers = $this->db()->select('user_id')
                    ->from('forum_remember')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($rememberedPostsUsers ?? [], $existingUserIds ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_remember')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                // Change type of column 'user_id' of the table 'forum_remember' to match the column 'id' of the table 'users'.
                $this->db()->query('ALTER TABLE `[prefix]_forum_remember` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL;');

                $this->db()->query('ALTER TABLE `[prefix]_forum_remember` ADD CONSTRAINT `FK_[prefix]_forum_remember_[prefix]_forum_posts` FOREIGN KEY (`post_id`) REFERENCES `[prefix]_forum_posts` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_remember` ADD CONSTRAINT `FK_[prefix]_forum_remember_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');

                // no break
            case "1.34.0":
                // Add FKCs for the 'forum_id' columns in the tables 'forum_posts' and 'forum_topics' after deleting possibly orphaned posts and topics.
                // Added additional FKCs and therefore indices to the posts and topics table to improve the performance of a query.
                $existingForums = $this->db()->select('id')
                    ->from('forum_items')
                    ->execute()
                    ->fetchList();

                $referencedForumsInPosts = $this->db()->select('forum_id')
                    ->from('forum_posts')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($referencedForumsInPosts ?? [], $existingForums ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_posts')
                        ->where(['forum_id' => $orphanedRows])
                        ->execute();
                }

                $referencedForumsInTopics = $this->db()->select('forum_id')
                    ->from('forum_topics')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($referencedForumsInTopics ?? [], $existingForums ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()
                        ->from('forum_topics')
                        ->where(['forum_id' => $orphanedRows])
                        ->execute();
                }

                // Change the order of column 'forum_id' of the tables 'forum_posts' and 'forum_topics'. Move them closer to the front.
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` CHANGE COLUMN `forum_id` `forum_id` INT(11) NOT NULL DEFAULT 0 AFTER `topic_id`;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_topics` CHANGE COLUMN `forum_id` `forum_id` INT(11) NOT NULL AFTER `id`;');

                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` ADD CONSTRAINT `FK_[prefix]_forum_posts_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_topics` ADD CONSTRAINT `FK_[prefix]_forum_topics_[prefix]_forum_items` FOREIGN KEY (`forum_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');

                // no break
            case "1.34.1":
            case "1.34.2":
            case "1.34.3":
            case "1.34.4":
                // Create new tables 'forum_prefixes' and 'forum_prefixes_items'.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_forum_prefixes` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `prefix` VARCHAR(255) NOT NULL,
                            PRIMARY KEY (`id`) USING BTREE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_forum_prefixes_items` (
                            `item_id` INT(11) NOT NULL,
                            `prefix_id` INT(11) NOT NULL,
                            INDEX `FK_[prefix]_forum_prefixes_items_[prefix]_forum_items` (`item_id`) USING BTREE,
                            INDEX `FK_[prefix]_forum_prefixes_items_[prefix]_forum_prefixes` (`prefix_id`) USING BTREE,
                            CONSTRAINT `FK_[prefix]_forum_prefixes_items_[prefix]_forum_items` FOREIGN KEY (`item_id`) REFERENCES `[prefix]_forum_items` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                            CONSTRAINT `FK_[prefix]_forum_prefixes_items_[prefix]_forum_prefixes` FOREIGN KEY (`prefix_id`) REFERENCES `[prefix]_forum_prefixes` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Convert old data for the prefix feature to the new system.
                // Get all non-empty values of prefix for every forum.
                $prefixRows = $this->db()->select(['id', 'prefix'])
                    ->from('forum_items')
                    ->where(['type' => 1, 'prefix <>' => ''])
                    ->execute()
                    ->fetchList('prefix', 'id');

                // Get all topics with a prefix. Ignore 0, which stands for no prefix.
                $topicsWithPrefix = $this->db()->select(['id', 'forum_id', 'topic_prefix'])
                    ->from('forum_topics')
                    ->where(['topic_prefix <>' => 0])
                    ->execute()
                    ->fetchRows();

                // Create a list of unique prefixes that need to be added to the new 'forum_prefixes' table.
                $mapTopicIdToPrefix = [];
                $mapItemIdToPrefixes = [];
                $existingPrefixes = [];
                foreach ($prefixRows as $item_id => $prefixes) {
                    $prefixes = explode(', ', $prefixes);

                    // Map the topic id to the used prefix.
                    foreach ($topicsWithPrefix as $topic) {
                        $mapTopicIdToPrefix[$topic['id']] = $prefixes[$topic['topic_prefix']] ?? '';
                    }

                    $existingPrefixes = array_merge($existingPrefixes, $prefixes);
                    $mapItemIdToPrefixes[$item_id] = $prefixes;
                }
                $uniquePrefixes = array_unique($existingPrefixes);

                // Add the unique prefixes in chunks of 25 to the 'forum_prefixes' table.
                $chunks = array_chunk($uniquePrefixes, 25);
                foreach ($chunks as $chunk) {
                    $this->db()->insert('forum_prefixes')
                        ->columns(['prefix'])
                        ->values($chunk)
                        ->execute();
                }

                // Update the 'topic_prefix' column of the 'forum_topics' table to contain the id of the prefix.
                $existingPrefixes = $this->db()->select(['id', 'prefix'])
                    ->from('forum_prefixes')
                    ->execute()
                    ->fetchRows();

                foreach ($topicsWithPrefix as $topic) {
                    foreach ($existingPrefixes as $existingPrefix) {
                        if (!$mapTopicIdToPrefix[$topic['id']]) {
                            $this->db()->update('forum_topics')
                                ->values(['topic_prefix' => 0])
                                ->where(['id' => $topic['id']])
                                ->execute();
                            break;
                        }
                        if ($mapTopicIdToPrefix[$topic['id']] = $existingPrefix['prefix']) {
                            $this->db()->update('forum_topics')
                                ->values(['topic_prefix' => $existingPrefix['id']])
                                ->where(['id' => $topic['id']])
                                ->execute();
                            break;
                        }
                    }
                }

                // Add currently allowed prefixes for the forum items.
                $forumPrefixesItemsRows = [];
                foreach ($mapItemIdToPrefixes as $item_id => $prefixes) {
                    foreach ($prefixes as $prefix) {
                        foreach ($existingPrefixes as $existingPrefix) {
                            if ($prefix === $existingPrefix['prefix']) {
                                $forumPrefixesItemsRows[] = ['item_id' => $item_id, 'prefix_id' => $existingPrefix['id']];
                                break;
                            }
                        }
                    }
                }
                $chunks = array_chunk($forumPrefixesItemsRows, 25);
                foreach ($chunks as $chunk) {
                    $this->db()->insert('forum_prefixes_items')
                        ->columns(['item_id', 'prefix_id'])
                        ->values($chunk)
                        ->execute();
                }

                // Delete the no longer used column 'prefix' of the 'forum_items' table.
                $this->db()->query('ALTER TABLE `[prefix]_forum_items` DROP COLUMN `prefix`;');

                // no break
            case "1.34.5":
            case "1.34.6":
            case "1.34.7":
            case "1.35.0":
            case "1.35.1":
            case "1.35.2":
                // Change datatype of the text column from TEXT to MEDIUMTEXT. This obviously allows longer forum posts.
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` MODIFY COLUMN `text` MEDIUMTEXT NOT NULL;');

                // no break
        }

        return '"' . $this->config['key'] . '" Update function executed.';
    }
}

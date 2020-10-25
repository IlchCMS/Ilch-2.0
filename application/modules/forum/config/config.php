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
        'version' => '1.28.0',
        'icon_small' => 'fa-list',
        'author' => 'Stantin Thomas',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Forum',
                'description' => 'Das ab Werk ausgelieferte Forum-Modul mit vielen typischen Funktionen eines Forums.',
            ],
            'en_EN' => [
                'name' => 'Forum',
                'description' => 'The by factory distributed forum module with many typical features of a forum.',
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
        'ilchCore' => '2.1.36',
        'phpVersion' => '5.6'
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
        $filename = uniqid().'.css';
        file_put_contents(APPLICATION_PATH.'/modules/forum/static/css/groupappearance/'.$filename, $defaultCss);
        $databaseConfig->set('forum_filenameGroupappearanceCSS', $filename);

        $this->db()->query('INSERT INTO `[prefix]_forum_groupranking` (`group_id`,`rank`) VALUES(1,0);');
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DROP TABLE `[prefix]_forum_topics`;
            DROP TABLE `[prefix]_forum_groupranking`;
            DROP TABLE `[prefix]_forum_items`;
            DROP TABLE `[prefix]_forum_posts`;
            DROP TABLE `[prefix]_forum_ranks`;
            DROP TABLE `[prefix]_forum_topicsubscription`;
            DROP TABLE `[prefix]_forum_remember`;
            DROP TABLE `[prefix]_forum_reports`;
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

    public function getInstallSql()
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
                `prefix` VARCHAR(255) NOT NULL,
                `read_access` VARCHAR(255) NOT NULL,
                `replay_access` VARCHAR(255) NOT NULL,
                `create_access` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_forum_topicsubscription` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `topic_id` INT(11) NULL DEFAULT NULL,
                `user_id` INT(11) NULL DEFAULT NULL,
                `last_notification` DATETIME NOT NULL,
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
                `read` VARCHAR(255) NOT NULL DEFAULT \'\',
                PRIMARY KEY (`id`)
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
                `user_id` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
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

            INSERT INTO `[prefix]_forum_items` (`id`, `sort`, `parent_id`, `type`, `title`, `description`, `read_access`, `replay_access`, `create_access`) VALUES
                (1, 0, 0, 0, "Meine Kategorie", "Meine erste Kategorie", "", "", ""),
                (2, 10, 1, 1, "Mein Forum", "Mein erstes Forum", "2,3", 2, 2);

            INSERT INTO `[prefix]_forum_topics` (`id`, `topic_id`, `topic_title`, `creator_id`, `date_created`, `forum_id`) VALUES
                (1, 2, "Willkommen bei Ilch!", 0, NOW(), 2);

            INSERT INTO `[prefix]_forum_posts` (`id`, `topic_id`, `text`, `user_id`, `date_created`, `forum_id`) VALUES
                (1, 1, "Willkommen im Ilch 2 Forum!\n\nBei Fragen oder Probleme im [url=http://www.ilch.de/forum.html]Ilch Forum[/url] melden.

                        Viel Erfolg
                        Ilch", 0, NOW(), 2);

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
                (12, "Foren Gott", 10000);

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

    public function getUpdate($installedVersion)
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
            case "1.10.0":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_forum_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_topics` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_forum_ranks` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');

                // Delete no longer needed file.
                unlink(ROOT_PATH.'/application/modules/forum/controllers/admin/Base.php');
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
                $filename = uniqid().'.css';
                file_put_contents(APPLICATION_PATH.'/modules/forum/static/css/groupappearance/'.$filename, $defaultCss);
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
                if (!file_exists(APPLICATION_PATH.'/modules/forum/static/css/groupappearance')) {
                    mkdir(APPLICATION_PATH.'/modules/forum/static/css/groupappearance');

                    // Add default appearance for admin group
                    $databaseConfig = new \Ilch\Config\Database($this->db());
                    $appearance[1]['active'] = 'on';
                    $appearance[1]['textcolor'] = '#000000';
                    $appearance[1]['bold'] = 'on';
                    $databaseConfig->set('forum_groupAppearance', serialize($appearance));

                    $defaultCss = '#forum .appearance1 {color: #000000;font-weight: bold;}';
                    $filename = uniqid().'.css';
                    file_put_contents(APPLICATION_PATH.'/modules/forum/static/css/groupappearance/'.$filename, $defaultCss);
                    $databaseConfig->set('forum_filenameGroupappearanceCSS', $filename);
                }
            case "1.19.0":
            case "1.20.0":
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('forum_DESCPostorder', '0');
            case "1.21.0":
                // convert forum_groupAppearance to new format
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $appearance = unserialize($databaseConfig->get('forum_groupAppearance'));
                $databaseConfig->set('forum_groupAppearance', json_encode($appearance));
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
            foreach($this->config['languages'] as $key => $value) {
                $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'forum' AND `locale` = '%s';", $value['description'], $key));
            }

            case "1.25.0":
            case "1.26.0":
            case "1.27.0":

                case "1.28.0":
                //
                $this->db()->query('ALTER TABLE `[prefix]_forum_posts` CHANGE `text` `text` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ;');

        }
    }
}


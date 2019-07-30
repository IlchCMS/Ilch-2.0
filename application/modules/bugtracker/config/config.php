<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'bugtracker',
        'version' => '1.0',
        'icon_small' => 'fa-bug',
        'author' => 'Nex4T',
        'link' => '',
        'languages' => [
            'de_DE' => [
                'name' => 'Bugtracker',
                'description' => 'Bugtracker für dein Projekt',
            ],
            'en_EN' => [
                'name' => 'Bugtracker',
                'description' => 'Bugtracker for your own project',
            ],
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_status` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) NOT NULL,
                        `css_class` varchar(50) NOT NULL DEFAULT '',
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db()->query($query);

        $query = "  INSERT INTO `[prefix]_bugtracker_status` (`id`, `name`, `css_class`) VALUES
	                    (1, '<i class=\"fa fa-info-circle\"></i> New Report', 'label label-success'),
	                    (2, 'Confirmed', 'label label-success'),
	                    (3, 'Assigned', 'label label-success'),
	                    (4, 'In Progress', 'label label-warning'),
	                    (5, 'Complete', 'label label-success'),
	                    (6, 'Ready for testing', 'label label-primary'),
	                    (7, 'Fixed', 'label label-success'),
	                    (8, '<i class=\"fa fa-times-circle\"></i> Closed', 'label label-danger'),
	                    (9, 'No Bug', 'label label-default');";
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_categories` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(50) NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_sub_categories` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `category_id` int(11) NOT NULL,
                        `name` varchar(50) NOT NULL,
                        PRIMARY KEY (`id`),
                        KEY `FK_bugtracker_sub_category_bugtracker_category` (`category_id`),
                        CONSTRAINT `FK_bugtracker_sub_category_bugtracker_category` FOREIGN KEY (`category_id`) REFERENCES `[prefix]_bugtracker_categories` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugs` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `sub_category_id` int(11) NOT NULL,
                        `title` varchar(250) NOT NULL,
                        `description` longtext NOT NULL,
                        `priority` int(11) DEFAULT NULL,
                        `creator_id` int(11) unsigned NOT NULL,
                        `progress` int(11) NOT NULL DEFAULT '0',
                        `status_id` int(11) NOT NULL,
                        `intern_only` tinyint(4) NOT NULL DEFAULT '0',
                        `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`),
                        KEY `FK_bugs_bugtracker_sub_categories` (`sub_category_id`),
                        KEY `FK_bugs_users` (`creator_id`),
                        KEY `FK_bugs_bugtracker_status` (`status_id`),
                        CONSTRAINT `FK_bugs_bugtracker_status` FOREIGN KEY (`status_id`) REFERENCES `[prefix]_bugtracker_status` (`id`),
                        CONSTRAINT `FK_bugs_bugtracker_sub_categories` FOREIGN KEY (`sub_category_id`) REFERENCES `[prefix]_bugtracker_sub_categories` (`id`),
                        CONSTRAINT `FK_bugs_users` FOREIGN KEY (`creator_id`) REFERENCES `[prefix]_users` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";        
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_assigned_users` (
                        `bug_id` int(11) NOT NULL,
                        `user_id` int(11) unsigned NOT NULL,
                        PRIMARY KEY (`bug_id`,`user_id`),
                        KEY `FK_bugtracker_assigned_users_users` (`user_id`),
                        CONSTRAINT `FK_bugtracker_assigned_users_bugs` FOREIGN KEY (`bug_id`) REFERENCES `[prefix]_bugs` (`id`),
                        CONSTRAINT `FK_bugtracker_assigned_users_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";        
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_attachments` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `bug_id` int(11) NOT NULL,
                        `filename` varchar(255) NOT NULL,
                        `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`),
                        KEY `FK_bugtracker_attachments_bugs` (`bug_id`),
                        CONSTRAINT `FK_bugtracker_attachments_bugs` FOREIGN KEY (`bug_id`) REFERENCES `[prefix]_bugs` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_comments` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `bug_id` int(11) NOT NULL,
                        `content` longtext NOT NULL,
                        `poster_id` int(11) unsigned NOT NULL,
                        `intern_only` tinyint(4) NOT NULL DEFAULT '0',
                        `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`),
                        KEY `FK_bugtracker_comments_bugs` (`bug_id`),
                        KEY `FK_bugtracker_comments_users` (`poster_id`),
                        CONSTRAINT `FK_bugtracker_comments_bugs` FOREIGN KEY (`bug_id`) REFERENCES `[prefix]_bugs` (`id`),
                        CONSTRAINT `FK_bugtracker_comments_users` FOREIGN KEY (`poster_id`) REFERENCES `[prefix]_users` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db()->query($query);

        $query = "  CREATE TABLE IF NOT EXISTS `[prefix]_bugtracker_votes` (
                        `bug_id` int(11) NOT NULL,
                        `user_id` int(11) unsigned NOT NULL,
                        `type` enum('like','dislike') NOT NULL,
                        PRIMARY KEY (`bug_id`,`user_id`),
                        KEY `FK_bugtracker_votes_users` (`user_id`),
                        CONSTRAINT `FK_bugtracker_votes_bugs` FOREIGN KEY (`bug_id`) REFERENCES `[prefix]_bugs` (`id`),
                        CONSTRAINT `FK_bugtracker_votes_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db()->query($query);
    }

    public function uninstall()
    {

    }

    public function getUpdate($installedVersion)
    {
    }
}

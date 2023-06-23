<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'vote',
        'version' => '1.12.0',
        'icon_small' => 'fa-solid fa-bars-progress',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Umfrage',
                'description' => 'Zum Erstellen und Verwalten von Umfragen, welche auf einer Seite oder in einer Box angezeigt werden können.',
            ],
            'en_EN' => [
                'name' => 'Vote',
                'description' => 'Enables you to create and manage votes, which can be shown on a page or inside of a box.',
            ]
        ],
        'boxes' => [
            'vote' => [
                'de_DE' => [
                    'name' => 'Umfrage'
                ],
                'en_EN' => [
                    'name' => 'Vote'
                ]
            ]
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->drop('poll_res', true);
        $this->db()->drop('poll_ip', true);
        $this->db()->drop('poll_access', true);
        $this->db()->drop('poll', true);
    }

    public function getInstallSql(): string
    {
        return "CREATE TABLE IF NOT EXISTS `[prefix]_poll` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `question` VARCHAR(255) NOT NULL,
                `key` VARCHAR(255) NOT NULL,
                `groups` VARCHAR(255) NOT NULL DEFAULT '0',
                `status` TINYINT(1) NOT NULL DEFAULT 0,
                `read_access_all` TINYINT(1) NOT NULL,
                `multiple_reply` TINYINT(1) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_poll_access` (
                `poll_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL,
                PRIMARY KEY (`poll_id`, `group_id`) USING BTREE,
                INDEX `FK_[prefix]_poll_access_[prefix]_groups` (`group_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_poll_access_[prefix]_poll` FOREIGN KEY (`poll_id`) REFERENCES `[prefix]_poll` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_poll_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_poll_res` (
                `poll_id` INT(11) NOT NULL,
                `reply` VARCHAR(255) NOT NULL,
                `result` INT(11) NOT NULL,
                INDEX `FK_[prefix]_poll_res_[prefix]_poll` (`poll_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_poll_res_[prefix]_poll` FOREIGN KEY (`poll_id`) REFERENCES `[prefix]_poll` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_poll_ip` (
                `poll_id` INT(11) NOT NULL,
                `ip` VARCHAR(255) NOT NULL,
                `user_id` INT(11) NOT NULL,
                INDEX `FK_[prefix]_poll_ip_[prefix]_poll` (`poll_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_poll_ip_[prefix]_poll` FOREIGN KEY (`poll_id`) REFERENCES `[prefix]_poll` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;";
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_poll` ADD `read_access` VARCHAR(255) NOT NULL DEFAULT \'2,3\' AFTER `group`;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_ip` ADD `user_id` INT(11) NOT NULL AFTER `ip`;');
                // no break
            case "1.1":
            case "1.2":
            case "1.3":
            case "1.4":
            case "1.5":
                $this->db()->query('ALTER TABLE `[prefix]_poll` CHANGE `group` `groups` VARCHAR(255) NOT NULL DEFAULT \'0\';');
                // no break
            case "1.6":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_poll` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_res` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_ip` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case '1.7.0':
            case '1.8.0':
            case '1.9.0':
            case '1.10.0':
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'vote' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case '1.11.0':
                $this->db()->update('modules')->values(['icon_small' => $this->config['icon_small']])->where(['key' => $this->config['key']])->execute();

                // Create new table for read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_poll_access` (
                `poll_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL,
                PRIMARY KEY (`poll_id`, `group_id`) USING BTREE,
                INDEX `FK_[prefix]_poll_access_[prefix]_groups` (`group_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_poll_access_[prefix]_poll` FOREIGN KEY (`poll_id`) REFERENCES `[prefix]_poll` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_poll_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Convert data from old read_access column of table poll to the new poll_access table.
                $readAccessRows = $this->db()->select(['id', 'read_access'])
                    ->from(['poll'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                $sql = 'INSERT INTO [prefix]_poll_access (poll_id, group_id) VALUES';
                $sqlWithValues = $sql;
                $rowCount = 0;

                foreach ($readAccessRows as $readAccessRow) {
                    $readAccessArray = [];
                    $readAccessArray[$readAccessRow['id']] = explode(',', $readAccessRow['read_access']);
                    foreach ($readAccessArray as $articleId => $groupIds) {
                        // There is a limit of 1000 rows per insert, but according to some benchmarks found online
                        // the sweet spot seams to be around 25 rows per insert. So aim for that.
                        if ($rowCount >= 25) {
                            $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                            $this->db()->queryMulti($sqlWithValues);
                            $rowCount = 0;
                            $sqlWithValues = $sql;
                        }

                        // Don't try to add a groupId that doesn't exist in the groups table as this would
                        // lead to an error (foreign key constraint).
                        $groupIds = array_intersect($existingGroups, $groupIds);
                        $rowCount += \count($groupIds);

                        foreach ($groupIds as $groupId) {
                            $sqlWithValues .= '(' . $articleId . ',' . $groupId . '),';
                        }
                    }
                }
                if ($sqlWithValues != $sql) {
                    // Insert remaining rows.
                    $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                    $this->db()->queryMulti($sqlWithValues);
                }

                // Delete old read_access column of table poll.
                $this->db()->query('ALTER TABLE `[prefix]_poll` DROP COLUMN `read_access`;');
                $this->db()->query('ALTER TABLE `[prefix]_poll` ADD `read_access_all` TINYINT(1) NOT NULL AFTER `status`;');
                $this->db()->query('ALTER TABLE `[prefix]_poll` ADD `multiple_reply` TINYINT(1) NOT NULL AFTER `read_access_all`;');

                // Add constraint to poll_ip and poll_res after deleting orphaned rows in them (rows with an poll_id not
                // existing in the poll table) as this would lead to an error.
                $ids = $this->db()->select('id')
                    ->from('poll')
                    ->execute()
                    ->fetchList();

                $idsPoll = $this->db()->select('poll_id')
                    ->from('poll_ip')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($idsPoll ?? [], $ids ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('poll_ip')
                        ->where(['poll_id' => $orphanedRows])
                        ->execute();
                }

                $idsPoll = $this->db()->select('poll_id')
                    ->from('poll_res')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($idsPoll ?? [], $ids ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('poll_res')
                        ->where(['poll_id' => $orphanedRows])
                        ->execute();
                }

                $this->db()->query('ALTER TABLE `[prefix]_poll_ip` ADD INDEX `FK_[prefix]_poll_ip_[prefix]_poll` (`poll_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_ip` ADD CONSTRAINT `FK_[prefix]_poll_ip_[prefix]_poll` FOREIGN KEY (`poll_id`) REFERENCES `[prefix]_poll` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');

                $this->db()->query('ALTER TABLE `[prefix]_poll_res` ADD INDEX `FK_[prefix]_poll_res_[prefix]_poll` (`poll_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_poll_res` ADD CONSTRAINT `FK_[prefix]_poll_res_[prefix]_poll` FOREIGN KEY (`poll_id`) REFERENCES `[prefix]_poll` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
        }

        return 'Update function executed.';
    }
}

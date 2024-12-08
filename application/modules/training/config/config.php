<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Config;

use Modules\User\Mappers\User;

class Config extends \Ilch\Config\Install
{
    public array $config = [
        'key' => 'training',
        'version' => '1.10.0',
        'icon_small' => 'fa-solid fa-graduation-cap',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Training',
                'description' => 'Hiermit können Trainings geplant werden und auf Wunsch auch in den Kalender eingetragen werden. Über einer Box können die nächsten Trainings angezeigt werden.',
            ],
            'en_EN' => [
                'name' => 'Training',
                'description' => 'Can be used to plan trainings, which optionally can be added to the calendar. The next trainings can be shown with a box.',
            ],
        ],
        'boxes' => [
            'nexttraining' => [
                'de_DE' => [
                    'name' => 'Next Training'
                ],
                'en_EN' => [
                    'name' => 'Next Training'
                ]
            ]
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.4'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        if ($this->db()->ifTableExists('calendar_events')) {
            $this->db()->insert('calendar_events', ['url' => 'training/trainings/index/'])->execute();
        }

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('training_boxNexttrainingLimit', '5');
    }

    public function uninstall()
    {
        $this->db()->drop('training_access', true);
        $this->db()->drop('training_entrants', true);
        $this->db()->drop('training', true);

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->delete('calendar_events', ['url' => 'training/trainings/index/'])->execute();
        }

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('training_boxNexttrainingLimit');
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_training` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(100) NOT NULL,
                `date` DATETIME NOT NULL,
                `end` DATETIME DEFAULT NULL,
                `period_type` VARCHAR(100) NOT NULL,
                `period_day` INT(11) NOT NULL,
                `repeat_until` DATETIME DEFAULT NULL,
                `place` VARCHAR(100) NOT NULL,
                `contact` INT(11) UNSIGNED NOT NULL,
                `voice_server` INT(11) NOT NULL,
                `voice_server_ip` VARCHAR(100) NOT NULL,
                `voice_server_pw` VARCHAR(100) NOT NULL,
                `game_server` INT(11) NOT NULL,
                `game_server_ip` VARCHAR(100) NOT NULL,
                `game_server_pw` VARCHAR(100) NOT NULL,
                `text` MEDIUMTEXT NOT NULL,
                `show` TINYINT(1) NOT NULL DEFAULT 0,
                `access_all` TINYINT(1) NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `FK_[prefix]_training_[prefix]_users` (`contact`) USING BTREE,
                CONSTRAINT `FK_[prefix]_training_[prefix]_users` FOREIGN KEY (`contact`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_training_access` (
                `training_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL,
                PRIMARY KEY (`training_id`, `group_id`) USING BTREE,
                INDEX `FK_[prefix]_training_access_[prefix]_training` (`training_id`) USING BTREE,
                INDEX `FK_[prefix]_training_access_[prefix]_groups` (`group_id`) USING BTREE,
                CONSTRAINT `FK_[prefix]_training_access_[prefix]_training` FOREIGN KEY (`training_id`) REFERENCES `[prefix]_training` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT `FK_[prefix]_training_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_training_entrants` (
              `train_id` INT(11) NOT NULL,
              `user_id` INT(11) UNSIGNED NOT NULL,
              `note` VARCHAR(100) NOT NULL,
              PRIMARY KEY (`train_id`, `user_id`) USING BTREE,
              INDEX `FK_[prefix]_training_entrants_[prefix]_training` (`train_id`) USING BTREE,
              INDEX `FK_[prefix]_training_entrants_[prefix]_users` (`user_id`) USING BTREE,
              CONSTRAINT `FK_[prefix]_training_entrants_[prefix]_training` FOREIGN KEY (`train_id`) REFERENCES `[prefix]_training` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
              CONSTRAINT `FK_[prefix]_training_entrants_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `show` TINYINT(1) NOT NULL DEFAULT 0 AFTER `text`;');
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `read_access` VARCHAR(191) NOT NULL AFTER `show`;');
                // no break
            case "1.1":
                // On installation of Ilch adding this entry failed. Reinstalling or a later install of this module adds the entry.
                // Add entry on update. Instead of checking if the entry exists, delete entry/entries and add it again.
                if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
                    $this->db()->query("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'training/trainings/index/'");
                    $this->db()->query('INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("training/trainings/index/");');
                }
                // no break
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_training` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            // no break
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
                $boxMapper = new \Modules\Admin\Mappers\Box();
                $boxModel = new \Modules\Admin\Models\Box();
                $boxModel->setModule($this->config['key']);
                foreach ($this->config['boxes'] as $key => $value) {
                    $boxModel->addContent($key, $value);
                }
                $boxMapper->install($boxModel);

                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('training_boxNexttrainingLimit', '5');

                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'training' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.7.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");

                // Create new table for read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_training_access` (
                    `training_id` INT(11) NOT NULL,
                    `group_id` INT(11) NOT NULL,
                    PRIMARY KEY (`training_id`, `group_id`) USING BTREE,
                    INDEX `FK_[prefix]_training_access_[prefix]_training` (`training_id`) USING BTREE,
                    INDEX `FK_[prefix]_training_access_[prefix]_groups` (`group_id`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_training_access_[prefix]_training` FOREIGN KEY (`training_id`) REFERENCES `[prefix]_training` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                    CONSTRAINT `FK_[prefix]_training_access_[prefix]_users` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Convert data from old read_access column of table training to the new training_access table.
                $readAccessRows = $this->db()->select(['id', 'read_access', 'contact'])
                    ->from(['training'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                $preparedRows = [];

                $userMapper = new User();
                $admins = $userMapper->getUserListByGroupId(1, 1);
                /** @var \Modules\User\Models\User $admin */
                $admin = reset($admins);

                $contact = [];
                $ids = [];
                $accesall = [];
                foreach ($readAccessRows as $readAccessRow) {
                    if ($readAccessRow['contact']) {
                        $contact[$readAccessRow['contact']] = $userMapper->getUserById($readAccessRow['contact']);
                        if (!$contact[$readAccessRow['contact']]) {
                            $this->db()->update('training', ['contact' => $admin->getId()], ['id' => $readAccessRow['id']]);
                        }
                    }
                    $ids[] = $readAccessRow['id'];
                    $readAccessArray = [];
                    $readAccessArray[$readAccessRow['id']] = explode(',', $readAccessRow['read_access']);
                    if (empty($readAccessRow['read_access'])) {
                        $accesall[] = $readAccessRow['id'];
                    } else {
                        foreach ($readAccessArray as $trainingId => $groupIds) {
                            $groupIds = array_intersect($existingGroups, $groupIds);
                            foreach ($groupIds as $groupId) {
                                $preparedRows[] = [$trainingId, (int)$groupId];
                            }
                        }
                    }
                }

                if (count($preparedRows)) {
                    // Add access rights in chunks of 25 to the table. This prevents reaching the limit of 1000 rows
                    $chunks = array_chunk($preparedRows, 25);
                    foreach ($chunks as $chunk) {
                        $this->db()->insert('training_access')
                            ->columns(['training_id', 'group_id'])
                            ->values($chunk)
                            ->execute();
                    }
                }

                // Delete old read_access column of table training.
                $this->db()->query('ALTER TABLE `[prefix]_training` DROP COLUMN `read_access`;');
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `access_all` TINYINT(1) NOT NULL AFTER `show`;');

                foreach ($accesall as $id) {
                    $this->db()->update('training', ['access_all' => 1], ['id' => $id]);
                }

                $this->db()->query('ALTER TABLE `[prefix]_training` CHANGE `contact` `contact` INT(11) UNSIGNED NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD INDEX `FK_[prefix]_training_[prefix]_users` (`contact`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD CONSTRAINT `FK_[prefix]_training_[prefix]_users` FOREIGN KEY (`contact`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');

                $usersRows = $this->db()->select(['train_id', 'user_id'])
                    ->from(['training_entrants'])
                    ->group(['user_id'])
                    ->execute()
                    ->fetchRows();
                foreach ($usersRows as $usersRow) {
                    if (!isset($contact[$usersRow['user_id']])) {
                        $contact[$usersRow['user_id']] = $userMapper->getUserById($usersRow['user_id']);
                    }
                    if (!$contact[$usersRow['user_id']]) {
                        $this->db()->delete('training_entrants', ['user_id' => $usersRow['user_id']]);
                    }
                }
                $trainsRows = $this->db()->select(['train_id'])
                    ->from(['training_entrants'])
                    ->group(['train_id'])
                    ->execute()
                    ->fetchRows();
                foreach ($trainsRows as $trainsRow) {
                    if (!in_array($trainsRow['train_id'], $ids)) {
                        $this->db()->delete('training_entrants', ['train_id' => $trainsRow['train_id']]);
                    }
                }

                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` CHANGE `user_id` `user_id` INT(11) UNSIGNED NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` ADD PRIMARY KEY(`train_id`, `user_id`);');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` ADD INDEX `FK_[prefix]_training_entrants_[prefix]_training` (`train_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` ADD INDEX `FK_[prefix]_training_entrants_[prefix]_users` (`user_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` ADD CONSTRAINT `FK_[prefix]_training_entrants_[prefix]_training` FOREIGN KEY (`train_id`) REFERENCES `[prefix]_training` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` ADD CONSTRAINT `FK_[prefix]_training_entrants_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
                // no break
            case "1.8.0":
                // no break
            case "1.8.1":
                // no break
            case "1.9.0":
                if ($this->db()->ifTableExists('calendar_events') && $this->db()->select('COUNT(*)', 'calendar_events', ['url' => 'training/trainings/index/'])->execute()->fetchCell() == 0) {
                    $this->db()->insert('calendar_events', ['url' => 'training/trainings/index/'])->execute();
                }
                // no break
            case "1.9.1":
            case "1.9.2":
                // Add new columns for recurrent trainings feature.
                $this->db()->queryMulti('ALTER TABLE `[prefix]_training` ADD `end` DATETIME DEFAULT NULL AFTER `date`;
                        ALTER TABLE `[prefix]_training` ADD `period_type` VARCHAR(100) NOT NULL AFTER `time`;
                        ALTER TABLE `[prefix]_training` ADD `period_day` INT(11) NOT NULL AFTER `period_type`;
                        ALTER TABLE `[prefix]_training` ADD `repeat_until` DATETIME DEFAULT NULL AFTER `period_day`;');

                // Calculate the value for the end column with the columns date and time. Update the end column in the table.
                $trainings = $this->db()->select(['id', 'date', 'time'])
                    ->from(['training'])
                    ->execute()
                    ->fetchRows();

                foreach($trainings ?? [] as $training) {
                    $end = date("Y-m-d H:i:s", strtotime('+' . $training['time'] . ' minutes', strtotime($training['date'])));
                    $this->db()->update('training', ['end' => $end], ['id' => $training['id']]);
                }

                // Drop time column
                $this->db()->query('ALTER TABLE `[prefix]_training` DROP COLUMN `time`;');
                // no break
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

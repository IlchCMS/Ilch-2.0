<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Config;

use Modules\Admin\Mappers\Module as ModulesMapper;
use Modules\Calendar\Mappers\Events as EventsMapper;
use Modules\Calendar\Models\Events as EventsModel;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'calendar',
        'version' => '1.9.0',
        'icon_small' => 'fa-calendar',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Kalender',
                'description' => 'Ein einfacher Kalender. Termine können im Admincenter eingetragen werden.',
            ],
            'en_EN' => [
                'name' => 'Calendar',
                'description' => 'A simple calendar. Appointments can be entered in the admincenter.',
            ],
        ],
        'boxes' => [
            'calendar' => [
                'de_DE' => [
                    'name' => 'Kalender'
                ],
                'en_EN' => [
                    'name' => 'Calendar'
                ]
            ]
        ],
        'ilchCore' => '2.1.43',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $eventsMapper = new EventsMapper();
        $eventsModel = new EventsModel();

        $eventsMapper->save($eventsModel->setUrl('calendar/events/index/'));

        $modulesMapper = new ModulesMapper();
        $warmodul = $modulesMapper->getModulesByKey('war', $this->getTranslator()->getLocale());
        if ($warmodul) {
            $eventsMapper->save($eventsModel->setUrl('war/wars/index/'));
        }
        $trainingmodul = $modulesMapper->getModulesByKey('training', $this->getTranslator()->getLocale());
        if ($trainingmodul) {
            $eventsMapper->save($eventsModel->setUrl('training/trainings/index/'));
        }
        $birthdaymodul = $modulesMapper->getModulesByKey('birthday', $this->getTranslator()->getLocale());
        if ($birthdaymodul) {
            $eventsMapper->save($eventsModel->setUrl('birthday/birthdays/index/'));
        }
        $eventsmodul = $modulesMapper->getModulesByKey('events', $this->getTranslator()->getLocale());
        if ($eventsmodul) {
            $eventsMapper->save($eventsModel->setUrl('events/events/index/'));
        }
        $awaymodul = $modulesMapper->getModulesByKey('away', $this->getTranslator()->getLocale());
        if ($awaymodul) {
            $eventsMapper->save($eventsModel->setUrl('away/aways/index/'));
        }
    }

    public function uninstall()
    {
        $this->db()->drop('calendar_access', true);
        $this->db()->drop('calendar_events', true);
        $this->db()->drop('calendar', true);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_calendar` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(100) NOT NULL,
          `place` VARCHAR(100) DEFAULT NULL,
          `start` DATETIME NOT NULL,
          `end` DATETIME DEFAULT NULL,
          `text` MEDIUMTEXT DEFAULT NULL,
          `color` VARCHAR(7) DEFAULT NULL,
          `period_type` VARCHAR(100) NOT NULL,
          `period_day` INT(11) NOT NULL,
          `repeat_until` DATETIME DEFAULT NULL,
          `read_access_all` TINYINT(1) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_calendar_events` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `url` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_calendar_access` (
          `calendar_id` INT(11) NOT NULL,
          `group_id` INT(11) NOT NULL,
          PRIMARY KEY (`calendar_id`, `group_id`) USING BTREE,
          INDEX `FK_[prefix]_calendar_access_[prefix]_groups` (`group_id`) USING BTREE,
          CONSTRAINT `FK_[prefix]_calendar_access_[prefix]_calendar` FOREIGN KEY (`calendar_id`) REFERENCES `[prefix]_calendar` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
          CONSTRAINT `FK_[prefix]_calendar_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\';');
                // no break
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `period_day` INT(1) DEFAULT NULL AFTER `color`;');
                // no break
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_calendar` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_calendar_events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.3.0":
                // no break
            case "1.4.0":
                // no break
            case "1.5.0":
                // no break
            case "1.6.0":
            // update zu 1.7.0
                /*
                Update ilch Core
                Rechtesystem geändert
                Code verbesserung
                Box hinzugefügt #461
                Zyklische / wiederkehrende Termine #424
                */
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'calendar' AND `locale` = '%s';", $value['description'], $key));
                }

                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD `period_type` VARCHAR(100) NOT NULL AFTER `color`;');
                $this->db()->query('ALTER TABLE `[prefix]_calendar` CHANGE `period_day` `period_day` INT(11) NOT NULL;');

                // Create new table for calendar read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_calendar_access` (
                        `calendar_id` INT(11) NOT NULL,
                        `group_id` INT(11) NOT NULL,
                        PRIMARY KEY (`calendar_id`, `group_id`) USING BTREE,
                        INDEX `FK_[prefix]_calendar_access_[prefix]_groups` (`group_id`) USING BTREE,
                        CONSTRAINT `FK_[prefix]_calendar_access_[prefix]_calendar` FOREIGN KEY (`calendar_id`) REFERENCES `[prefix]_calendar` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                        CONSTRAINT `FK_[prefix]_calendar_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Convert data from old read_access column of table calendar to the new calendar_access table.
                $readAccessRows = $this->db()->select(['id', 'read_access', 'period_day'])
                    ->from(['calendar'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                if ($readAccessRows) {
                    foreach ($readAccessRows as $readAccessRow) {
                        $readAccess = explode(',', $readAccessRow['read_access']);
                        $readAccess = array_intersect($existingGroups, $readAccess);

                        // Delete possible old entries to later insert the new ones.
                        $this->db()->delete('calendar_access')
                            ->where(['calendar_id' => $readAccessRow['id']])
                            ->execute();

                        $sql = 'INSERT INTO [prefix]_calendar_access (calendar_id, group_id) VALUES';
                        $sqlWithValues = $sql;
                        $rowCount = 0;
                        $groupIds = [];
                        if (!empty($readAccess)) {
                            if (!in_array('all', $readAccess)) {
                                $groupIds = $readAccess;
                            }
                        }
                        if (!in_array('1', $groupIds)) {
                            $groupIds[] = '1';
                        }

                        foreach ($groupIds as $groupId) {
                            // There is a limit of 1000 rows per insert, but according to some benchmarks found online
                            // the sweet spot seams to be around 25 rows per insert. So aim for that.
                            if ($rowCount >= 25) {
                                $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                                $this->db()->queryMulti($sqlWithValues);
                                $rowCount = 0;
                                $sqlWithValues = $sql;
                            }

                            $rowCount++;
                            $sqlWithValues .= '(' . $readAccessRow['id'] . ',' . (int)$groupId . '),';
                        }

                        // Insert remaining rows.
                        $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                        $this->db()->queryMulti($sqlWithValues);

                        if ($readAccessRow['period_day'] > 0) {
                            $this->db()->update('calendar')
                                ->values(['period_type' => 'days'])
                                ->where(['id' => $readAccessRow['id']])
                                ->execute();
                        }
                    }
                }

                // Delete old read_access column of table calendar.
                $this->db()->query('ALTER TABLE `[prefix]_calendar` DROP COLUMN `read_access`;');
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD `read_access_all` TINYINT(1) NOT NULL AFTER `period_day`;');

                // Add box
                $boxMapper = new \Modules\Admin\Mappers\Box();
                $boxModel = new \Modules\Admin\Models\Box();
                $boxModel->setModule($this->config['key']);
                $boxModel->addContent('calendar', $this->config['boxes']['calendar']);
                $boxMapper->install($boxModel);

                removeDir(APPLICATION_PATH.'/modules/calendar/static/js/fullcalendar/');
            // no break
            case "1.7.0":
                // no break
                removeDir(APPLICATION_PATH.'/modules/calendar/static/js/fullcalendar_5_11_0/');
            case "1.8.0":
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD COLUMN `repeat_until` DATETIME DEFAULT NULL AFTER `period_day`;');
                // no break
        }
    }
}

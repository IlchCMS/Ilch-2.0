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
        'version' => '1.7.0',
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
        'ilchCore' => '2.1.43',
        'phpVersion' => '7.4'
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
        $this->db()->queryMulti('DROP TABLE `[prefix]_calendar`;
                                 DROP TABLE `[prefix]_calendar_events`;');
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
          `period_day` INT(1) DEFAULT NULL,
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
                Update ilchCore
                Rechte-System geändert
                Code verbesseung
                */
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'calendar' AND `locale` = '%s';", $value['description'], $key));
                }

                // Create new table for war read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_calendar_access` (
                        `calendar_id` INT(11) NOT NULL,
                        `group_id` INT(11) NOT NULL,
                        PRIMARY KEY (`calendar_id`, `group_id`) USING BTREE,
                        INDEX `FK_[prefix]_calendar_access_[prefix]_groups` (`group_id`) USING BTREE,
                        CONSTRAINT `FK_[prefix]_calendar_access_[prefix]_calendar` FOREIGN KEY (`calendar_id`) REFERENCES `[prefix]_calendar` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                        CONSTRAINT `FK_[prefix]_calendar_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
                // Convert data from old read_access column of table war to the new war_access table.
                $readAccessRows = $this->db()->select(['id', 'read_access'])
                    ->from(['calendar'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                if ($readAccessRows) {
                    $calendarMapper = new \Modules\Calendar\Mappers\Calendar();

                    foreach ($readAccessRows as $readAccessRow) {
                        $groupIds = explode(',', $readAccessRow['read_access']);
                        $groupIds = array_intersect($existingGroups, $groupIds);
                        $calendarMapper->saveReadAccess($readAccessRow['id'], $groupIds);
                    }
                }

                // Delete old read_access column of table war.
                $this->db()->query('ALTER TABLE `[prefix]_calendar` DROP COLUMN `read_access`;');
                $this->db()->query('ALTER TABLE `[prefix]_calendar` ADD `read_access_all` TINYINT(1) NOT NULL AFTER `period_day`;');
        }
    }
}

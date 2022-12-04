<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'war',
        'version' => '1.15.1',
        'icon_small' => 'fa-shield',
        'author' => 'Stantin, Thomas',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'War',
                'description' => 'Modul zum Verwalten von Wars. Es können eigene Gruppen und Gegner angelegt werden.',
            ],
            'en_EN' => [
                'name' => 'War',
                'description' => 'Module to manage wars. You can add own groups and opponents.',
            ],
        ],
        'boxes' => [
            'nextwar' => [
                'de_DE' => [
                    'name' => 'Next War'
                ],
                'en_EN' => [
                    'name' => 'Next War'
                ]
            ],
            'lastwar' => [
                'de_DE' => [
                    'name' => 'Last War'
                ],
                'en_EN' => [
                    'name' => 'Last War'
                ]
            ]
        ],
        'ilchCore' => '2.1.43',
        'phpVersion' => '7.4'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->insert('calendar_events')->values(['url' => 'war/wars/index/'])->execute();
        }

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('war_boxNextWarLimit', '5')
            ->set('war_boxLastWarLimit', '5');
    }

    public function uninstall()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('war_boxNextWarLimit');
        $databaseConfig->delete('war_boxLastWarLimit');
        $databaseConfig->delete('war_warsPerPage');
        $databaseConfig->delete('war_enemiesPerPage');
        $databaseConfig->delete('war_groupsPerPage');

        $this->db()->drop('war_access', true);
        $this->db()->drop('war_accept', true);
        $this->db()->drop('war_played', true);
        $this->db()->drop('war_groups', true);
        $this->db()->drop('war_enemy', true);
        $this->db()->drop('war_maps', true);
        $this->db()->drop('war', true);

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->delete('calendar_events')->where(['url' => 'war/wars/index/'])->execute();
        }
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_war` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `enemy` INT(11) NOT NULL,
          `group` INT(11) NOT NULL,
          `time` DATETIME NOT NULL,
          `maps` VARCHAR(255) NOT NULL,
          `server` VARCHAR(255) NOT NULL,
          `password` VARCHAR(255) NOT NULL,
          `xonx` VARCHAR(50) NOT NULL,
          `game` VARCHAR(255) NOT NULL,
          `matchtype` VARCHAR(255) NOT NULL,
          `report` TEXT NOT NULL,
          `status` TINYINT(1) NOT NULL DEFAULT 0,
          `show` TINYINT(1) NOT NULL DEFAULT 0,
          `lastaccepttime` INT(11) NOT NULL,
          `read_access_all` TINYINT(1) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_war_maps` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(32) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_war_enemy` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(150) NOT NULL,
          `tag` VARCHAR(20) NOT NULL,
          `homepage` VARCHAR(150) NOT NULL,
          `image` VARCHAR(255) NOT NULL,
          `contact_name` VARCHAR(50) NOT NULL,
          `contact_email` VARCHAR(150) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_war_groups` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(32) NOT NULL,
          `tag` VARCHAR(20) NOT NULL,
          `image` VARCHAR(255) NOT NULL,
          `desc` VARCHAR(255) NOT NULL,
          `member` INT(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_war_played` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `war_id` INT(11) DEFAULT NULL,
          `map` INT(11) NOT NULL,
          `group_points` MEDIUMINT(9) DEFAULT NULL,
          `enemy_points` MEDIUMINT(9) DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `FK_[prefix]_war_played_[prefix]_war` (`war_id`) USING BTREE,
          CONSTRAINT `FK_[prefix]_war_played_[prefix]_war` FOREIGN KEY (`war_id`) REFERENCES `[prefix]_war` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_war_accept` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `war_id` INT(11) DEFAULT NULL,
          `user_id` INT(11) DEFAULT NULL,
          `accept` TINYINT(1) DEFAULT NULL,
          `comment` MEDIUMTEXT NOT NULL,
          `date_created` DATETIME NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `FK_[prefix]_war_accept_[prefix]_war` (`war_id`) USING BTREE,
          CONSTRAINT `FK_[prefix]_war_accept_[prefix]_war` FOREIGN KEY (`war_id`) REFERENCES `[prefix]_war` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_war_access` (
            `war_id` INT(11) NOT NULL,
            `group_id` INT(11) NOT NULL,
            PRIMARY KEY (`war_id`, `group_id`) USING BTREE,
            INDEX `FK_[prefix]_war_access_[prefix]_groups` (`group_id`) USING BTREE,
            CONSTRAINT `FK_[prefix]_war_access_[prefix]_war` FOREIGN KEY (`war_id`) REFERENCES `[prefix]_war` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
            CONSTRAINT `FK_[prefix]_war_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                // no break
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_war` ADD `show` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;');
                $this->db()->query('ALTER TABLE `[prefix]_war` ADD `read_access` VARCHAR(255) NOT NULL AFTER `show`;');
                // no break
            case "1.2":
                // On installation of Ilch adding this entry failed. Reinstalling or a later install of this module adds the entry.
                // Add entry on update. Instead of checking if the entry exists, delete entry/entries and add it again.
                if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
                    $this->db()->query("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'war/wars/index/'");
                    $this->db()->query('INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("war/wars/index/");');
                }
                // no break
            case "1.3":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_war_groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war_enemy` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war_played` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_war_accept` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.4.0":
                // no break
            case "1.5.0":
                // no break
            case "1.6.0":
                // no break
            case "1.7.0":
                $this->db()->query('ALTER TABLE `[prefix]_war_accept` ADD COLUMN `comment` MEDIUMTEXT;');
                // no break
            case "1.8.0":
                // no break
            case "1.9.0":
                // no break
            case "1.10.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'war' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.11.0":
                // no break
            case "1.12.0":
                // no break
            case "1.13.0":
                // no break
            case "1.14.0":
            // update zu 1.15.0
                /*
                Update ilchCore
                Maps Tabelle hinzugefügt
                Rechte-System geändert
                Alte Datensätze löschen
                Code verbesseung
                Anzeige wann der User sich eingetragen hatt
                Sperrzeit in Minuten bis wann sich ein User eintagen kann
                Game Icon verwaltung
                */
                //Create Maps Table and import from "war_played"
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_war_maps` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(32) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');

                $mapsRows = $this->db()->select(['id', 'map'])
                    ->from(['war_played'])
                    ->execute()
                    ->fetchRows();

                $this->db()->query('ALTER TABLE `[prefix]_war_played` CHANGE `map` `map` INT(11) NOT NULL;');

                if ($mapsRows) {
                    $savedMaps = [];
                    $savedMapsid = [];
                    foreach ($mapsRows as $mapArray) {
                        if (!isset($savedMapsid[strtolower($mapArray['map'])])) {
                            $id = $this->db()->insert('war_maps')
                                ->values(['name' => $mapArray['map']])
                                ->execute();

                            $savedMapsid[strtolower($mapArray['map'])] = $id;
                        }
                        $savedMaps[strtolower($mapArray['map'])][] = $mapArray['id'];
                    }
                    foreach ($savedMaps as $mapsName => $mapsArray) {
                        foreach ($mapsArray as $mapsId) {
                            $this->db()->update('war_played')
                                ->values(['map' => $savedMapsid[$mapsName]])
                                ->where(['id' => $mapsId])
                                ->execute();
                        }
                    }
                }

                // Create new table for war read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_war_access` (
                    `war_id` INT(11) NOT NULL,
                    `group_id` INT(11) NOT NULL,
                    PRIMARY KEY (`war_id`, `group_id`) USING BTREE,
                    INDEX `FK_[prefix]_war_access_[prefix]_groups` (`group_id`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_war_access_[prefix]_war` FOREIGN KEY (`war_id`) REFERENCES `[prefix]_war` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                    CONSTRAINT `FK_[prefix]_war_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
                // Convert data from old read_access column of table war to the new war_access table.
                $readAccessRows = $this->db()->select(['id', 'read_access'])
                    ->from(['war'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                if ($readAccessRows) {
                    $warMapper = new \Modules\War\Mappers\War();

                    foreach ($readAccessRows as $readAccessRow) {
                        $groupIds = explode(',', $readAccessRow['read_access']);
                        $groupIds = array_intersect($existingGroups, $groupIds);
                        $warMapper->saveReadAccess($readAccessRow['id'], $groupIds);
                    }
                }

            // Delete old read_access column of table war.
            $this->db()->query('ALTER TABLE `[prefix]_war` DROP COLUMN `read_access`;');

            //Deleting all old ID's
            $idswar = $this->db()->select('id')
                ->from('war')
                ->execute()
                ->fetchList();

            $idswar_played = $this->db()->select('war_id')
                ->from('war_played')
                ->execute()
                ->fetchList();

            $idswar_accept = $this->db()->select('war_id')
                ->from('war_accept')
                ->execute()
                ->fetchList();

            $orphanedRowswar_played = array_diff($idswar_played ?? [], $idswar ?? []);
            if (count($orphanedRowswar_played) > 0) {
                $this->db()->delete()->from('war_played')
                    ->where(['war_id' => $orphanedRowswar_played])
                    ->execute();
            }

            $orphanedRowswar_accept = array_diff($idswar_accept ?? [], $idswar ?? []);
            if (count($orphanedRowswar_accept) > 0) {
                $this->db()->delete()->from('war_accept')
                    ->where(['war_id' => $orphanedRowswar_accept])
                    ->execute();
            }

            $commentsArray = $this->db()->select('*')
                ->from('comments')
                ->where(['key LIKE' => 'war/index/show/id/%'])
                ->execute()
                ->fetchRows();

            $idswar_comments = [];
            foreach ($commentsArray ?? [] as $comments) {
                $warid = explode('/', $comments['key']);
                if (isset($warid[4]) and !in_array($warid[4], $idswar_comments)) {
                    $idswar_comments[] = $warid[4];
                }
            }
            $orphanedRowswar_comments = array_diff($idswar_comments ?? [], $idswar ?? []);
            if (count($orphanedRowswar_comments) > 0) {
                foreach ($orphanedRowswar_comments as $warid) {
                    $this->db()->delete()->from('comments')
                        ->where(['key LIKE' => 'war/index/show/id/'.$warid.'%'])
                        ->execute();
                }
            }

            //change TABLE
            $this->db()->query('ALTER TABLE `[prefix]_war_played` ADD INDEX `FK_[prefix]_war_played_[prefix]_war` (`war_id`) USING BTREE;');
            $this->db()->query('ALTER TABLE `[prefix]_war_played` ADD CONSTRAINT `FK_[prefix]_war_played_[prefix]_war` FOREIGN KEY (`war_id`) REFERENCES `[prefix]_war` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
            $this->db()->query('ALTER TABLE `[prefix]_war_accept` ADD INDEX `FK_[prefix]_war_accept_[prefix]_war` (`war_id`) USING BTREE;');
            $this->db()->query('ALTER TABLE `[prefix]_war_accept` ADD CONSTRAINT `FK_[prefix]_war_accept_[prefix]_war` FOREIGN KEY (`war_id`) REFERENCES `[prefix]_war` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
            $this->db()->query('ALTER TABLE `[prefix]_war_accept` ADD `date_created` DATETIME NOT NULL AFTER `comment`;');
            $this->db()->query('ALTER TABLE `[prefix]_war` ADD `lastaccepttime` INT(11) NOT NULL AFTER `show`;');
            // no break
            case "1.15.0":
                // update zu 1.15.1
                /*
                Rechte-System erweitert
                Code verbesseung
                */
                $this->db()->query('ALTER TABLE `[prefix]_war` ADD `read_access_all` TINYINT(1) NOT NULL AFTER `lastaccepttime`;');
        }
    }
}

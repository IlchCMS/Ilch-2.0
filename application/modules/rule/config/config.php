<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Rule\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'rule',
        'version' => '1.8.0',
        'icon_small' => 'fa-solid fa-gavel',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Regeln',
                'description' => 'Zum Verfassen von Regeln, die auf der Seite angezeigt werden können. Unterstützt Paragraphen, bequemes Ändern der Reihenfolge und Leserechte.',
            ],
            'en_EN' => [
                'name' => 'Rules',
                'description' => 'Can be used to write down a ruleset, which can be shown on the websites. Supports paragraphs, easy changing the order and adjusting read access.',
            ],
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
        $this->db()->drop('rules_access', true);
        $this->db()->drop('rules', true);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_rules` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `paragraph` VARCHAR(255) NOT NULL,
            `title` VARCHAR(100) NOT NULL,
            `text` MEDIUMTEXT NOT NULL,
            `position` INT(11) NOT NULL DEFAULT 0,
            `parent_id` INT(11) NOT NULL DEFAULT 0,
            `access_all` TINYINT(1) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_rules_access` (
            `rule_id` INT(11) NOT NULL,
            `group_id` INT(11) NOT NULL,
            PRIMARY KEY (`rule_id`, `group_id`) USING BTREE,
            INDEX `FK_[prefix]_rules_access_[prefix]_groups` (`group_id`) USING BTREE,
            CONSTRAINT `FK_[prefix]_rules_access_[prefix]_rules` FOREIGN KEY (`rule_id`) REFERENCES `[prefix]_rules` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
            CONSTRAINT `FK_[prefix]_rules_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD `position` INT(11) NOT NULL DEFAULT 0;');
                // no break
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_rules` MODIFY COLUMN `paragraph` VARCHAR(255) NOT NULL;');
                // no break
            case "1.2":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_rules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.3.0":
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD COLUMN `parent_id` INT(11) NOT NULL DEFAULT 0;');
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD COLUMN `access` varchar(255) NOT NULL;');
                $rulesArray = $this->db()->select('*')->from('rules')->execute()->fetchAssoc();
                if (!empty($rulesArray)) {
                    $this->db()->query('INSERT INTO `[prefix]_rules` (`id`, `paragraph`, `title`, `text`, `position`, `parent_id`, `access`) VALUES (NULL, "1", "All Rules", "", "0", "0", "");');
                    $result = $this->db()->getLastInsertId();
                    $this->db()->query('UPDATE `[prefix]_rules` SET `parent_id` = "' . $result . '" WHERE `id` != "' . $result . '"');
                }

                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('rule_showallonstart', '1');
                // no break
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'rule' AND `locale` = '%s';", $value['description'], $key));
                }
                // no break
            case "1.7.0":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");

                // Create new table for read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_rules_access` (
                        `rule_id` INT(11) NOT NULL,
                        `group_id` INT(11) NOT NULL,
                        PRIMARY KEY (`rule_id`, `group_id`) USING BTREE,
                        INDEX `FK_[prefix]_rules_access_[prefix]_groups` (`group_id`) USING BTREE,
                        CONSTRAINT `FK_[prefix]_rules_access_[prefix]_rules` FOREIGN KEY (`rule_id`) REFERENCES `[prefix]_rules` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                        CONSTRAINT `FK_[prefix]_rules_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Convert data from old read_access column of table faqs_cats to the new faqs_cats_access table.
                $readAccessRows = $this->db()->select(['id', 'access'])
                    ->from(['rules'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                $preparedRows = [];

                $accesall = [];
                foreach ($readAccessRows as $readAccessRow) {
                    $readAccessArray = [];
                    $readAccessArray[$readAccessRow['id']] = explode(',', $readAccessRow['access']);
                    if (empty($readAccessRow['access'])) {
                        $accesall[] = $readAccessRow['id'];
                    } else {
                        foreach ($readAccessArray as $ruleId => $groupIds) {
                            $groupIds = array_intersect($existingGroups, $groupIds);
                            foreach ($groupIds as $groupId) {
                                $preparedRows[] = [$ruleId, (int)$groupId];
                            }
                        }
                    }
                }

                if (count($preparedRows)) {
                    // Add access rights in chunks of 25 to the table. This prevents reaching the limit of 1000 rows
                    // per insert, which would have been possible with a higher number of forums and user groups.
                    $chunks = array_chunk($preparedRows, 25);
                    foreach ($chunks as $chunk) {
                        $this->db()->insert('rules_access')
                            ->columns(['rule_id', 'group_id'])
                            ->values($chunk)
                            ->execute();
                    }
                }

                // Delete old read_access column of table faqs_cats.
                $this->db()->query('ALTER TABLE `[prefix]_rules` DROP COLUMN `access`;');
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD `access_all` TINYINT(1) NOT NULL AFTER `parent_id`;');

                foreach ($accesall as $id) {
                    $this->db()->query("UPDATE `[prefix]_rules` SET `access_all` = '1' WHERE `id` = '" . $id . "';");
                }
                // no break
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

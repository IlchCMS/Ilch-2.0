<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'faq',
        'version' => '1.9.0',
        'icon_small' => 'fa-regular fa-circle-question',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'F.A.Q.',
                'description' => 'Ein FAQ-Modul (Häufig gestellte Fragen) mit Kategorien, Optionen zur Sortierung und einer Suchfunktion.',
            ],
            'en_EN' => [
                'name' => 'F.A.Q.',
                'description' => 'A FAQ module (frequently asked questions) with categories, options regarding the sorting and a search function.',
            ],
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('faq_sortCategoriesAlphabetically', '0')
            ->set('faq_sortQuestionsAlphabetically', '0');
    }

    public function uninstall()
    {
        $this->db()->drop('faqs_cats_access', true);
        $this->db()->drop('faqs_cats', true);
        $this->db()->drop('faqs', true);

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('faq_sortCategoriesAlphabetically')
            ->delete('faq_sortQuestionsAlphabetically');
    }

    public function getInstallSql(): string
    {
        return '
        CREATE TABLE IF NOT EXISTS `[prefix]_faqs_cats` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(100) NOT NULL,
          `read_access_all` TINYINT(1) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

       CREATE TABLE IF NOT EXISTS `[prefix]_faqs` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `cat_id` INT(11) NULL DEFAULT 0,
          `question` VARCHAR(100) NOT NULL,
          `answer` MEDIUMTEXT NOT NULL,
          PRIMARY KEY (`id`)
          INDEX `FK_[prefix]_faqs_[prefix]_faqs_cats` (`cat_id`) USING BTREE,
          CONSTRAINT `FK_[prefix]_faqs_[prefix]_faqs_cats` FOREIGN KEY (`cat_id`) REFERENCES `[prefix]_faqs_cats` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `[prefix]_faqs_cats_access` (
            `cat_id` INT(11) NOT NULL,
            `group_id` INT(11) NOT NULL,
            PRIMARY KEY (`cat_id`, `group_id`) USING BTREE,
            INDEX `FK_[prefix]_articles_access_[prefix]_groups` (`group_id`) USING BTREE,
            CONSTRAINT `FK_[prefix]_faqs_cats_access_[prefix]_faqs_cats` FOREIGN KEY (`cat_id`) REFERENCES `[prefix]_faqs_cats` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
            CONSTRAINT `FK_[prefix]_faqs_cats_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_faqs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_faqs_cats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            // no break
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
                // Add read_access column
                $this->db()->query('ALTER TABLE `[prefix]_faqs_cats` ADD COLUMN `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\';');
            // no break
            case "1.6.0":
            case "1.7.0":
                // Update description
                foreach ($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'faq' AND `locale` = '%s';", $value['description'], $key));
                }
            // no break
            case "1.8.0":
                $this->db()->update('modules')->values(['icon_small' => $this->config['icon_small']])->where(['key' => $this->config['key']])->execute();

                // Create new table for read access.
                $this->db()->queryMulti('CREATE TABLE IF NOT EXISTS `[prefix]_faqs_cats_access` (
            `cat_id` INT(11) NOT NULL,
            `group_id` INT(11) NOT NULL,
            PRIMARY KEY (`cat_id`, `group_id`) USING BTREE,
            INDEX `FK_[prefix]_articles_access_[prefix]_groups` (`group_id`) USING BTREE,
            CONSTRAINT `FK_[prefix]_faqs_cats_access_[prefix]_faqs_cats` FOREIGN KEY (`cat_id`) REFERENCES `[prefix]_faqs_cats` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
            CONSTRAINT `FK_[prefix]_faqs_cats_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // Convert data from old read_access column of table articles to the new articles_access table.
                $readAccessRows = $this->db()->select(['id', 'read_access'])
                    ->from(['faqs_cats'])
                    ->execute()
                    ->fetchRows();

                $existingGroups = $this->db()->select('id')
                    ->from(['groups'])
                    ->execute()
                    ->fetchList();

                $sql = 'INSERT INTO [prefix]_faqs_cats_access (cat_id, group_id) VALUES';
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

                // Delete old read_access column of table articles.
                $this->db()->query('ALTER TABLE `[prefix]_faqs_cats` DROP COLUMN `read_access`;');
                $this->db()->query('ALTER TABLE `[prefix]_faqs_cats` ADD `read_access_all` TINYINT(1) NOT NULL AFTER `title`;');

                // Add constraint to articles_content after deleting orphaned rows in it (rows with an article id not
                // existing in the articles table) as this would lead to an error.
                $idsCats = $this->db()->select('id')
                    ->from('faqs_cats')
                    ->execute()
                    ->fetchList();

                $idsFaq = $this->db()->select('cat_id')
                    ->from('faqs')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($idsFaq ?? [], $idsCats ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('faqs')
                        ->where(['cat_id' => $orphanedRows])
                        ->execute();
                }

                $this->db()->query('ALTER TABLE `[prefix]_faqs` ADD INDEX `FK_[prefix]_faqs_[prefix]_faqs_cats` (`cat_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_faqs` ADD CONSTRAINT `FK_[prefix]_faqs_[prefix]_faqs_cats` FOREIGN KEY (`cat_id`) REFERENCES `[prefix]_faqs_cats` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
        }

        return 'Update function executed.';
    }
}

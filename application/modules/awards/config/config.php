<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'awards',
        'version' => '1.10.1',
        'icon_small' => 'fa-solid fa-trophy',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Auszeichnungen',
                'description' => 'Hier können Auszeichnungen an Benutzer oder Teams verliehen werden.',
            ],
            'en_EN' => [
                'name' => 'Awards',
                'description' => 'Here you can award users or teams an award.',
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
        $this->db()->queryMulti('DROP TABLE `[prefix]_awards_recipients`;
            DROP TABLE `[prefix]_awards`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_awards` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date` DATE NOT NULL,
                  `rank` INT(11) NOT NULL,
                  `image` VARCHAR(255) NOT NULL,
                  `event` VARCHAR(100) NOT NULL,
                  `url` VARCHAR(150) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_awards_recipients` (
                    `award_id` INT(11) NOT NULL,
                    `ut_id` INT(11) NOT NULL,
                    `typ` TINYINT(1) NOT NULL,
                    INDEX `FK_[prefix]_awards_recipients_[prefix]_awards` (`award_id`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_awards_recipients_[prefix]_awards` FOREIGN KEY (`award_id`) REFERENCES `[prefix]_awards` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB COLLATE=utf8mb4_unicode_ci;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_awards` ADD `image` VARCHAR(255) NOT NULL AFTER `rank`;');
            case "1.2":
            case "1.3":
            case "1.4":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_awards` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case '1.5.0':
            case '1.6.0':
            case '1.7.0':
            case '1.8.0':
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-solid fa-trophy' WHERE `key` = 'awards';");
            case '1.9.0':
                // Create new table awards_recipients.
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_awards_recipients` (
                    `award_id` INT(11) NOT NULL,
                    `ut_id` INT(11) NOT NULL,
                    `typ` TINYINT(1) NOT NULL,
                    INDEX `FK_[prefix]_awards_recipients_[prefix]_awards` (`award_id`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_awards_recipients_[prefix]_awards` FOREIGN KEY (`award_id`) REFERENCES `[prefix]_awards` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB COLLATE=utf8mb4_unicode_ci;');

                // Copy existing recipients in chunks of 25 recipients at a time to the new table.
                $existingRecipientsRows = $this->db()->select(['id', 'ut_id', 'typ'])
                    ->from(['awards'])
                    ->execute()
                    ->fetchRows();

                $existingRecipients = [];
                foreach ($existingRecipientsRows as $recipientsRow) {
                    $existingRecipients[] = [$recipientsRow['id'], $recipientsRow['ut_id'], $recipientsRow['typ']];
                }

                $existingRecipients = array_chunk($existingRecipients, 25);
                foreach($existingRecipients as $existingRecipientsChunk) {
                    $this->db()->insert('awards_recipients')
                        ->columns(['award_id', 'ut_id', 'typ'])
                        ->values($existingRecipientsChunk)
                        ->execute();
                }

                // Delete no longer needed columns of the awards table
                $this->db()->query('ALTER TABLE `[prefix]_awards` DROP COLUMN `ut_id`, DROP COLUMN `typ`');
        }
    }
}

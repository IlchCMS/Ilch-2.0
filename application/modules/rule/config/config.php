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
        'version' => '1.7.0',
        'icon_small' => 'fa-gavel',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Regeln',
                'description' => 'Zum Verfassen von Regeln, die auf der Seite angezeigt werden können. Untersützt Paragraphen, bequemes Ändern der Reihenfolge und Leserechte.',
            ],
            'en_EN' => [
                'name' => 'Rules',
                'description' => 'Can be used to write down a ruleset, which can be shown on the websites. Supports paragraphs, easy changing the order and adjusting read access.',
            ],
        ],
        'ilchCore' => '2.1.26',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_rules`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_rules` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `paragraph` VARCHAR(255) NOT NULL,
            `title` VARCHAR(100) NOT NULL,
            `text` MEDIUMTEXT NOT NULL,
            `position` INT(11) NOT NULL DEFAULT 0,
            `parent_id` INT(11) NOT NULL DEFAULT 0,
            `access` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD `position` INT(11) NOT NULL DEFAULT 0;');
            case "1.1":
                $this->db()->query('ALTER TABLE `[prefix]_rules` MODIFY COLUMN `paragraph` VARCHAR(255) NOT NULL;');
            case "1.2":
                // Convert table to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_rules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.3.0":
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD COLUMN `parent_id` INT(11) NOT NULL DEFAULT 0;');
                $this->db()->query('ALTER TABLE `[prefix]_rules` ADD COLUMN `access` varchar(255) NOT NULL;');
                $rulesArray = $this->db()->select('*')->from('rules')->execute()->fetchAssoc();
                if (!empty($rulesArray)) {
                    $this->db()->query('INSERT INTO `[prefix]_rules` (`id`, `paragraph`, `title`, `text`, `position`, `parent_id`, `access`) VALUES (NULL, "1", "All Rules", "", "0", "0", "");');
                    $result = $this->db()->getLastInsertId();
                    $this->db()->query('UPDATE `[prefix]_rules` SET `parent_id` = "'.$result.'" WHERE `id` != "'.$result.'"');
                }

                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('rule_showallonstart', '1');
            case "1.4.0":
            case "1.5.0":
            case "1.6.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'rule' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}

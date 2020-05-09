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
        'version' => '1.7.0',
        'icon_small' => 'fa-question-circle',
        'author' => 'Veldscholten, Kevin',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'F.A.Q.',
                'description' => 'Hier können die FAQ - Häufig gestellte Fragen verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'F.A.Q.',
                'description' => 'Here you can manage your FAQ - Frequently Asked Questions.',
            ],
        ],
        'ilchCore' => '2.1.16',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('faq_sortCategoriesAlphabetically', '0');
        $databaseConfig->set('faq_sortQuestionsAlphabetically', '0');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_faqs`;
                                 DROP TABLE `[prefix]_faqs_cats`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'faq_sortCategoriesAlphabetically';
             DELETE FROM `[prefix]_config` WHERE `key` = 'faq_sortQuestionsAlphabetically'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_faqs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` INT(11) NULL DEFAULT 0,
                  `question` VARCHAR(100) NOT NULL,
                  `answer` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_faqs_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(100) NOT NULL,
                  `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_faqs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_faqs_cats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            case "1.3.0":
            case "1.4.0":
            case "1.5.0":
                // Add read_access column
                $this->db()->query('ALTER TABLE `[prefix]_faqs_cats` ADD COLUMN `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\';');
        }
    }
}

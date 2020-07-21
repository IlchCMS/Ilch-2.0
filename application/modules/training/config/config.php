<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'training',
        'version' => '1.7.0',
        'icon_small' => 'fa-graduation-cap',
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
        'ilchCore' => '2.1.26',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('training_boxNexttrainingLimit', '5');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_training`;
                                 DROP TABLE `[prefix]_training_entrants`;');

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $this->db()->queryMulti('DELETE FROM `[prefix]_calendar_events` WHERE `url` = \'training/trainings/index/\';');
        }

        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'training_boxNexttrainingLimit'");
    }

    public function getInstallSql()
    {
        $installSql =
            'CREATE TABLE IF NOT EXISTS `[prefix]_training` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(100) NOT NULL,
                `date` DATETIME NOT NULL,
                `time` INT(11) NOT NULL,
                `place` VARCHAR(100) NOT NULL,
                `contact` INT(11) NOT NULL,
                `voice_server` INT(11) NOT NULL,
                `voice_server_ip` VARCHAR(100) NOT NULL,
                `voice_server_pw` VARCHAR(100) NOT NULL,
                `game_server` INT(11) NOT NULL,
                `game_server_ip` VARCHAR(100) NOT NULL,
                `game_server_pw` VARCHAR(100) NOT NULL,
                `text` MEDIUMTEXT NOT NULL,
                `show` TINYINT(1) NOT NULL DEFAULT 0,
                `read_access` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_training_entrants` (
              `train_id` INT(11) NOT NULL,
              `user_id` INT(11) NOT NULL,
              `note` VARCHAR(100) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
            $installSql.='INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("training/trainings/index/");';
        }
        return $installSql;
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0":
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `show` TINYINT(1) NOT NULL DEFAULT 0 AFTER `text`;');
                $this->db()->query('ALTER TABLE `[prefix]_training` ADD `read_access` VARCHAR(191) NOT NULL AFTER `show`;');
            case "1.1":
                // On installation of Ilch adding this entry failed. Reinstalling or a later install of this module adds the entry.
                // Add entry on update. Instead of checking if the entry exists, delete entry/entries and add it again.
                if ($this->db()->ifTableExists('[prefix]_calendar_events')) {
                    $this->db()->query("DELETE FROM `[prefix]_calendar_events` WHERE `url` = 'training/trainings/index/'");
                    $this->db()->query('INSERT INTO `[prefix]_calendar_events` (`url`) VALUES ("training/trainings/index/");');
                }
            case "1.2":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_training` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_training_entrants` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
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
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `description` = '%s' WHERE `key` = 'training' AND `locale` = '%s';", $value['description'], $key));
                }
        }
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Comment\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'comment',
        'icon_small' => 'fa-regular fa-comments',
        'system_module' => true,
        'hide_menu' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Kommentare',
                'description' => 'Hier werden alle Kommentare verwaltet.',
            ],
            'en_EN' => [
                'name' => 'Comments',
                'description' => 'Here you can manage all comments.',
            ],
        ],
        'boxes' => [
            'comments' => [
                'de_DE' => [
                    'name' => 'Kommentare'
                ],
                'en_EN' => [
                    'name' => 'Comments'
                ]
            ]
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('comment_reply', '1');
        $databaseConfig->set('comment_nesting', '5');
        $databaseConfig->set('comment_box_comments_limit', '5');
        $databaseConfig->set('comment_floodInterval', '10');
        $databaseConfig->set('comment_excludeFloodProtection', '1');
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_comments` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `key` VARCHAR(255) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  `fk_id` INT(11) NOT NULL DEFAULT 0,
                  `up` INT(11) NOT NULL DEFAULT 0,
                  `down` INT(11) NOT NULL DEFAULT 0,
                  `voted` LONGTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

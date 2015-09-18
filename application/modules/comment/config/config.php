<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'comment',
        'icon_small' => 'comment.png',
        'system_module' => true,
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Kommentare',
                'description' => 'Hier werden alle Kommentare verwaltet.',
            ),
            'en_EN' => array
            (
                'name' => 'Comments',
                'description' => 'Here you can manage all comments.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('comment_reply', '1');
        $databaseConfig->set('comment_interleaving', '5');
        $databaseConfig->set('comment_avatar', '1');
        $databaseConfig->set('comment_date', '1');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_comments` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `key` VARCHAR(255) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  `fk_id` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1';
    }
}

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
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_comments` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `key` varchar(255) NOT NULL,
		  `text` mediumtext NOT NULL,
		  `date_created` datetime NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `fk_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
                                

                CREATE TABLE IF NOT EXISTS `[prefix]_cfg_comments` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cc_strname` VARCHAR(100) NOT NULL,
                  `cc_strcode` VARCHAR(100) NOT NULL,
                  `cc_bolactive` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}

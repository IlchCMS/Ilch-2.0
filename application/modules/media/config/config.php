<?php
/**
 * Holds Media\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Media\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'media';
    public $author = 'Stantin Thomas';
    public $name = array
    (
        'en_EN' => 'Media',
        'de_DE' => 'Medien',
    );
    public $icon_small = 'media.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('media_uploadpath', 'application/modules/media/static/upload/');
        $databaseConfig->set('media_allowed_ending', 'png jpg gif');
    }
    
    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_media`
                (
                   `id` int(11) NOT NULL AUTO_INCREMENT,
                   `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
				   `url` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
				   `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
                   `datetime` datetime NOT NULL,
				   PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}


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
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('media_uploadpath', 'application/modules/media/static/upload/');
        $databaseConfig->set('media_ext_img',  'jpg jpeg png gif bmp tiff svg');
        $databaseConfig->set('media_ext_video',  'mp3 m4a ac3 aiff mid ogg wav mov mpeg mp4 avi mpg wma flv webm');
        $databaseConfig->set('media_ext_file',  'zip rar gz tar iso dmg doc docx rtf pdf xls xlsx txt csv html xhtml psd sql log fla xml ade adp mdb accdb ppt pptx odt ots ott odb odg otp otg odf ods odp css ai');
    }

    public function uninstall()
    {
    }
    
    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_media`
                (
                   `id` int(11) NOT NULL AUTO_INCREMENT,
                   `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
				   `url` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
                   `url_thumb` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
				   `ending` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
                   `datetime` datetime NOT NULL,
				   PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}


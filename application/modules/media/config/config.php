<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'media',
        'system_module' => true,
        'icon_small' => 'fa-th',
        'languages' => [
            'de_DE' => [
                'name' => 'Medien',
                'description' => 'Hier können die Medien verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Media',
                'description' => 'Here you can manage your media',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('media_uploadpath', 'application/modules/media/static/upload/');
        $databaseConfig->set('media_ext_img', 'jpg jpeg png gif bmp tiff svg');
        $databaseConfig->set('media_ext_video', 'mp3 m4a ac3 aiff mid ogg wav mov mpeg mp4 avi mpg wma flv webm');
        $databaseConfig->set('media_ext_file', 'zip rar gz tar iso dmg doc docx rtf pdf xls xlsx txt csv html xhtml psd sql log fla xml ade adp mdb accdb ppt pptx odt ots ott odb odg otp otg odf ods odp css ai');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_media`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_media_cats`');
    }
    
    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_media` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(50) NOT NULL DEFAULT 0,
                  `url` VARCHAR(150) NOT NULL DEFAULT 0,
                  `url_thumb` VARCHAR(150) NOT NULL DEFAULT 0,
                  `ending` VARCHAR(5) NOT NULL DEFAULT 0,
                  `datetime` DATETIME NOT NULL,
                  `cat_name` VARCHAR(100) NOT NULL,
                  `cat` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_media_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_name` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}


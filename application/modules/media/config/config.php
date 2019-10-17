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
        'icon_small' => 'fa-th',
        'system_module' => true,
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
        $databaseConfig->set('media_ext_img', 'jpg jpeg png gif bmp tiff svg ico');
        $databaseConfig->set('media_ext_video', 'mp3 m4a ac3 aiff mid ogg wav mov mpeg mp4 avi mpg wma flv webm');
        $databaseConfig->set('media_ext_file', 'zip rar gz tar iso dmg doc docx rtf pdf xls xlsx txt csv psd sql log fla xml ade adp mdb accdb ppt pptx odt ots ott odb odg otp otg odf ods odp css ai');
        $databaseConfig->set('media_extensionBlacklist', 'html htm xht xhtml php php2 php3 php4 php5 phtml pwml inc asp aspx ascx jsp cfm cfc pl bat exe com dll vbs js reg cgi htaccess asis sh shtml shtm phtm');
        $databaseConfig->set('media_directoriesAsCategories', '0');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_media` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL DEFAULT 0,
                  `url` VARCHAR(255) NOT NULL DEFAULT 0,
                  `url_thumb` VARCHAR(255) NOT NULL DEFAULT 0,
                  `ending` VARCHAR(5) NOT NULL DEFAULT 0,
                  `datetime` DATETIME NOT NULL,
                  `cat` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_media_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_name` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {

    }
}


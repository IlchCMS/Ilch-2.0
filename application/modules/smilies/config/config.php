<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Smilies\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'smilies',
        'icon_small' => 'fa-smile-o',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Smilies',
                'description' => 'Hier kann man die Smilies verwalten.',
            ],
            'en_EN' => [
                'name' => 'Smilies',
                'description' => 'Here you can magnage the Smilies.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('smiley_filetypes', 'png gif');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_smilies` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(50) NOT NULL,
                  `url` VARCHAR(150) NOT NULL,
                  `url_thumb` VARCHAR(150) NOT NULL,
                  `ending` VARCHAR(5) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_smilies` (`name`, `url`, `url_thumb`, `ending`) VALUES
                ("smiley", "application/modules/smilies/static/img/regular_smile.png", "application/modules/smilies/static/img/thumb_regular_smile.png", "png"),
                ("sad", "application/modules/smilies/static/img/sad_smile.png", "application/modules/smilies/static/img/thumb_sad_smile.png", "png"),
                ("wink", "application/modules/smilies/static/img/wink_smile.png", "application/modules/smilies/static/img/thumb_wink_smile.png", "png"),
                ("laugh", "application/modules/smilies/static/img/teeth_smile.png", "application/modules/smilies/static/img/thumb_teeth_smile.png", "png"),
                ("frown", "application/modules/smilies/static/img/confused_smile.png", "application/modules/smilies/static/img/thumb_confused_smile.png", "png"),
                ("cheeky", "application/modules/smilies/static/img/tongue_smile.png", "application/modules/smilies/static/img/thumb_tongue_smile.png", "png"),
                ("blush", "application/modules/smilies/static/img/embarrassed_smile.png", "application/modules/smilies/static/img/thumb_embarrassed_smile.png", "png"),
                ("surprise", "application/modules/smilies/static/img/omg_smile.png", "application/modules/smilies/static/img/thumb_omg_smile.png", "png"),
                ("indecision", "application/modules/smilies/static/img/whatchutalkingabout_smile.png", "application/modules/smilies/static/img/thumb_whatchutalkingabout_smile.png", "png"),
                ("angry", "application/modules/smilies/static/img/angry_smile.png", "application/modules/smilies/static/img/thumb_angry_smile.png", "png"),
                ("angel", "application/modules/smilies/static/img/angel_smile.png", "application/modules/smilies/static/img/thumb_angel_smile.png", "png"),
                ("cool", "application/modules/smilies/static/img/shades_smile.png", "application/modules/smilies/static/img/thumb_shades_smile.png", "png"),
                ("devil", "application/modules/smilies/static/img/devil_smile.png", "application/modules/smilies/static/img/thumb_devil_smile.png", "png"),
                ("crying", "application/modules/smilies/static/img/cry_smile.png", "application/modules/smilies/static/img/thumb_cry_smile.png", "png"),
                ("enlightened", "application/modules/smilies/static/img/lightbulb.png", "application/modules/smilies/static/img/thumb_lightbulb.png", "png"),
                ("no", "application/modules/smilies/static/img/thumbs_down.png", "application/modules/smilies/static/img/thumb_thumbs_down.png", "png"),
                ("yes", "application/modules/smilies/static/img/thumbs_up.png", "application/modules/smilies/static/img/thumb_thumbs_up.png", "png"),
                ("heart", "application/modules/smilies/static/img/heart.png", "application/modules/smilies/static/img/thumb_heart.png", "png"),
                ("broken_heart", "application/modules/smilies/static/img/broken_heart.png", "application/modules/smilies/static/img/thumb_broken_heart.png", "png"),
                ("kiss", "application/modules/smilies/static/img/kiss.png", "application/modules/smilies/static/img/thumb_kiss.png", "png"),
                ("mail", "application/modules/smilies/static/img/envelope.png", "application/modules/smilies/static/img/thumb_envelope.png", "png");';
    }

    public function getUpdate()
    {

    }
}

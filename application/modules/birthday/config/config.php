<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Birthday\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'birthday',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'fa-birthday-cake',
        'languages' => [
            'de_DE' => [
                'name' => 'Geburtstag',
                'description' => 'Hier kannst du die Geburtstags-Box verwalten.',
            ],
            'en_EN' => [
                'name' => 'Birthday',
                'description' => 'Here you can manage the birthday-box.',
            ],
        ]
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('bday_boxShow', '5');
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'bday_boxShow'");
    }

    public function getInstallSql()
    {
    }

    public function getUpdate()
    {

    }
}

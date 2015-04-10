<?php
/**
 * @package ilch
 */

namespace Modules\Birthday\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'birthday',
        'author' => 'Veldscholten Kevin',
        'icon_small' => 'birthday.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Geburtstag',
                'description' => 'Hier kannst du die Geburtstags Box verwalten.',
            ),
            'en_EN' => array
            (
                'name' => 'Birthday',
                'description' => 'Here you can change Birthday box.',
            ),
        )
    );

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('bday_boxShow', '5');
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
    }
}

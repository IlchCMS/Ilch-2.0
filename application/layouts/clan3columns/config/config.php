<?php
namespace Layouts\Clan3Columns\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'name' => 'Ilch-Clan',
        'version' => '1.0',
        'author' => 'Ilch.de',
        'link' => 'http://ilch.de',
        'desc' => '2 Spalten Clan Layout',
        'layouts' => ['index_full' => [['module' => 'user', 'controller' => 'panel']]], //only for example
        'layouts' => ['index_full' => [['module' => 'forum']]] //only for example
        //'layouts']['index_full'] = array(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'newentry'));
        //'modulekey'] = 'Name of Module';
    ];

    public function getUpdate()
    {

    }
}

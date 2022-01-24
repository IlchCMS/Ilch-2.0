<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Layouts\Clan3Columns\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'name' => 'Ilch-Clan',
        'version' => '1.0.1',
        'author' => 'Ilch.de',
        'link' => 'http://ilch.de',
        'desc' => '3 Spalten Clan Layout',
        'layouts' => [
            'index_full' => [
                ['module' => 'user', 'controller' => 'panel'],
                ['module' => 'forum'],
                ['module' => 'guestbook'],
            ]//only for example
        ],
        //'modulekey' => 'Name of Module'
    ];

    public function getUpdate($installedVersion)
    {
    }
}

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
        'version' => '1.1.0',
        'author' => 'Ilch.de',
        'link' => 'https://ilch.de',
        'desc' => '3 Spalten Clan Layout',
        'layouts' => [
            'index_full' => [
                ['module' => 'user', 'controller' => 'panel'],
                ['module' => 'forum'],
                ['module' => 'guestbook'],
            ],
            'start' => [
                ['module' => 'contact'],
            ],
            'shop_index' => [
                ['module' => 'shop'],
            ]
        ],
        'settings' => [
            'header' => [
              'type' => 'text',
              'default' => 'Clanname',
              'description' => '',
            ],
            'slider1' => [
              'type' => 'mediaselection',
              'default' => 'application/layouts/clan3columns/img/slider/slider_1.jpg',
              'description' => 'img',
            ],
            'slider2' => [
              'type' => 'mediaselection',
              'default' => 'application/layouts/clan3columns/img/slider/slider_2.jpg',
              'description' => 'img',
            ],
            'slider3' => [
              'type' => 'mediaselection',
              'default' => 'application/layouts/clan3columns/img/slider/slider_3.jpg',
              'description' => 'img',
            ],
        ],
    ];

    public function getUpdate($installedVersion)
    {
    }
}

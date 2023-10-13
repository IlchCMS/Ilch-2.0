<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Layouts\rtx_layout01\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'name' => 'rtx_layout01',
        'version' => '1.0.0',
        'author' => 'RTX2070',
        'link' => 'https://ilch.de',
        'desc' => 'Bootstrap 5 Standard Layout (flexbox)',

        //'modulekey' => 'Name of Module'
    ];

    public function getUpdate($installedVersion)
    {
    }
}

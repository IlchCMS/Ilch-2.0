<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Install\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'install',
        'system_module' => true
    ];

    public function install()
    {

    }

    public function getUpdate($installedVersion)
    {

    }
}

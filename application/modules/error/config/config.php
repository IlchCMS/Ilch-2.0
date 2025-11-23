<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Error\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'error',
        'system_module' => true
    ];

    public function install()
    {
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}

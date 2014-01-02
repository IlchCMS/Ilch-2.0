<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Sample\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = '';
    public $author = '';
    public $name = array
    (
        'en_EN' => '',
        'de_DE' => '',
    );
    public $icon_small = '';

    public function install()
    {
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
    }
}



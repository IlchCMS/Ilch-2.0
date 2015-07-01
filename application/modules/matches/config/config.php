<?php
/**
 * @package ilch
 */

namespace Modules\Matches\Config;

class Matches extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'matches',
        'author' => 'Tobias Schwarz',
        'icon_small' => '',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Matches',
                'description' => '',
            ),
            'en_EN' => array
            (
                'name' => 'Matches',
                'description' => '',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        // TODO: Uninstall
    }

    public function getInstallSql()
    {
        // TODO: SQL Statements
        return '';
    }
}

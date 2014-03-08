<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Balthazar3k
 * @package eventplaner
 */

namespace Eventplaner\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'eventplaner';
    public $author = 'Balthazar3k';
    public $name = array
    (
        'en_EN' => 'Eventplaner',
        'de_DE' => 'Eventplaner',
    );
    public $icon_small = 'eventplaner.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        //$databaseConfig = new \Ilch\Config\Database($this->db());
        //$databaseConfig->set('eventplaner', '5');
        //$databaseConfig->set('shoutbox_maxwordlength', '10');
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
		$install = array(
		
			//"INSERT INTO `[prefix]_modules` (`key`, `icon_small`) VALUES ('eventplaner', 'eventplaner.png');"
		
		);
        return '';
    }
}

<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Checkout\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'checkout';
    public $author = 'Thomas Stantin';
    public $name = array
    (
        'en_EN' => 'Checkout',
        'de_DE' => 'Kasse',
    );
    public $icon_small = 'checkout.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('checkout_contact', '<p>Kontoinhaber: Max Mustermann</p><p>Bankname: Muster Sparkasse</p><p>Kontonummer: 123</p><p>Bankleitzahl: 123</p><p>BIC: 123</p><p>IBAN: 123</p><p>Verwendungszweck: Spende f&uuml;r ilch.de ;-)</p>');
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_checkout` (
                `id` int(14) NOT NULL AUTO_INCREMENT,
                `date_created` datetime NOT NULL,
                `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `usage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `amount` float NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
    }
}

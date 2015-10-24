<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'checkout',
        'author' => 'Stantin, Thomas',
        'icon_small' => 'checkout.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Kasse',
                'description' => 'Hier kann die Clan-Kasse gepflegt werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Checkout',
                'description' => 'Here you can manage your clan cash.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('checkout_contact', '<p>Kontoinhaber: Max Mustermann</p><p>Bankname: Muster Sparkasse</p><p>Kontonummer: 123</p><p>Bankleitzahl: 123</p><p>BIC: 123</p><p>IBAN: 123</p><p>Verwendungszweck: Spende f&uuml;r ilch.de ;-)</p>');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_checkout`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'checkout_contact'");
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

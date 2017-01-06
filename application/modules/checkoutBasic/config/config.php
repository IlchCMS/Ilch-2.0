<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\CheckoutBasic\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'checkoutBasic',
        'version' => '1.0',
        'icon_small' => 'fa-credit-card',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Kasse',
                'description' => 'Hier kann die Clan-Kasse gepflegt werden.',
            ],
            'en_EN' => [
                'name' => 'Checkout',
                'description' => 'Here you can manage your clan cash.',
            ],
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('checkoutBasic_contact', '<p>Kontoinhaber: Max Mustermann</p><p>Bankname: Muster Sparkasse</p><p>Kontonummer: 123</p><p>Bankleitzahl: 123</p><p>BIC: 123</p><p>IBAN: 123</p><p>Verwendungszweck: Spende f&uuml;r ilch.de ;-)</p>');
        $databaseConfig->set('checkoutBasic_currency', '1');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_checkoutBasic`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_checkoutBasic_currencies`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'checkoutBasic_contact'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'checkoutBasic_currency'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_checkoutBasic` (
                  `id` INT(14) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  `usage` VARCHAR(255) NOT NULL,
                  `amount` FLOAT NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_checkoutBasic_currencies` (
                  `id` INT(14) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_checkoutBasic_currencies` (`id`, `name`) VALUES (1, "EUR (€)");
                INSERT INTO `[prefix]_checkoutBasic_currencies` (`id`, `name`) VALUES (2, "USD ($)");
                INSERT INTO `[prefix]_checkoutBasic_currencies` (`id`, `name`) VALUES (3, "GBP (£)");
                INSERT INTO `[prefix]_checkoutBasic_currencies` (`id`, `name`) VALUES (4, "AUD ($)");
                INSERT INTO `[prefix]_checkoutBasic_currencies` (`id`, `name`) VALUES (5, "NZD ($)");
                INSERT INTO `[prefix]_checkoutBasic_currencies` (`id`, `name`) VALUES (6, "CHF");';
    }

    public function getUpdate()
    {

    }
}

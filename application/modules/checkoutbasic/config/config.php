<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkoutbasic\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'checkoutbasic',
        'version' => '1.6.0',
        'icon_small' => 'fa-regular fa-credit-card',
        'author' => 'Stantin, Thomas',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Kasse',
                'description' => 'Die Basisversion des Kassen-Moduls. Hier kann die Clan-Kasse gepflegt werden.',
            ],
            'en_EN' => [
                'name' => 'Checkout',
                'description' => 'The basic version of the checkout-module. Here you can manage your clan cash.',
            ],
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('checkoutbasic_contact', '<p>Kontoinhaber: Max Mustermann</p><p>Bankname: Muster Sparkasse</p><p>Kontonummer: 123</p><p>Bankleitzahl: 123</p><p>BIC: 123</p><p>IBAN: 123</p><p>Verwendungszweck: Spende f&uuml;r ilch.de ;-)</p>')
            ->set('checkoutbasic_currency', '1');
    }

    public function uninstall()
    {
        $this->db()->drop('checkoutbasic', true);
        $this->db()->drop('checkoutbasic_currencies', true);

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('checkoutbasic_contact')
            ->delete('checkoutbasic_currency');
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_checkoutbasic` (
                  `id` INT(14) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  `usage` VARCHAR(255) NOT NULL,
                  `amount` FLOAT NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_checkoutbasic_currencies` (
                  `id` INT(14) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_checkoutbasic_currencies` (`id`, `name`) VALUES (1, "EUR (€)");
                INSERT INTO `[prefix]_checkoutbasic_currencies` (`id`, `name`) VALUES (2, "USD ($)");
                INSERT INTO `[prefix]_checkoutbasic_currencies` (`id`, `name`) VALUES (3, "GBP (£)");
                INSERT INTO `[prefix]_checkoutbasic_currencies` (`id`, `name`) VALUES (4, "AUD ($)");
                INSERT INTO `[prefix]_checkoutbasic_currencies` (`id`, `name`) VALUES (5, "NZD ($)");
                INSERT INTO `[prefix]_checkoutbasic_currencies` (`id`, `name`) VALUES (6, "CHF");';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0":
            case "1.1":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_checkoutbasic` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_checkoutbasic_currencies` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                // no break
            case "1.2.0":
            case "1.3.0":
            case "1.4.0":
                $this->db()->update('modules')->values(['link' => $this->config['link']])->where(['key' => $this->config['key']])->execute();
                // no break
            case "1.4.1":
            case "1.4.2":
                $this->db()->update('modules')->values(['icon_small' => $this->config['icon_small']])->where(['key' => $this->config['key']])->execute();
            case "1.5.0":
        }

        return 'Update function executed.';
    }
}

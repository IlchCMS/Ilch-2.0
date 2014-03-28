<?php
/**
 * @package ilch
 */

namespace impressum\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'impressum',
        'author' => 'Veldscholten Kevin',
        'icon_small' => 'impressum.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Impressum',
                'description' => 'Hier kann das Impressum verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Impress',
                'description' => 'Here you can manage your impress.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_impressum`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_impressum` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `paragraph` varchar(100) NOT NULL,
                  `company` varchar(100) NOT NULL,
                  `name` varchar(100) NOT NULL,
                  `address` varchar(255) NOT NULL,
                  `city` varchar(255) NOT NULL,
                  `phone` varchar(255) NOT NULL,
                  `disclaimer` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        
                INSERT INTO `[prefix]_impressum` (`paragraph`, `name`, `address`, `city`, `disclaimer`) VALUES
                ("Angaben gemäß § 5 TMG:", "Max Mustermann", "Muster Str. 43", "12345 Musterhausen", "Haftungsausschluss");';
    }
}

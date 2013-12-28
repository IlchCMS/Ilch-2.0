<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Contact\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'contact';
    public $author = 'Meyer Dominik';
    public $name = array
    (
        'en_EN' => 'Contact',
        'de_DE' => 'Kontakt',
    );
    public $icon_small = 'contact.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $receiverMapper = new \Contact\Mappers\Receiver();
        $model = new \Contact\Models\Receiver();
        $userMapper = new \User\Mappers\User();
        $user = $userMapper->getUserById(1);
        $model->setName('Webmaster')->setEmail($user->getEmail());
        $receiverMapper->save($model);
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_contact_receivers` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}

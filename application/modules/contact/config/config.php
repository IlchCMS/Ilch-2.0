<?php
/**
 * @package ilch
 */

namespace Contact\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'contact',
        'author' => 'Meyer Dominik',
        'icon_small' => 'contact.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Kontakt',
                'description' => 'Hier können die Kontakte gepflegt werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Contact',
                'description' => 'Here you can manage your contacts.',
            ),
        )
    );

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
        $this->db()->queryMulti('DROP TABLE `[prefix]_contact_receivers`');
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

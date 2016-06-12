<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Contact\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'contact',
        'icon_small' => 'fa-envelope-o',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Kontakt',
                'description' => 'Hier können die Kontakte gepflegt werden.',
            ],
            'en_EN' => [
                'name' => 'Contact',
                'description' => 'Here you can manage your contacts.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $receiverMapper = new \Modules\Contact\Mappers\Receiver();
        $receiverModel = new \Modules\Contact\Models\Receiver();
        $userMapper = new \Modules\User\Mappers\User();
        $user = $userMapper->getUserById(1);
        $receiverModel->setName('Webmaster')->setEmail($user->getEmail());
        $receiverMapper->save($receiverModel);
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_contact_receivers` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(255) NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}

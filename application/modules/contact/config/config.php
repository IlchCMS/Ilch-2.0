<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Contact\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'contact',
        'icon_small' => 'fa-regular fa-envelope',
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

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('contact_welcomeMessage', '');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_contact_receivers` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(255) NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "2.1.42":
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('contact_welcomeMessage', '');
                break;
            case "2.1.50":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = 'fa-regular fa-envelope' WHERE `key` = 'contact';");
                break;
        }
    }
}

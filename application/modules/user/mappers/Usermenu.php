<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Usermenu as UserMenuModel;

class Usermenu extends \Ilch\Mapper
{
    /**
     * Gets the usermenu.
     * @param
     * @return null|\Modules\User\Models\Usermenu
     */
    public function getUserMenu()
    {
        $usermenu = array();
        $usermenuRows = $this->db()->select('*')
                ->from('user_menu')
                ->execute()
                ->fetchRows();

        if (empty($usermenuRows)) {
            return null;
        }

        foreach ($usermenuRows as $item) {
            $usermenuModel = new UserMenuModel();
            $usermenuModel->setId($item['id']);
            $usermenuModel->setKey($item['key']);
            $usermenuModel->setTitle($item['title']);
            $usermenu[] = $usermenuModel;
        }

        return $usermenu;
    }

    /**
     * Gets the usermenu.
     * @param
     * @return null|\Modules\User\Models\Usermenu
     */
    public function getUserMenuSettingsLinks()
    {
        $usermenu = array();
        $usermenuRows = $this->db()->select('*')
                ->from('user_menu_settings_links')
                ->execute()
                ->fetchRows();

        if (empty($usermenuRows)) {
            return null;
        }

        foreach ($usermenuRows as $item) {
            $usermenuModel = new UserMenuModel();
            $usermenuModel->setId($item['id']);
            $usermenuModel->setKey($item['key']);
            $usermenuModel->setTitle($item['title']);
            $usermenuModel->setText($item['text']);
            $usermenu[] = $usermenuModel;
        }

        return $usermenu;
    }
}

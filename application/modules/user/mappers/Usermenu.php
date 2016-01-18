<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Usermenu as UserMenuModel;
use Modules\User\Models\Usermenusettings as UserMenuSettingsModel;

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
     * Gets the usermenusettings.
     * @param
     * @return null|\Modules\User\Models\Usermenusettings
     */
    public function getUserMenuSettingsLinks($locale)
    {
        $usermenu = array();
        $usermenuRows = $this->db()->select('*')
                ->from('user_menu_settings_links')
                ->where(array('locale' => $locale))
                ->execute()
                ->fetchRows();

        if (empty($usermenuRows)) {
            return null;
        }

        foreach ($usermenuRows as $usermenuRow) {
            $usermenuModel = new UserMenuSettingsModel();
            $usermenuModel->setKey($usermenuRow['key']);
            $usermenuModel->setLocale($usermenuRow['locale']);
            $usermenuModel->setName($usermenuRow['name']);
            $usermenuModel->setDescription($usermenuRow['description']);

            $usermenu[] = $usermenuModel;
        }        

        return $usermenu;
    }
}

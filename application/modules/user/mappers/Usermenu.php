<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Usermenu as UserMenuModel;
use Modules\User\Models\Usermenusettings as UserMenuSettingsModel;

class Usermenu extends \Ilch\Mapper
{
    /**
     * Gets the usermenu.
     *
     * @return null|UserMenuModel[]
     */
    public function getUserMenu()
    {
        $usermenu = [];
        $usermenuRows = $this->db()->select('*')
                ->from('user_menu')
                ->order(['position' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($usermenuRows)) {
            return null;
        }

        foreach ($usermenuRows as $item) {
            $usermenuModel = new UserMenuModel();
            $usermenuModel->setId($item['id']);
            $usermenuModel->setKey($item['key']);
            $usermenuModel->setIcon($item['icon']);
            $usermenuModel->setPosition($item['position']);
            $usermenu[] = $usermenuModel;
        }

        return $usermenu;
    }

    /**
     * Gets the usermenusettings.
     * @param $locale
     * @return null|UserMenuSettingsModel[]
     */
    public function getUserMenuSettingsLinks($locale)
    {
        $usermenu = [];
        $usermenuRows = $this->db()->select('*')
                ->from('user_menu_settings_links')
                ->where(['locale' => $locale])
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

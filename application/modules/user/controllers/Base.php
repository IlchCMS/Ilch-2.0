<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\Usermenu as UserMenuMapper;
use Modules\User\Mappers\User as UserMapper;

/**
 * Handles the init for the user module.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
class Base extends \Ilch\Controller\Frontend
{
    /**
     * Initializes the menu.
     */
    public function init()
    {
        $UserMenuMapper = new UserMenuMapper();
        $profilMapper = new UserMapper();
        
        $menu = $UserMenuMapper->getUserMenu();
        $menuLinks = $UserMenuMapper->getUserMenuSettingsLinks($this->getTranslator()->getLocale());
        
        $this->getView()->set('usermenu', $menu);
        $this->getView()->set('usermenusettingslinks', $menuLinks);
        $this->getView()->set('profil', $profilMapper->getUserById($this->getUser()->getId()));
        $this->getView()->set('galleryAllowed', $this->getConfig()->get('usergallery_allowed'));
    }
}
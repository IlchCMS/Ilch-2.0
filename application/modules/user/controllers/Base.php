<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\Usermenu as UserMenuMapper;

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
        
        $menu = $UserMenuMapper->getUserMenu();
        $menuLinks = $UserMenuMapper->getUserMenuSettingsLinks($this->getTranslator()->getLocale());
        
        $this->getView()->set('usermenu', $menu);
        $this->getView()->set('usermenusettingslinks', $menuLinks);
    }
}
<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout;
defined('ACCESS') or die('no direct access');

class Frontend extends Base
{
    /**
     * Gets all the menus.
     * 
     * @return \Admin\Models\Menu[]
     */
    public function getMenus()
    {
        $menuMapper = new \Admin\Mappers\Menu();

        return $menuMapper->getMenus();
    }

    /**
     * Gets the menu for the given position.
     * 
     * @return \Admin\Models\Menu
     */
    public function getMenu($menu = 1)
    {
        $menuMapper = new \Admin\Mappers\Menu();

        return $menuMapper->getMenu($menuMapper->getMenuIdForPosition($menu));
    }

    /**
     * Gets page title from config or meta settings.
     *
     * @return string
     */
    public function getTitle()
    {
        $config = \Ilch\Registry::get('config');

        /*
         * @todo page modul handling
         */

        if (!empty($config) && $config->get('page_title') !== '') {
            return $this->escape($config->get('page_title'));
        } else {
            return 'Ilch '.VERSION.' Frontend';
        }
    }
}

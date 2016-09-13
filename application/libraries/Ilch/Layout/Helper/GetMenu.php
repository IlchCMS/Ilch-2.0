<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;

class GetMenu
{
    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Gets the menu for the given position.
     *
     * @param integer $menu
     * @param string $html
     * @return string
     */
    public function getMenu($args)
    {
        $menuId = $args[0];
        $template = '';
        $options = [
            'menus' => [
                'ul-class-root' => 'list-unstyled ilch_menu_ul',
                'ul-class-child' => 'list-unstyled ilch_menu_ul',
                'li-class-root' => '',
                'li-class-child' => '',
                'allow-nesting' => true,
            ],
            'boxes' => [
                'render' => true,
            ],
        ];

        $helperMapper = new \Ilch\Layout\Helper\Menu\Mapper($this->layout);
        $menuMapper = new \Modules\Admin\Mappers\Menu();

        $menu = $helperMapper->getMenu($menuMapper->getMenuIdForPosition($menuId));

        if (isset($args[1])) {
            $template = $args[1];
        }

        if (isset($args[2])) {
            $options = array_replace_recursive($options, $args[2]);
        }

        return $menu->getItems($template, $options);
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout;

use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;

class Admin extends Base
{
    /**
     * @var array
     */
    private $menus = [];

    /**
     * @var array
     */
    private $menuActions = [];

    /**
     * @var boolean
     */
    private $showSidebar = true;

    /**
     * @return array
     */
    public function getMenus(): array
    {
        return $this->menus;
    }

    /**
     * Adds layout helper.
     *
     * @todo adds helper dynamic from folder.
     * @param Request $request
     * @param Translator $translator
     * @param Router $router
     * @param string|null $baseUrl
     */
    public function __construct(Request $request, Translator $translator, Router $router, $baseUrl = null)
    {
        parent::__construct($request, $translator, $router, $baseUrl);

        $this->addHelper('getAdminHmenu', 'layout', new \Ilch\Layout\Helper\GetAdminHmenu($this));
    }

    /**
     * Add menu to layout.
     *
     * @param string $headKey
     * @param array  $items
     */
    public function addMenu($headKey, $items)
    {
        $this->menus[$headKey] = $items;
     }

    /**
     * Add menu action to layout.
     *
     * @param array $actionArray
     */
    public function addMenuAction($actionArray)
    {
        $this->menuActions[] = $actionArray;
    }

    /**
     * @return array
     */
    public function getMenuAction(): array
    {
        return $this->menuActions;
    }

    /**
     * Removes sidebar on the left side.
     */
    public function removeSidebar()
    {
        $this->showSidebar = false;
    }

    /**
     * Defines if sidebar is shown or not.
     *
     * @return boolean
     */
    public function hasSidebar(): bool
    {
        return $this->showSidebar;
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout;

use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;

/**
 * Class Admin
 * @package Ilch\Layout

 * @method \Ilch\Layout\Helper\AdminHmenu\Model getAdminHmenu() get the Admin Hmenu model
 */
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
     * @var bool
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
     * @param Request $request
     * @param Translator $translator
     * @param Router $router
     * @param string|null $baseUrl
     *@todo adds helper dynamic from folder.
     */
    public function __construct(Request $request, Translator $translator, Router $router, ?string $baseUrl = null)
    {
        parent::__construct($request, $translator, $router, $baseUrl);

        $this->addHelper('getAdminHmenu', 'layout', new \Ilch\Layout\Helper\GetAdminHmenu($this));
    }

    /**
     * Add menu to layout.
     *
     * @param string $headKey
     * @param array $items
     * @return $this
     */
    public function addMenu(string $headKey, array $items): Admin
    {
        $this->menus[$headKey] = $items;
        return $this;
    }

    /**
     * Add menu action to layout.
     *
     * @param array $actionArray
     * @return $this
     */
    public function addMenuAction(array $actionArray): Admin
    {
        $this->menuActions[] = $actionArray;
        return $this;
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
     * @return $this
     */
    public function removeSidebar(): Admin
    {
        $this->showSidebar = false;
        return $this;
    }

    /**
     * Defines if sidebar is shown or not.
     *
     * @return bool
     */
    public function hasSidebar(): bool
    {
        return $this->showSidebar;
    }
}

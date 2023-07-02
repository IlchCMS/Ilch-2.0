<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

abstract class Box extends Controller\Base
{
    /**
     * Hold the database adapter.
     *
     * @var \Ilch\Database\Mysql
     */
    private $db;

    /**
     * @var int
     */
    private $boxUniqid;

    /**
     * @var int
     */
    private static $staticBoxUniqid;

    /**
     * Injects the layout/view to the controller.
     *
     * @param Layout\Base $layout
     * @param View $view
     * @param Request $request
     * @param Router $router
     * @param Translator $translator
     */
    public function __construct(
        Layout\Base $layout,
        View $view,
        Request $request,
        Router $router,
        Translator $translator
    ) {
        parent::__construct($layout, $view, $request, $router, $translator);
        $this->db = Registry::get('db');

        if (isset(Box::$staticBoxUniqid)) {
            Box::$staticBoxUniqid++;
        } else {
            Box::$staticBoxUniqid = 0;
        }

        $this->boxUniqid = Box::$staticBoxUniqid;
    }

    /**
     * Gets uniqid for box.
     *
     * @return string
     */
    public function getUniqid(): string
    {
        return 'box_' . $this->boxUniqid;
    }

    /**
     * Shortcut for getDatabase
     *
     * @return \Ilch\Database\Mysql
     */
    public function db(): Database\Mysql
    {
        return $this->db;
    }

    /**
     * Dummy function for original redirect.
     *
     * @param array|string|null  $url
     * @param string|null $route
     * @throws \LogicException
     */
    public function redirect($url = [], string $route = null): Redirect
    {
        throw new \LogicException('php redirect is not possible in boxes');
    }

    /**
     * Prepare the view variables for rendering this box
     *
     * @return void
     */
    abstract public function render();
}

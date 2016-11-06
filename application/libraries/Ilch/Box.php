<?php
/**
 * @copyright Ilch 2.0
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
     * @var integer
     */
    private $boxUniqid;

    /**
     * @var integer
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

        if (!isset(Box::$staticBoxUniqid)) {
            Box::$staticBoxUniqid = 0;
        } else {
            Box::$staticBoxUniqid++;
        }

        $this->boxUniqid = Box::$staticBoxUniqid;
    }

    /**
     * Gets uniqid for box.
     *
     * @return string
     */
    public function getUniqid()
    {
        return 'box_' . $this->boxUniqid;
    }

    /**
     * Shortcut for getDatabase
     *
     * @return \Ilch\Database\Mysql
     */
    public function db()
    {
        return $this->db;
    }

    /**
     * Dummy function for original redirect.
     *
     * @param array $url
     * @param string $route
     * @param boolean $rewrite
     * @throws \LogicException
     */
    public function redirect($url = [], $route = null, $rewrite = false)
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

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Box extends \Ilch\Controller\Base
{
    /**
     * Hold the database adapter.
     *
     * @var Ilch_Database_*
     */
    private $_db;

    /**
     * Injects the layout/view to the controller.
     *
     * @param \Ilch\Layout\Base $layout
     * @param \Ilch\View        $view
     * @param \Ilch\Request     $request
     * @param \Ilch\Router      $router
     * @param Ilch_Translator   $translator
     */
    public function __construct(\Ilch\Layout\Base $layout, \Ilch\View $view, \Ilch\Request $request, \Ilch\Router $router, \Ilch\Translator $translator)
    {
        parent::__construct($layout, $view, $request, $router, $translator);
        $this->_db = Registry::get('db');
    }

    /**
     * Shortcut for getDatabse.
     *
     * @return Ilch_Database_*
     */
    public function db()
    {
        return $this->_db;
    }

    /**
     * Dummy function for original redirect.
     *
     * @param array $urlArray
     * @param string $route
     * @param boolean $rewrite
     */
    public function redirect($urlArray, $route = null, $rewrite = false)
    {
        throw new \LogicExceptionheader('php redirect is not possible in boxes');
    }
}

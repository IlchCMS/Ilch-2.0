<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Box extends \Ilch\Controller\Base
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
     * @param \Ilch\Layout\Base $layout
     * @param \Ilch\View        $view
     * @param \Ilch\Request     $request
     * @param \Ilch\Router      $router
     * @param \Ilch\Translator   $translator
     */
    public function __construct(\Ilch\Layout\Base $layout, \Ilch\View $view, \Ilch\Request $request, \Ilch\Router $router, \Ilch\Translator $translator)
    {
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
        return 'box_'.$this->boxUniqid;
    }

    /**
     * Shortcut for getDatabse.
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
     * @param array $urlArray
     * @param string $route
     * @param boolean $rewrite
     */
    public function redirect($urlArray, $route = null, $rewrite = false)
    {
        throw new \LogicExceptionheader('php redirect is not possible in boxes');
    }
}

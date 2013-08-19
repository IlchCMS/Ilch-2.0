<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Controller extends Ilch_Design_Abstract
{
    /**
     * Ilch_Layout $layout
     * Ilch_View type $view 
     */
    public function __construct($layout, $view)
    {
        $this->layout = $layout;
        $this->view = $view;
    }

    /**
     * redirect to given params
     * 
     * @param string $modul
     * @param string $controller
     * @param string $action
     * @param string $params 
     */
    public function redirect($modul = '', $controller = '', $action = '', $params = array())
    {
        header("location: ".$this->url($modul, $controller, $action, $params)); 
        exit;
    }

    public function message($msg)
    {
        $_SESSION['layout_messages'][] = $msg;
    }

    /**
     * adds a menuitem to the left menu
     * 
     * @param array $array 
     */
    public function addMenuHeader($array)
    {
        $this->layout->menu->headers[] = $array;
    }

    /**
     * adds a menudiver to the left menu
     * 
     * @param array $array 
     */
    public function addMenuDivider($array)
    {
        $this->layout->menu->dividers[] = $array;
    }
}
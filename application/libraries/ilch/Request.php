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
     * Injects the layout/view to the controller.
     *
     * Ilch_Layout $layout
     * Ilch_View type $view 
     */
    public function __construct($layout, $view)
    {
        $this->layout = $layout;
        $this->view = $view;
    }

    /**
     * Redirect to given params.
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
}
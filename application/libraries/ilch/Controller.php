<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Controller extends Ilch_Design_Abstract
{
    /**
     * Injects the layout/view to the controller.
     *
     * @param Ilch_Layout $layout
     * @param Ilch_View $view
     * @param Ilch_Plugin $plugin
     * @param Ilch_Request $request
     */
    public function __construct(Ilch_Layout $layout, Ilch_View $view, Ilch_Plugin $plugin, Ilch_Request $request)
    {
        $this->layout = $layout;
        $this->view = $view;
	$this->request = $request;
    }

    /**
     * Redirect to given params.
     * 
     * @param string $module
     * @param string $controller
     * @param string $action
     * @param string $params 
     */
    public function redirect($module = '', $controller = '', $action = '', $params = array())
    {
        header("location: ".$this->url($module, $controller, $action, $params)); 
        exit;
    }
}
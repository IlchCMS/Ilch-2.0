<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Controller
{
    /**
     * @var Ilch_Request 
     */
    private $_request;
    
    /**
     * @var Ilch_Translator 
     */
    private $_translator;
    
    /**
     * @var Ilch_Layout 
     */
    private $_layout;
    
    /**
     * @var Ilch_View 
     */
    private $_view;

    /**
     * Injects the layout/view to the controller.
     *
     * @param Ilch_Layout $layout
     * @param Ilch_View $view
     * @param Ilch_Plugin $plugin
     * @param Ilch_Request $request
     */
    public function __construct(Ilch_Layout $layout, Ilch_View $view, Ilch_Plugin $plugin, Ilch_Request $request, Ilch_Translator $translator)
    {
        $this->_layout = $layout;
        $this->_view = $view;
	$this->_request = $request;
	$this->_translator = $translator;
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
        header("location: ".$this->getLayout()->url($module, $controller, $action, $params)); 
        exit;
    }
    
    /**
     * Gets the request object.
     *
     * @return Ilch_Request
     */
    public function getRequest()
    {
	return $this->_request;
    }
    
    /**
     * Gets the translator object.
     *
     * @return Ilch_Translator
     */
    public function getTranslator()
    {
	return $this->_translator;
    }
    
    /**
     * Gets the layout object.
     *
     * @return Ilch_Layout
     */
    public function getLayout()
    {
	return $this->_layout;
    }
    
    /**
     * Gets the view object.
     *
     * @return Ilch_Request
     */
    public function getView()
    {
	return $this->_view;
    }
}
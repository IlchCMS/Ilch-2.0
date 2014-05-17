<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Controller;
defined('ACCESS') or die('no direct access');

class Base
{
    /**
     * @var Ilch_Request
     */
    private $request;

    /**
     * @var Ilch_Translator
     */
    private $translator;

    /**
     * @var Ilch_Layout_Base
     */
    private $layout;

    /**
     * @var Ilch_View
     */
    private $view;

    /**
     * The currently logged in user or null if the user is a guest.
     *
     * @var User_UserModel
     */
    private $user;

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
        $this->layout = $layout;
        $this->view = $view;
        $this->request = $request;
        $this->router = $router;
        $this->translator = $translator;
        $this->user = \Ilch\Registry::get('user');
    }

    /**
     * Redirect to given params.
     *
     * @param array   $urlArray
     * @param string  $route
     * @param boolean $rewrite
     */
    public function redirect($urlArray, $route = null, $rewrite = false)
    {
        header("location: ".$this->getLayout()->getUrl($urlArray, $route, $rewrite));
        exit;
    }

    /**
     * Gets the request object.
     *
     * @return Ilch_Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the router object.
     *
     * @return Ilch_Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Gets the config object.
     *
     * @return Ilch_Config
     */
    public function getConfig()
    {
        return \Ilch\Registry::get('config');
    }

    /**
     * Gets the translator object.
     *
     * @return Ilch_Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Gets the layout object.
     *
     * @return Ilch_Layout_Base
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Gets the view object.
     *
     * @return Ilch_Request
     */
    public function getView()
    {
        return $this->view;
    }    
    
    /**
     * Gets the user object.
     *
     * @return User_UserModel
     */
    public function getUser()
    {
        return \Ilch\Registry::get('user');
    }

    /**
     * Adds a flash message.
     *
     * @param string $message
     * @param string|null $type
     */
    public function addMessage($message, $type = 'success')
    {
        $_SESSION['messages'][] = array('text' => $message, 'type' => $type);
    }
}

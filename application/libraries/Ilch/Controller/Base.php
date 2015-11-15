<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Controller;

class Base
{
    /**
     * @var \Ilch\Request
     */
    private $request;

    /**
     * @var \Ilch\Translator
     */
    private $translator;

    /**
     * @var \Ilch\Layout\Base
     */
    private $layout;

    /**
     * @var \Ilch\View
     */
    private $view;

    /**
     * The currently logged in user or null if the user is a guest.
     *
     * @var \Modules\User\Models\User
     */
    private $user;

    /**
     * Injects the layout/view to the controller.
     *
     * @param \Ilch\Layout\Base $layout
     * @param \Ilch\View        $view
     * @param \Ilch\Request     $request
     * @param \Ilch\Router      $router
     * @param \Ilch\Translator   $translator
     */
    public function __construct(
        \Ilch\Layout\Base $layout,
        \Ilch\View $view,
        \Ilch\Request $request,
        \Ilch\Router $router,
        \Ilch\Translator $translator
    ) {
        $this->layout = $layout;
        $this->view = $view;
        $this->request = $request;
        $this->router = $router;
        $this->translator = $translator;
        $this->user = \Ilch\Registry::get('user');
    }

    /**
     * Redirect to given url or url params.
     *
     * @param array|string $url
     * @param string  $route
     * @param boolean $rewrite
     */
    public function redirect($url = array(), $route = null, $rewrite = false)
    {
        if (!is_string($url) || preg_match('~^[a-z]+://.*~', $url) === 0) {
            $url = $this->getLayout()->getUrl($url, $route, $rewrite);
        }
        header("Location: " . $url);
        exit;
    }

    /**
     * Gets the request object.
     *
     * @return \Ilch\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the router object.
     *
     * @return \Ilch\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Gets the config object.
     *
     * @return \Ilch\Config\Database
     */
    public function getConfig()
    {
        return \Ilch\Registry::get('config');
    }

    /**
     * Gets the translator object.
     *
     * @return \Ilch\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Gets the layout object.
     *
     * @return \Ilch\Layout\Base
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Gets the view object.
     *
     * @return \Ilch\View
     */
    public function getView()
    {
        return $this->view;
    }
    
    /**
     * Gets the user object.
     *
     * @return \Modules\User\Models\User
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

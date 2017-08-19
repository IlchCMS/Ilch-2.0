<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Controller;

use Ilch\Event;

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
     */
    public function redirect($url = null, $route = null)
    {
        $redirector = new \Ilch\Redirect($this->getRequest());

        if (!is_null($url)) {
            $redirector->to($url, $route);
        }

        return $redirector;
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
     * @param bool|false|string $validationError
     */
    public function addMessage($message, $type = 'success', $validationError = false)
    {
        if ($validationError == true) {
            $_SESSION['messages'][] = ['text' => $message, 'type' => $type, 'validationError' => $validationError];
        } else {
            if (!is_array($message)) {
                $_SESSION['messages'][] = ['text' => $this->getTranslator()->trans($message), 'type' => $type];
            }
        }
    }

    /**
     * Simple helper for triggering events
     * @param string $event
     * @param array $args
     */
    protected function trigger($event, array $args) {
        trigger($event, new Event($event, $args));
    }
}

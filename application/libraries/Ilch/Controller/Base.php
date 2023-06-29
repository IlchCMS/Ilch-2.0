<?php

/**
 * @copyright Ilch 2
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
     * @var \Ilch\Router
     */
    private $router;

    /**
     * @var \Ilch\Translator
     */
    private $translator;

    /**
     * @var \Ilch\Layout\Base|\Ilch\Layout\Admin|\Ilch\Layout\Frontend
     */
    private $layout;

    /**
     * @var \Ilch\View
     */
    private $view;

    /**
     * The currently logged in user or null if the user is a guest.
     *
     * @var \Modules\User\Models\User|null
     */
    private $user;

    /**
     * Injects the layout/view to the controller.
     *
     * @param \Ilch\Layout\Base|\Ilch\Layout\Admin|\Ilch\Layout\Frontend $layout
     * @param \Ilch\View        $view
     * @param \Ilch\Request     $request
     * @param \Ilch\Router      $router
     * @param \Ilch\Translator  $translator
     */
    public function __construct(
        $layout,
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
     * @param array|string|null  $url
     * @param string|null $route
     * @return \Ilch\Redirect
     */
    public function redirect($url = null, ?string $route = null): \Ilch\Redirect
    {
        $redirector = new \Ilch\Redirect($this->getRequest());

        if ($url !== null) {
            $redirector->to($url, $route);
        }

        return $redirector;
    }

    /**
     * Gets the request object.
     *
     * @return \Ilch\Request
     */
    public function getRequest(): \Ilch\Request
    {
        return $this->request;
    }

    /**
     * Gets the router object.
     *
     * @return \Ilch\Router
     */
    public function getRouter(): \Ilch\Router
    {
        return $this->router;
    }

    /**
     * Gets the config object.
     *
     * @return \Ilch\Config\Database
     */
    public function getConfig(): \Ilch\Config\Database
    {
        return \Ilch\Registry::get('config');
    }

    /**
     * Gets the translator object.
     *
     * @return \Ilch\Translator
     */
    public function getTranslator(): \Ilch\Translator
    {
        return $this->translator;
    }

    /**
     * Gets the layout object.
     *
     * @return \Ilch\Layout\Base|\Ilch\Layout\Admin|\Ilch\Layout\Frontend
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
    public function getView(): \Ilch\View
    {
        return $this->view;
    }

    /**
     * Gets the user object.
     *
     * @return \Modules\User\Models\User|null
     */
    public function getUser(): ?\Modules\User\Models\User
    {
        return \Ilch\Registry::get('user');
    }

    /**
     * Adds a flash message.
     *
     * @param string|array      $message
     * @param string|null $type
     * @param bool|false|string $validationError
     */
    public function addMessage($message, ?string $type = 'success', $validationError = false)
    {
        if ($validationError && is_array($message)) {
            $_SESSION['messages'][] = ['text' => $message, 'type' => $type, 'validationError' => $validationError];
        } elseif (!is_array($message)) {
            $_SESSION['messages'][] = ['text' => $this->getTranslator()->trans($message), 'type' => $type];
        }
    }

    /**
     * Gets the default URL.
     *
     * @param string|null $page
     * @return string
     * @since 2.1.43
     */
    public function getDefaultUrl(?string $page = null): string
    {
        if (!$page) {
            $page = $this->getConfig()->get('start_page');
        }

        $newRouter = new \Ilch\Router(new \Ilch\Request());
        $newRouter->defineStartPage($page, $this->getTranslator());

        $newRedirect = new \Ilch\Redirect($newRouter->getRequest());
        if ($newRouter->getRequest()->getControllerName() === 'page') {
            $pageMapper = new \Modules\Admin\Mappers\Page();
            $pageModel = $pageMapper->getPageByIdLocale($newRouter->getRequest()->getParam('id'), $newRouter->getRequest()->getParam('locale'));
            return $newRedirect->getUrl($pageModel->getPerma());
        } else {
            return substr($newRedirect->getUrl(['#']), 0, -4);
        }
    }
}

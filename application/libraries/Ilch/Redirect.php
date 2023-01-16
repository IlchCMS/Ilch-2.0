<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

class Redirect
{
    /**
     * The current request instance
     *
     * @var Request
     */
    protected $request;

    /**
     * The translator instance
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Flash messages translation keys
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Input for the next request.
     *
     * @var array
     */
    protected $input = [];

    /**
     * The errors for the next request.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor
     *
     * @param Request $request Request instance
     * @param null|Translator $translator Translator instance
     */
    public function __construct(Request $request, ?Translator $translator = null)
    {
        $this->request = $request;
        if ($translator) {
            $this->translator = $translator;
        } else {
            $this->translator = Registry::get('translator');
        }
    }

    /**
     * Adds a flash message
     *
     * @param string $message Translation key for the flash message
     * @param string $type
     * @return $this
     * @throws \Exception
     */
    public function withMessage(string $message, string $type = 'success'): Redirect
    {
        $this->messages[] = ['text' => $message, 'type' => $type];

        return $this;
    }

    /**
     * Sets the input for the next request.
     *
     * @param array|null $input Input data
     * @return $this
     */
    public function withInput(?array $input = null): Redirect
    {
        if ($input === null) {
            $input = $this->request->getPost();
        }

        $this->input = array_merge($this->input, $input);

        return $this;
    }

    /**
     * Sets the errors for the next request.
     *
     * @param \Ilch\Validation\ErrorBag $errorBag The errorBag instance
     *
     * @return $this
     */
    public function withErrors(\Ilch\Validation\ErrorBag $errorBag): Redirect
    {
        $this->errors = array_merge($this->errors, $errorBag->getErrors());

        return $this;
    }

    /**
     * Redirects the user to $destination.
     *
     * @param mixed $destination
     * @param bool|null $route
     * @param int $status
     * @param array $headers
     * @return $this
     */
    public function to($destination, ?bool $route = null, int $status = 302, array $headers = []): Redirect
    {
        if (!is_string($destination) || preg_match('~^[a-z]+://.*~', $destination) === 0) {
            $destination = $this->getUrl($destination, $route);
        }

        $this->perform($destination, $status, $headers);
        return $this;
    }

    /**
     * Redirects to the desired location.
     *
     * @param string $destination
     * @param int $status HTTP Status Code
     * @param array $headers An array with headers
     * @return void
     */
    protected function perform(string $destination = '/', int $status = 302, array $headers = [])
    {
        if (!empty($this->errors)) {
            array_dot_set($_SESSION, 'ilch_validation_errors', $this->errors);
        }

        if (!empty($this->input)) {
            array_dot_set($_SESSION, 'ilch_old_input', $this->input);
        }

        if (!empty($this->messages)) {
            $messages = array();

            foreach ($this->messages as $message) {
                $messages[] = [
                    'text' => $this->translator->trans($message['text']),
                    'type' => $message['type'],
                ];
            }

            if (is_array(array_dot($_SESSION, 'messages'))) {
                array_dot_set($_SESSION, 'messages', array_merge(array_dot($_SESSION, 'messages'), $messages));
            } else {
                array_dot_set($_SESSION, 'messages', $messages);
            }
        }

        foreach ($headers as $header) {
            header($header);
        }

        header('Location: '.$destination, true, $status);
        exit;
    }

    /**
     * Creates a full url for the given parts.
     *
     * @param array|string $url
     * @param string|null $route
     *
     * @return string
     */
    public function getUrl($url = [], ?string $route = null): string
    {
        $config = Registry::get('config');
        $locale = '';
        $modRewrite = false;

        if ($config !== null) {
            $modRewrite = (bool)$config->get('mod_rewrite');

            if ($config->get('multilingual_acp') && $this->translator->getLocale() != $config->get('content_language')) {
                $locale = $this->translator->getLocale();
            }
        }

        if (empty($url)) {
            return BASE_URL;
        }

        if (is_string($url)) {
            return BASE_URL . '/index.php/' . $url;
        }

        $urlParts = [];

        if (isset($url['module']) && $url['module'] === 'admin' && isset($url['controller']) && $url['controller'] === 'page' && isset($url['action']) && $url['action'] === 'show' && isset($url['id'])) {
            $pageMapper = new \Modules\Admin\Mappers\Page();
            $page = $pageMapper->getPageByIdLocale((int)$url['id'], $locale);
            if (!$page) {
                $page = $pageMapper->getPageByIdLocale((int)$url['id']);
            }
            $urlParts[] = $page ? $page->getPerma() : '';
            unset($url['module'], $url['controller'], $url['action'], $url['id']);
        } else {
            if (isset($url['module'])) {
                $urlParts[] = $url['module'];
                unset($url['module']);
            } else {
                $urlParts[] = $this->request->getModuleName();
            }

            if (isset($url['controller'])) {
                $urlParts[] = $url['controller'];
                unset($url['controller']);
            } else {
                $urlParts[] = $this->request->getControllerName();
            }

            if (isset($url['action'])) {
                $urlParts[] = $url['action'];
                unset($url['action']);
            } else {
                $urlParts[] = $this->request->getActionName();
            }
        }

        foreach ($url as $key => $value) {
            $urlParts[] = $key.'/'.$value;
        }

        if ($this->request->isAdmin() && $route === null) {
            $route = 'admin';
        }

        $prefix = '';
        
        if ($route !== null && $route !== 'frontend') {
            $prefix = $route. '/';
        }

        if ($modRewrite) {
            return BASE_URL.'/'.$prefix.implode('/', $urlParts);
        }

        return BASE_URL.'/index.php/'.$prefix.implode('/', $urlParts);
    }
}

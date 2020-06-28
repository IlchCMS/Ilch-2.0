<?php

namespace Ilch;

class Redirect
{
    /**
     * The current request instance
     *
     * @var \Ilch\Request
     */
    protected $request;

    /**
     * The translator instance
     *
     * @var \Ilch\Translator
     */
    protected $translator;

    /**
     * Flash messages translation keys
     *
     * @var string
     */
    protected $messages = array();

    /**
     * Input for the next request.
     *
     * @var array
     */
    protected $input = array();

    /**
     * The errors for the next request.
     *
     * @var array
     */
    protected $errors = array();

    /**
     * Constructor
     *
     * @param \Ilch\Request $request Request instance
     */
    public function __construct($request)
    {
        $this->request = $request;
        $this->translator = \Ilch\Registry::get('translator');
    }

    /**
     * Adds a flash message
     *
     * @param  string $message Translation key for the flash message
     * @param string $type
     * @return self
     * @throws \Exception
     */
    public function withMessage($message, $type = 'success')
    {
        if (!is_string($message)) {
            throw new \RuntimeException('Wrong parameter type: expected string, got ' .gettype($message));
        }

        $this->messages[] = ['text' => $message, 'type' => $type];

        return $this;
    }

    /**
     * Sets the input for the next request.
     *
     * @param array|null $input Input data
     * @return self
     */
    public function withInput($input = null)
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
     * @return self
     */
    public function withErrors(\Ilch\Validation\ErrorBag $errorBag)
    {
        $this->errors = array_merge($this->errors, $errorBag->getErrors());

        return $this;
    }

    /**
     * Redirects the user to $destination.
     *
     * @param mixed $destination
     * @param bool  $route
     * @param int   $status
     * @param array $headers
     */
    public function to($destination, $route = null, $status = 302, $headers = [])
    {
        if (!is_string($destination) || preg_match('~^[a-z]+://.*~', $destination) === 0) {
            $destination = $this->getUrl($destination, $route);
        }

        $this->perform($destination, $status, $headers);
    }

    /**
     * Redirects to the desired location.
     *
     * @param string $destination
     * @param int $status HTTP Status Code
     * @param array $headers An array with headers
     */
    protected function perform($destination = '/', $status = 302, $headers = [])
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
     * @param string       $route
     *
     * @return string
     */
    public function getUrl($url = [], $route = null)
    {
        $config = \Ilch\Registry::get('config');

        $modRewrite = false;

        if ($config !== null) {
            $modRewrite = (bool) $config->get('mod_rewrite');
        }

        if (empty($url)) {
            return BASE_URL;
        }

        if (is_string($url)) {
            return BASE_URL.'/index.php/'.$url;
        }

        $urlParts = [];

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

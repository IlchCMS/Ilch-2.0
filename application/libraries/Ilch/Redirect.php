<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch;

/**
 * Class to build a redirect.
 */
class Redirect
{
    /**
     * The request instance.
     *
     * @var \Ilch\Request
     */
    protected $request;

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
     * Constructor.
     *
     * @param \Ilch\Request $request The current request instance
     */
    public function __construct(\Ilch\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Sets the input for the next request.
     *
     * @param array $input Input data
     *
     * @return self
     */
    public function withInput(array $input)
    {
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
     * @param string    $default Default location
     * @param int       $status  HTTP Status Code
     * @param array     $headers An array with headers
     * @param bool|null $secure  Whether or not it is a secure request
     */
    protected function perform($destination = '/', $status = 302, $headers = [])
    {
        if (!empty($this->errors)) {
            $_SESSION['ilch_validation_errors'] = $this->errors;
        }

        if (!empty($this->input)) {
            $_SESSION['ilch_old_input'] = $this->input;
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

        if (!isset($url['module'])) {
            $urlParts[] = $this->request->getModuleName();
        } else {
            $urlParts[] = $url['module'];
            unset($url['module']);
        }

        if (!isset($url['controller'])) {
            $urlParts[] = $this->request->getControllerName();
        } else {
            $urlParts[] = $url['controller'];
            unset($url['controller']);
        }

        if (!isset($url['action'])) {
            $urlParts[] = $this->request->getActionName();
        } else {
            $urlParts[] = $url['action'];
            unset($url['action']);
        }

        foreach ($url as $key => $value) {
            $urlParts[] = $key.'/'.$value;
        }

        $s = '';

        if (($this->request->isAdmin() && $route === null) || ($route !== null && $route == 'admin')) {
            $s = 'admin/';
        }

        if ($modRewrite && empty($s)) {
            return BASE_URL.'/'.$s.implode('/', $urlParts);
        } else {
            return BASE_URL.'/index.php/'.$s.implode('/', $urlParts);
        }
    }
}

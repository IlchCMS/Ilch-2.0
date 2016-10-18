<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Request
{
    /**
     * @var boolean
     */
    protected $isAdmin = false;

    /**
     * @var boolean
     */
    protected $isAjax = false;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var string
     */
    protected $actionName;

    /**
     * @var array
     */
    protected $params;

    /**
     * Form input from the last request.
     *
     * @var array
     */
    protected $oldInput;

    /**
     * Validation errors from the last request.
     *
     * @var \Ilch\Validation\ErrorBag
     */
    protected $validationErrors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->oldInput = array();
        $this->validationErrors = new \Ilch\Validation\ErrorBag();

        $this->checkForOldInput();
        $this->checkForValidationErrors();
    }

    /**
     * Checks the session for old input data.
     */
    public function checkForOldInput()
    {
        if (isset($_SESSION['ilch_old_input'])) {
            $this->oldInput = $_SESSION['ilch_old_input'];
            unset($_SESSION['ilch_old_input']);
        }
    }

    /**
     * Checks the session for validation errors.
     */
    public function checkForValidationErrors()
    {
        if (isset($_SESSION['ilch_validation_errors'])) {
            $this->validationErrors->setErrors($_SESSION['ilch_validation_errors']);
            unset($_SESSION['ilch_validation_errors']);
        }
    }

    /**
     * Returns the old input for the given key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOldInput($key = null, $default = '')
    {
        return array_dot($this->oldInput, $key, $default);
    }

    /**
     * Returns the validation errorbag.
     *
     * @return \Ilch\Validation\ErrorBag
     */
    public function getErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Gets admin request flag.
     *
     * @return string
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Sets admin request flag.
     *
     * @param boolean $admin
     */
    public function setIsAdmin($admin)
    {
        $this->isAdmin = $admin;
    }

    /**
     * Gets Ajax request flag.
     *
     * @return boolean
     */
    public function isAjax()
    {
        if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") || $this->isAjax) {
            return true;
        }

        return false;
    }

    /**
     * Sets ajax request flag.
     *
     * @param boolean $ajax
     */
    public function setIsAjax($ajax)
    {
        $this->isAjax = $ajax;
    }

    /**
     * Gets the current module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * Sets the current module name.
     *
     * @param string $name
     */
    public function setModuleName($name)
    {
        $this->moduleName = $name;
    }

    /**
     * Gets the current controller name.
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Sets the current controller name.
     *
     * @param string $name
     */
    public function setControllerName($name)
    {
        $this->controllerName = $name;
    }

    /**
     * Gets the current action name.
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Sets the current action name.
     *
     * @param string $name
     */
    public function setActionName($name)
    {
        $this->actionName = $name;
    }

    /**
     * Gets param with given key.
     *
     * @return string|null
     */
    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        return null;
    }

    /**
     * Sets the param with the given key / value.
     *
     * @param string $name
     * @param string $value
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Get all key/value params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set key/value params.
     *
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Checks if request is a POST - request.
     *
     * @return boolean
     */
    public function isPost()
    {
        return !empty($_POST);
    }

    /**
     * Gets post-value by key.
     *
     * Supports 'dot' notation for arrays
     * e.g.
     *      foo.bar     > foo['bar']
     *      foo.bar.baz > foo['bar']['baz']
     *
     * @param  string $key
     * @param  string $default This gets returned if $key does not exist
     * @return string|null
     */
    public function getPost($key = null, $default = null)
    {
        return array_dot($_POST, $key, $default);
    }

    /**
     * Get get-value by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function getQuery($key = '')
    {
        if ($key === '') {
            return $_GET;
        } elseif (isset($_GET[$key])) {
            return $_GET[$key];
        } else {
            return null;
        }
    }

    /**
     * Checks if request is secure.
     *
     * @return boolean
     */
    public function isSecure()
    {
        $returnValue = false;

        if (isset($_SESSION['token'][$this->getPost('ilch_token')])
               || isset($_SESSION['token'][$this->getParam('ilch_token')])) {
            $returnValue = true;
        }

        // Delete the used tokens.
        // Just delete the token used in a GET-Request to avoid "no valid secure token given, add function getTokenField() to formular".
        // unset($_SESSION['token'][$this->getPost('ilch_token')]);
        if (!$this->isPost()) {
            unset($_SESSION['token'][$this->getParam('ilch_token')]);
        }

        return $returnValue;
    }
}

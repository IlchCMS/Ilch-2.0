<?php

/**
 * @copyright Ilch 2
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
    protected $moduleName = '';

    /**
     * @var string
     */
    protected $controllerName = '';

    /**
     * @var string
     */
    protected $actionName = '';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Form input from the last request.
     *
     * @var array
     */
    protected $oldInput = [];

    /**
     * Validation errors from the last request.
     *
     * @var \Ilch\Validation\ErrorBag
     */
    protected $validationErrors;

    /**
     * Constructor
     * @param bool $check
     */
    public function __construct(bool $check = true)
    {
        $this->validationErrors = new \Ilch\Validation\ErrorBag();

        if ($check) {
            $this->checkForOldInput();
            $this->checkForValidationErrors();
        }
    }

    /**
     * Checks the session for old input data.
     * @return $this
     */
    public function checkForOldInput(): Request
    {
        if (isset($_SESSION['ilch_old_input'])) {
            $this->oldInput = $_SESSION['ilch_old_input'];
            unset($_SESSION['ilch_old_input']);
        }
        return $this;
    }

    /**
     * Checks the session for validation errors.
     * @return $this
     */
    public function checkForValidationErrors(): Request
    {
        if (isset($_SESSION['ilch_validation_errors'])) {
            $this->validationErrors->setErrors($_SESSION['ilch_validation_errors']);
            unset($_SESSION['ilch_validation_errors']);
        }
        return $this;
    }

    /**
     * Returns the old input for the given key.
     *
     * @param string|null $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOldInput(?string $key = null, $default = '')
    {
        return array_dot($this->oldInput, $key, $default);
    }

    /**
     * Returns the validation errorbag.
     *
     * @return \Ilch\Validation\ErrorBag
     */
    public function getErrors(): Validation\ErrorBag
    {
        return $this->validationErrors;
    }

    /**
     * Gets admin request flag.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * Sets admin request flag.
     *
     * @param boolean $admin
     * @return $this
     */
    public function setIsAdmin(bool $admin): Request
    {
        $this->isAdmin = $admin;
        return $this;
    }

    /**
     * Gets Ajax request flag.
     *
     * @return boolean
     */
    public function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || $this->isAjax;
    }

    /**
     * Sets ajax request flag.
     *
     * @param boolean $ajax
     * @return $this
     */
    public function setIsAjax(bool $ajax): Request
    {
        $this->isAjax = $ajax;
        return $this;
    }

    /**
     * Gets the current module name.
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * Sets the current module name.
     *
     * @param string $name
     * @return $this
     */
    public function setModuleName(string $name): Request
    {
        $this->moduleName = $name;
        return $this;
    }

    /**
     * Gets the current controller name.
     *
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * Sets the current controller name.
     *
     * @param string $name
     * @return $this
     */
    public function setControllerName(string $name): Request
    {
        $this->controllerName = $name;
        return $this;
    }

    /**
     * Gets the current action name.
     *
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * Sets the current action name.
     *
     * @param string $name
     * @return $this
     */
    public function setActionName(string $name): Request
    {
        $this->actionName = $name;
        return $this;
    }

    /**
     * Gets param with given key.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getParam(string $key, ?string $default = null): ?string
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Sets the param with the given key / value.
     *
     * @param string $key
     * @param string|null $value
     * @return $this
     */
    public function setParam(string $key, ?string $value): Request
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Unsets/deletes the param with the given key / value.
     *
     * @param string $key
     * @return $this
     */
    public function unsetParam(string $key): Request
    {
        if (isset($this->params[$key])) {
            unset($this->params[$key]);
        }
        return $this;
    }

    /**
     * Get all key/value params.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set key/value params.
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): Request
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Checks if request is a POST - request.
     *
     * @return bool
     */
    public function isPost(): bool
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
     * @param string|null $key
     * @param  mixed $default This gets returned if $key does not exist
     * @return mixed
     */
    public function getPost(?string $key = null, $default = null)
    {
        return array_dot($_POST, $key, $default);
    }

    /**
     * Get get-value by key.
     *
     * @param string $key
     * @return mixed
     */
    public function getQuery(string $key = '')
    {
        if ($key === '') {
            return $_GET;
        }

        return $_GET[$key] ?? null;
    }

    /**
     * Checks if request is secure.
     *
     * @return boolean
     */
    public function isSecure(): bool
    {
        // Return false if ilch_token is empty or not a string.
        // Fixes "Illegal offset type in isset or empty"
        if ($this->isPost()) {
            if (empty($this->getPost('ilch_token')) || !is_string($this->getPost('ilch_token'))) {
                return false;
            }
        } elseif (empty($this->getParam('ilch_token'))) {
            return false;
        }

        $returnValue = false;

        if (isset($_SESSION['token'][$this->getPost('ilch_token')]) || isset($_SESSION['token'][$this->getParam('ilch_token')])) {
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

    /**
     * Get an array of the current and provided url parts. Optionally reset parameters.
     *
     * @param array $urlParts
     * @param bool $resetParams
     * @return array
     * @since 2.1.46
     */
    public function getArray(array $urlParts = [], bool $resetParams = false): array
    {
        $currentUrlParts = [];

        if (array_key_exists('module', $urlParts)) {
            $currentUrlParts['module'] = $urlParts['module'];
            unset($urlParts['module']);
        } elseif ($this->getModuleName()) {
            $currentUrlParts['module'] = $this->getModuleName();
        }

        if (array_key_exists('controller', $urlParts)) {
            $currentUrlParts['controller'] = $urlParts['controller'];
            unset($urlParts['controller']);
        } elseif ($this->getModuleName()) {
            $currentUrlParts['controller'] = $this->getControllerName();
        }

        if (array_key_exists('action', $urlParts)) {
            $currentUrlParts['action'] = $urlParts['action'];
            unset($urlParts['action']);
        } elseif ($this->getModuleName()) {
            $currentUrlParts['action'] = $this->getActionName();
        }

        $params = $this->getParams();
        if (empty($params)) {
            $resetParams = true;
        }

        return array_merge(
            $currentUrlParts,
            $resetParams ? [] : $params,
            $urlParts
        );
    }
}

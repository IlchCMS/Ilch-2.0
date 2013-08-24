<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Request
{
    /**
     * @var string
     */
    protected $_moduleName;

    /**
     * @var string
     */
    protected $_controllerName;

    /**
     * @var string
     */
    protected $_actionName;

    /**
     * @var array
     */
    protected $_params;

    /**
     * Gets the current module name.
     *
     * @return string
     */
    public function getModuleName()
    {
	return $this->_moduleName;
    }

    /**
     * Sets the current module name.
     *
     * @param string $name
     */
    public function setModuleName($name)
    {
	$this->_moduleName = $name;
    }

    /**
     * Gets the current controller name.
     *
     * @return string
     */
    public function getControllerName()
    {
	return $this->_controllerName;
    }

    /**
     * Sets the current controller name.
     *
     * @param string $name
     */
    public function setControllerName($name)
    {
	$this->_controllerName = $name;
    }

    /**
     * Gets the current action name.
     *
     * @return string
     */
    public function getActionName()
    {
	return $this->_actionName;
    }

    /**
     * Sets the current action name.
     *
     * @param string $name
     */
    public function setActionName($name)
    {
	$this->_actionName = $name;
    }

    /**
     * Gets param with given key.
     *
     * @return string
     */
    public function getParam($key)
    {
	return $this->_params[$key];
    }

    /**
     * Sets the param with the given key / value.
     *
     * @param string $name
     * @param string $value
     */
    public function setParam($key, $value)
    {
	$this->_params[$key] = $value;
    }

    /**
     * Get all key/value params.
     *
     * @return array
     */
    public function getParams()
    {
	return $this->_params;
    }
    
    /**
     * Set key/value params.
     *
     * @param array $params
     */
    public function setParams($params)
    {
	$this->_params = $params;
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
     * Get post-value by key.
     *
     * @return array
     */
    public function getPost($key)
    {
	if(isset($_POST[$key]))
	{
	    return $_POST[$key];
	}
	else
	{
	    return null;
	}
    }

    /**
     * Get get-value by key.
     *
     * @return array
     */
    public function getQuery($key)
    {
	if(isset($_GET[$key]))
	{
	    return $_GET[$key];
	}
	else
	{
	    return null;
	}
    }
}
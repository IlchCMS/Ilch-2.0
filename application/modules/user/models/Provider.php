<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Provider extends \Ilch\Model
{
    /**
     * Unique key
     */
    protected $key;

    /**
     * Human readable name
     */
    protected $name;

    /**
     * Font-awesome icon
     */
    protected $icon;

    /**
     * Module providing the auth functionality of this provider
    */
    protected $module;

    /**
     * Controller that performs the authentication
     */
    protected $auth_controller;

    /**
     * Action that performs the authentication
     */
    protected $auth_action;

    /**
     * Controller that performs the unlink
     */
    protected $unlink_controller;

    /**
     * Action that performs the unlink
     */
    protected $unlink_action;

    /**
     * Constructor
     */
    public function __construct($params = [])
    {
        //
    }

    /**
     * Gets the Unique key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the Unique key.
     *
     * @param mixed $key the key
     *
     * @return self
     */
    protected function setKey($key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the Human readable name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Human readable name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    protected function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Font-awesome icon.
     *
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the Font-awesome icon.
     *
     * @param mixed $icon the icon
     *
     * @return self
     */
    protected function setIcon($icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets the Module providing the auth functionality of this provider.
     *
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets the Module providing the auth functionality of this provider.
     *
     * @param mixed $module the module
     *
     * @return self
     */
    protected function setModule($module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Gets the value of auth_controller.
     *
     * @return mixed
     */
    public function getAuthController()
    {
        return $this->auth_controller;
    }

    /**
     * Sets the value of auth_controller.
     *
     * @param mixed $auth_controller the auth controller
     *
     * @return self
     */
    protected function setAuthController($auth_controller): self
    {
        $this->auth_controller = $auth_controller;

        return $this;
    }

    /**
     * Gets the value of auth_action.
     *
     * @return mixed
     */
    public function getAuthAction()
    {
        return $this->auth_action;
    }

    /**
     * Sets the value of auth_action.
     *
     * @param mixed $auth_action the auth action
     *
     * @return self
     */
    protected function setAuthAction($auth_action): self
    {
        $this->auth_action = $auth_action;

        return $this;
    }

    /**
     * Gets the value of unlink_controller.
     *
     * @return mixed
     */
    public function getUnlinkController()
    {
        return $this->unlink_controller;
    }

    /**
     * Sets the value of unlink_controller.
     *
     * @param mixed $unlink_controller the unlink controller
     *
     * @return self
     */
    protected function setUnlinkController($unlink_controller): self
    {
        $this->unlink_controller = $unlink_controller;

        return $this;
    }

    /**
     * Gets the value of unlink_action.
     *
     * @return mixed
     */
    public function getUnlinkAction()
    {
        return $this->unlink_action;
    }

    /**
     * Sets the value of unlink_action.
     *
     * @param mixed $unlink_action the unlink action
     *
     * @return self
     */
    protected function setUnlinkAction($unlink_action): self
    {
        $this->unlink_action = $unlink_action;

        return $this;
    }
}

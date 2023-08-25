<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class Provider extends \Ilch\Model
{
    /**
     * Unique key
     * @var string
     */
    protected $key;

    /**
     * Human readable name
     * @var string
     */
    protected $name;

    /**
     * Font-awesome icon
     * @var string
     */
    protected $icon;

    /**
     * Module providing the auth functionality of this provider
     * @var string
    */
    protected $module;

    /**
     * Controller that performs the authentication
     * @var string
     */
    protected $auth_controller;

    /**
     * Action that performs the authentication
     * @var string
     */
    protected $auth_action;

    /**
     * Controller that performs the unlink
     * @var string
     */
    protected $unlink_controller;

    /**
     * Action that performs the unlink
     * @var string
     */
    protected $unlink_action;

    /**
     * The localised module name.
     * @var string
     */
    protected $module_name;

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
     * @return string
     */
    public function getKey(): string
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
    protected function setKey($key): Provider
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the Human readable name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the Human readable name.
     *
     * @param string $name the name
     *
     * @return self
     */
    protected function setName(string $name): Provider
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Font-awesome icon.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Sets the Font-awesome icon.
     *
     * @param string $icon the icon
     *
     * @return self
     */
    protected function setIcon(string $icon): Provider
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets the Module providing the auth functionality of this provider.
     *
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * Sets the Module providing the auth functionality of this provider.
     *
     * @param string $module the module
     *
     * @return self
     */
    protected function setModule(string $module): Provider
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Gets the value of auth_controller.
     *
     * @return string|null
     */
    public function getAuthController(): ?string
    {
        return $this->auth_controller;
    }

    /**
     * Sets the value of auth_controller.
     *
     * @param string $auth_controller the auth controller
     *
     * @return self
     */
    protected function setAuthController(string $auth_controller): Provider
    {
        $this->auth_controller = $auth_controller;

        return $this;
    }

    /**
     * Gets the value of auth_action.
     *
     * @return string|null
     */
    public function getAuthAction(): ?string
    {
        return $this->auth_action;
    }

    /**
     * Sets the value of auth_action.
     *
     * @param string $auth_action the auth action
     *
     * @return self
     */
    protected function setAuthAction(string $auth_action): Provider
    {
        $this->auth_action = $auth_action;

        return $this;
    }

    /**
     * Gets the value of unlink_controller.
     *
     * @return string|null
     */
    public function getUnlinkController(): ?string
    {
        return $this->unlink_controller;
    }

    /**
     * Sets the value of unlink_controller.
     *
     * @param string $unlink_controller the unlink controller
     *
     * @return self
     */
    protected function setUnlinkController(string $unlink_controller): Provider
    {
        $this->unlink_controller = $unlink_controller;

        return $this;
    }

    /**
     * Gets the value of unlink_action.
     *
     * @return string|null
     */
    public function getUnlinkAction(): ?string
    {
        return $this->unlink_action;
    }

    /**
     * Sets the value of unlink_action.
     *
     * @param string $unlink_action the unlink action
     *
     * @return self
     */
    protected function setUnlinkAction(string $unlink_action): Provider
    {
        $this->unlink_action = $unlink_action;

        return $this;
    }

    /**
     * Get the localised module name.
     *
     * @return string|null
     */
    public function getModuleName(): ?string
    {
        return $this->module_name;
    }

    /**
     * Set the localised module name.
     *
     * @param string $module_name
     * @return Provider
     */
    protected function setModuleName(string $module_name): Provider
    {
        $this->module_name = $module_name;
        return $this;
    }
}

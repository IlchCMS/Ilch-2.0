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
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Human readable name
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * Font-awesome icon
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * Module providing the auth functionality of this provider
     * @var string|null
     */
    protected ?string $module = null;

    /**
     * Controller that performs the authentication
     * @var string|null
     */
    protected ?string $auth_controller = null;

    /**
     * Action that performs the authentication
     * @var string|null
     */
    protected ?string $auth_action = null;

    /**
     * Controller that performs the unlink
     * @var string|null
     */
    protected ?string $unlink_controller = null;

    /**
     * Action that performs the unlink
     * @var string|null
     */
    protected ?string $unlink_action = null;

    /**
     * The localised module name.
     * @var string|null
     */
    protected ?string $module_name = null;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        //
    }

    /**
     * Gets the Unique key.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the Unique key.
     *
     * @param string|null $key the key
     *
     * @return Provider
     */
    public function setKey(?string $key): Provider
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the Human readable name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the Human readable name.
     *
     * @param string|null $name the name
     *
     * @return Provider
     */
    public function setName(?string $name): Provider
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Font-awesome icon.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Sets the Font-awesome icon.
     *
     * @param string|null $icon the icon
     *
     * @return Provider
     */
    public function setIcon(?string $icon): Provider
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
     * @param string|null $module the module
     *
     * @return Provider
     */
    public function setModule(?string $module): Provider
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
     * @param string|null $auth_controller the auth controller
     *
     * @return Provider
     */
    public function setAuthController(?string $auth_controller): Provider
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
     * @param string|null $auth_action the auth action
     *
     * @return Provider
     */
    public function setAuthAction(?string $auth_action): Provider
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
     * @param string|null $unlink_controller the unlink controller
     *
     * @return Provider
     */
    public function setUnlinkController(?string $unlink_controller): Provider
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
     * @param string|null $unlink_action the unlink action
     *
     * @return Provider
     */
    public function setUnlinkAction(?string $unlink_action): Provider
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
     * @param string|null $module_name
     * @return Provider
     */
    public function setModuleName(?string $module_name): Provider
    {
        $this->module_name = $module_name;

        return $this;
    }
}

<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class AuthProviderModule extends \Ilch\Model
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $auth_controller;

    /**
     * @var string
     */
    protected $auth_action;

    /**
     * @var string
     */
    protected $unlink_controller;

    /**
     * @var string
     */
    protected $unlink_action;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AuthProviderModule
     */
    public function setName(string $name): AuthProviderModule
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the value of provider.
     *
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Sets the value of provider.
     *
     * @param string $provider the provider
     *
     * @return self
     */
    public function setProvider(string $provider): AuthProviderModule
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Gets the value of module.
     *
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * Sets the value of module.
     *
     * @param mixed $module the module
     *
     * @return self
     */
    public function setModule($module): AuthProviderModule
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Gets the value of auth_controller.
     *
     * @return string
     */
    public function getAuthController(): string
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
    public function setAuthController(string $auth_controller): AuthProviderModule
    {
        $this->auth_controller = $auth_controller;

        return $this;
    }

    /**
     * Gets the value of auth_action.
     *
     * @return string
     */
    public function getAuthAction(): string
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
    public function setAuthAction(string $auth_action): AuthProviderModule
    {
        $this->auth_action = $auth_action;

        return $this;
    }

    /**
     * Gets the value of unlink_controller.
     *
     * @return string
     */
    public function getUnlinkController(): string
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
    public function setUnlinkController(string $unlink_controller): AuthProviderModule
    {
        $this->unlink_controller = $unlink_controller;

        return $this;
    }

    /**
     * Gets the value of unlink_action.
     *
     * @return string
     */
    public function getUnlinkAction(): string
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
    public function setUnlinkAction(string $unlink_action): AuthProviderModule
    {
        $this->unlink_action = $unlink_action;

        return $this;
    }
}

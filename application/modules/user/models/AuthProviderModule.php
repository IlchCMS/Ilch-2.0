<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class AuthProviderModule extends \Ilch\Model
{
    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var string|null
     */
    protected ?string $provider = null;

    /**
     * @var string|null
     */
    protected ?string $module = null;

    /**
     * @var string|null
     */
    protected ?string $auth_controller = null;

    /**
     * @var string|null
     */
    protected ?string $auth_action = null;

    /**
     * @var string|null
     */
    protected ?string $unlink_controller = null;

    /**
     * @var string|null
     */
    protected ?string $unlink_action = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return AuthProviderModule
     */
    public function setName(?string $name): AuthProviderModule
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the value of provider.
     *
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * Sets the value of provider.
     *
     * @param string|null $provider the provider
     *
     * @return AuthProviderModule
     */
    public function setProvider(?string $provider): AuthProviderModule
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Gets the value of module.
     *
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * Sets the value of module.
     *
     * @param string|null $module the module
     *
     * @return AuthProviderModule
     */
    public function setModule(?string $module): AuthProviderModule
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
     * @return AuthProviderModule
     */
    public function setAuthController(?string $auth_controller): AuthProviderModule
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
     * @return AuthProviderModule
     */
    public function setAuthAction(?string $auth_action): AuthProviderModule
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
     * @return AuthProviderModule
     */
    public function setUnlinkController(?string $unlink_controller): AuthProviderModule
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
     * @return AuthProviderModule
     */
    public function setUnlinkAction(?string $unlink_action): AuthProviderModule
    {
        $this->unlink_action = $unlink_action;

        return $this;
    }
}

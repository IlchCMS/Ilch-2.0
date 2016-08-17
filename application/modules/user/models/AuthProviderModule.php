<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class AuthProviderModule extends \Ilch\Model
{
    protected $provider;
    protected $module;
    protected $auth_controller;
    protected $auth_action;
    protected $unlink_controller;
    protected $unlink_action;

    public function __construct()
    {

    }

    /**
     * Gets the value of provider.
     *
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Sets the value of provider.
     *
     * @param mixed $provider the provider
     *
     * @return self
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Gets the value of module.
     *
     * @return mixed
     */
    public function getModule()
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
    public function setModule($module)
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
    public function setAuthController($auth_controller)
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
    public function setAuthAction($auth_action)
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
    public function setUnlinkController($unlink_controller)
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
    public function setUnlinkAction($unlink_action)
    {
        $this->unlink_action = $unlink_action;

        return $this;
    }
}

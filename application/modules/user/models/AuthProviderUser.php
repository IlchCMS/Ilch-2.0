<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class AuthProviderUser extends \Ilch\Model
{
    /**
     * User id
     */
    protected $user_id;

    /**
     * Provider name, e.g. twitter, facebook
     */
    protected $provider;

    /**
     * Identifier of the user at $provider
     */
    protected $identifier;

    /**
     * Account name
     */
    protected $screen_name;

    /**
     * OAuth token
     */
    protected $oauth_token;

    /**
     * OAuth token secret
     */
    protected $oauth_token_secret;

    /**
     * Created at
     */
    protected $created_at;

    public function __construct()
    {
    }

    /**
     * Gets the value of user_id.
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Sets the value of user_id.
     *
     * @param mixed $user_id the user id
     *
     * @return self
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
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
     * Gets the value of identifier.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the value of identifier.
     *
     * @param mixed $identifier the identifier
     *
     * @return self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Gets the value of oauth_token.
     *
     * @return mixed
     */
    public function getOauthToken()
    {
        return $this->oauth_token;
    }

    /**
     * Sets the value of oauth_token.
     *
     * @param mixed $oauth_token the oauth token
     *
     * @return self
     */
    public function setOauthToken($oauth_token)
    {
        $this->oauth_token = $oauth_token;

        return $this;
    }

    /**
     * Gets the value of oauth_token_secret.
     *
     * @return mixed
     */
    public function getOauthTokenSecret()
    {
        return $this->oauth_token_secret;
    }

    /**
     * Sets the value of oauth_token_secret.
     *
     * @param mixed $oauth_token_secret the oauth token secret
     *
     * @return self
     */
    public function setOauthTokenSecret($oauth_token_secret)
    {
        $this->oauth_token_secret = $oauth_token_secret;

        return $this;
    }

    /**
     * Gets the Account name.
     *
     * @return mixed
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }

    /**
     * Sets the Account name.
     *
     * @param mixed $screen_name the screen name
     *
     * @return self
     */
    public function setScreenName($screen_name)
    {
        $this->screen_name = $screen_name;

        return $this;
    }

    /**
     * Gets the Created at.
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Sets the Created at.
     *
     * @param mixed $created_at the created at
     *
     * @return self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }
}

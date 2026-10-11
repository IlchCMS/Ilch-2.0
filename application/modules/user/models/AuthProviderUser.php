<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class AuthProviderUser extends \Ilch\Model
{
    /**
     * User id
     *
     * @var int|null
     */
    protected ?int $user_id = null;

    /**
     * Provider name, e.g. twitter, facebook
     *
     * @var string|null
     */
    protected ?string $provider = null;

    /**
     * Identifier of the user at $provider
     *
     * @var string|null
     */
    protected ?string $identifier = null;

    /**
     * Account name
     *
     * @var string|null
     */
    protected ?string $screen_name = null;

    /**
     * OAuth token
     *
     * @var string|null
     */
    protected ?string $oauth_token = null;

    /**
     * OAuth token secret
     *
     * @var string|null
     */
    protected ?string $oauth_token_secret = null;

    /**
     * Created at
     *
     * @var string|null
     */
    protected ?string $created_at = null;

    /**
     * Gets the value of user_id.
     *
     * @return int|null
     */
    public function getUserId(): ?int
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
    public function setUserId(mixed $user_id): static
    {
        $this->user_id = $user_id;

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
     * @param mixed $provider the provider
     *
     * @return self
     */
    public function setProvider(mixed $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Gets the value of identifier.
     *
     * @return string|null
     */
    public function getIdentifier(): ?string
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
    public function setIdentifier(mixed $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Gets the value of oauth_token.
     *
     * @return string|null
     */
    public function getOauthToken(): ?string
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
    public function setOauthToken(mixed $oauth_token): static
    {
        $this->oauth_token = $oauth_token;

        return $this;
    }

    /**
     * Gets the value of oauth_token_secret.
     *
     * @return string|null
     */
    public function getOauthTokenSecret(): ?string
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
    public function setOauthTokenSecret(mixed $oauth_token_secret): static
    {
        $this->oauth_token_secret = $oauth_token_secret;

        return $this;
    }

    /**
     * Gets the Account name.
     *
     * @return string|null
     */
    public function getScreenName(): ?string
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
    public function setScreenName(mixed $screen_name): static
    {
        $this->screen_name = $screen_name;

        return $this;
    }

    /**
     * Gets the Created at.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
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
    public function setCreatedAt(mixed $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}

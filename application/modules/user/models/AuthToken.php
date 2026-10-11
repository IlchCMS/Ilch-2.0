<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class AuthToken extends \Ilch\Model
{
    protected ?string $selector = null;

    protected ?string $token = null;

    protected ?int $userid = null;

    protected ?string $expires = null;

    /**
     * Returns the selector of the user.
     *
     * @return string|null
     */
    public function getSelector(): ?string
    {
        return $this->selector;
    }

    /**
     * Saves the selector of the user.
     * Separated the selector from the authenticator because DB lookups are not constant-time.
     * This eliminates the potential impact of timing leaks on searches without causing a drastic performance hit.
     *
     * @param string $selector
     * @return AuthToken
     */
    public function setSelector(string $selector): static
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Returns the token of the user.
     * SHA256 hash of the authenticator.
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Saves the token of the user.
     * Store an SHA256 hash of the authenticator in the database.
     * This mitigates the risk of user impersonation following information leaks.
     *
     * @param string $token
     * @return AuthToken
     */
    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns the id of the user.
     *
     * @return int|null
     */
    public function getUserid(): ?int
    {
        return $this->userid;
    }

    /**
     * Saves the id of the user.
     *
     * @param int $userid
     * @return AuthToken
     */
    public function setUserid(int $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Returns the date at which point of time the token expires.
     *
     * @return string|null
     */
    public function getExpires(): ?string
    {
        return $this->expires;
    }

    /**
     * Sets the date at which point of time the token expires.
     *
     * @param string $expires
     * @return AuthToken
     */
    public function setExpires(string $expires): static
    {
        $this->expires = $expires;

        return $this;
    }
}

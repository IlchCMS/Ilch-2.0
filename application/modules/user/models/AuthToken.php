<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class AuthToken extends \Ilch\Model
{
    protected $selector;

    protected $token;

    protected $userid;

    protected $expires;

    /**
     * Returns the selector of the user.
     *
     * @return string
     */
    public function getSelector(): string
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
    public function setSelector($selector): AuthToken
    {
        $this->selector = $selector;

        return $this;
    }
    
        /**
     * Returns the token of the user.
     * SHA256 hash of the authenticator.
     *
     * @return string
     */
    public function getToken(): string
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
    public function setToken($token): AuthToken
    {
        $this->token = $token;

        return $this;
    }
    
    /**
     * Returns the id of the user.
     *
     * @return int
     */
    public function getUserid(): int
    {
        return $this->userid;
    }

    /**
     * Saves the id of the user.
     *
     * @param int $id
     * @return AuthToken
     */
    public function setUserid($userid): AuthToken
    {
        $this->userid = (int)$userid;

        return $this;
    }
    
    /**
     * Returns the date at which point of time the token expires.
     *
     * @return string
     */
    public function getExpires(): string
    {
        return $this->expires;
    }

    /**
     * Sets the date at which point of time the token expires.
     *
     * @param string $expires
     * @return AuthToken
     */
    public function setExpires($expires): AuthToken
    {
        $this->expires = $expires;

        return $this;
    }
}

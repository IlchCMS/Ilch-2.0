<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Service\Login;

use Modules\User\Models\User as UserModel;

/**
 * Representation of a login result
 */
class Result
{
    /** @var string */
    const LOGIN_FAILED = 'loginFailed';

    /** @var string */
    const USER_NOT_ACTIVATED = 'userNotActivated';

    /** @var bool */
    private $success;

    /** @var null|UserModel */
    private $user;

    /** @var null|string */
    private $error;

    /**
     * Result constructor.
     * @param bool $success
     * @param UserModel $user
     * @param string|null $error
     */
    public function __construct($success, UserModel $user = null, $error = null)
    {
        $this->success = $success;
        $this->user = $user;
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * @return UserModel|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getError()
    {
        return $this->error;
    }
}

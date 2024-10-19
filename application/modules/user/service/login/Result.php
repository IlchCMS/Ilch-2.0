<?php

/**
 * @copyright Ilch 2
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
    public const LOGIN_FAILED = 'loginFailed';

    /** @var string */
    public const COOKIESNOTALLOWED = 'cookiesNotAllowed';

    /** @var string */
    public const USER_NOT_ACTIVATED = 'userNotActivated';

    /** @var string */
    public const USER_LOCKED = 'userLocked';

    /** @var string */
    public const USER_SELECTSDELETE = 'userSelectsDelete';

    /** @var bool */
    private bool $success;

    /** @var null|UserModel */
    private ?UserModel $user;

    /** @var null|string */
    private ?string $error;

    /**
     * Result constructor.
     * @param bool $success
     * @param UserModel|null $user
     * @param string|null $error
     */
    public function __construct(bool $success, ?UserModel $user = null, ?string $error = null)
    {
        $this->success = $success;
        $this->user = $user;
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->success;
    }

    /**
     * @return UserModel|null
     */
    public function getUser(): ?UserModel
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Customer extends Model
{
    /**
     * The id of the customer.
     *
     * @var int|null
     */
    protected $id;

    /**
     * The user id of the customer.
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * The email of the customer.
     *
     * @var string
     */
    protected $email = '';

    /**
     * Gets the id of the customer.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the customer.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Customer
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the user id of the customer.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the customer.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): Customer
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets the email of the customer.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email of the customer.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): Customer
    {
        $this->email = $email;

        return $this;
    }
}

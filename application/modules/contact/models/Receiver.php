<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Contact\Models;

class Receiver extends \Ilch\Model
{
    /**
     * The id of the receiver.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The name of the receiver.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The email of the receiver.
     *
     * @var string|null
     */
    protected ?string $email = null;

    /**
     * Gets the id of the receiver.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the receiver.
     *
     * @param int $id
     * @return Receiver
     */
    public function setId(int $id): Receiver
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the name of the receiver.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the receiver.
     *
     * @param string $name
     * @return Receiver
     */
    public function setName(string $name): Receiver
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the email of the receiver.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email of the receiver.
     *
     * @param string $email
     * @return Receiver
     */
    public function setEmail(string $email): Receiver
    {
        $this->email = $email;

        return $this;
    }
}

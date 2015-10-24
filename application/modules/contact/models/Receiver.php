<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Contact\Models;

/**
 * The receiver model class.
 *
 * @package ilch
 */
class Receiver extends \Ilch\Model
{
    /**
     * The id of the receiver.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the receiver.
     *
     * @var string
     */
    protected $name;

    /**
     * The email of the receiver.
     *
     * @var string
     */
    protected $email;

    /**
     * Gets the id of the receiver.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the receiver.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the name of the receiver.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the receiver.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the email of the receiver.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email of the receiver.
     *
     * @param string $email
     * @return this
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;

        return $this;
    }
}

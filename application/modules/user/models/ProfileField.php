<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class ProfileField extends \Ilch\Model
{
    /**
     * The id of the profile-field.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the profile-field.
     *
     * @var string
     */
    protected $name;

    /**
     * The type of the profile-field.
     *
     * @var int
     */
    protected $type;

    /**
     * Returns the id of the profile-field.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return ProfileField
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Returns the name of the profile-field.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return ProfileField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the type of the profile-field.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     * @return ProfileField
     */
    public function setType($type)
    {
        $this->type = (int)$type;

        return $this;
    }
}

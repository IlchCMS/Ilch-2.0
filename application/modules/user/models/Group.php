<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Group extends \Ilch\Mapper
{
    /**
     * The id of the user group.
     *
     * @var int
     */
    private $id;

    /**
     * The name of the user group.
     *
     * @var string
     */
    private $name = '';

    /**
     * Returns the user group id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the user group id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Returns the user group name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the user group name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }
}

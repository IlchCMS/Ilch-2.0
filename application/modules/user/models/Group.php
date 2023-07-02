<?php

/**
 * @copyright Ilch 2
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
    private $id = 0;

    /**
     * The name of the user group.
     *
     * @var string
     */
    private $name = '';

    /**
     * Set this Model by Array
     *
     * @param array $entries
     * @return $this
     * @since 2.1.50
     */
    public function setByArray(array $entries): Group
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }

        return $this;
    }

    /**
     * Returns the user group id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the user group id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Group
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the user group name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the user group name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Group
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Array of this Model
     *
     * @param bool $withId
     * @return array
     * @since 2.1.50
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'name'   => $this->getName(),
            ]
        );
    }
}

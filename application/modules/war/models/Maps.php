<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 1.15.0
 */

namespace Modules\War\Models;

class Maps extends \Ilch\Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The Name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray($entries): Maps
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
     * Gets the id of the map.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the map.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Maps
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Maps
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true)
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'name' => $this->getName(),
            ]
        );
    }
}

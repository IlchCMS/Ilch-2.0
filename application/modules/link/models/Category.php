<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Models;

class Category extends \Ilch\Mapper
{
    /**
     * The id of the category.
     *
     * @var int
     */
    private $id = 0;
    /**
     * The position of the category.
     *
     * @var int
     */
    protected $position = 0;
    /**
     * The name of the category.
     *
     * @var string
     */
    private $name = '';
    /**
     * The catid of the category.
     *
     * @var int
     */
    private $cat = 0;
    /**
     * The description of the category.
     *
     * @var string
     */
    private $desc = '';
    /**
     * The links count of the category.
     *
     * @var integer
     */
    private $linksCount = 0;

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Category
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['parent_id'])) {
            $this->setParentId($entries['parent_id']);
        }
        if (isset($entries['pos'])) {
            $this->setPosition($entries['pos']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['desc'])) {
            $this->setDesc($entries['desc']);
        }
        if (isset($entries['count'])) {
            $this->setLinksCount($entries['count']);
        }

        return $this;
    }

    /**
     * Returns the user category id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the category id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Category
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the position of the category.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): Category
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Returns the category name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the category name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the category id.
     *
     * @return int
     */
    public function getParentId(): int
    {
        return $this->cat;
    }

    /**
     * Sets the category id.
     *
     * @param int $cat
     * @return $this
     */
    public function setParentId(int $cat): Category
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Gets the description of the category.
     *
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Sets the description of the category.
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc(string $desc): Category
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Gets the links count of the category.
     *
     * @return int
     */
    public function getLinksCount(): int
    {
        return $this->linksCount;
    }

    /**
     * Sets the links count of the category.
     *
     * @param int $count
     * @return $this
     */
    public function setLinksCount(int $count): Category
    {
        $this->linksCount = $count;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'name'      => $this->getName(),
                'desc'      => $this->getDesc(),
                'parent_id' => $this->getParentId(),
                'pos'       => $this->getPosition()
            ]
        );
    }
}

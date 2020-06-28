<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Models;

class Category extends \Ilch\Mapper
{
    /**
     * The id of the category.
     *
     * @var int
     */
    private $id;

    /**
     * The name of the category.
     *
     * @var string
     */
    private $name;

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
    public function setId($id): self
    {
        $this->id = (int) $id;

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
    public function setName($name): self
    {
        $this->name = (string) $name;

        return $this;
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Models;

class Category extends \Ilch\Model
{
    /**
     * The id of the category.
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The name of the category.
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Returns the category id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the category id.
     *
     * @param mixed $id
     * @return $this
     */
    public function setId(mixed $id): self
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Returns the category name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the category name.
     *
     * @param mixed $name
     * @return $this
     */
    public function setName(mixed $name): self
    {
        $this->name = (string) $name;

        return $this;
    }
}

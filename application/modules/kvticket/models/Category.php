<?php

/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Models;

class Category extends \Ilch\Model
{
    /**
     * The Id.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The Title.
     *
     * @var string
     */
    protected string $title = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id.
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
     * Gets the Title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the Title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Category
    {
        $this->title = $title;

        return $this;
    }
}

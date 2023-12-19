<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The prefix model.
 *
 * @package ilch
 */
class Prefix extends Model
{
    /**
     * @var int id of the prefix
     */
    protected $id;

    /**
     * @var string the prefix
     */
    protected $prefix;

    /**
     * Get the id of the prefix.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the id of the prefix.
     *
     * @param int|null $id
     * @return Prefix
     */
    public function setId(?int $id): Prefix
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Set the prefix.
     *
     * @param string $prefix
     * @return Prefix
     */
    public function setPrefix(string $prefix): Prefix
    {
        $this->prefix = $prefix;
        return $this;
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Models;

class Currency extends \Ilch\Model
{
    /**
     * The id of the currency.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The name of the currency.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Gets the id of the currency.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the currency.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Currency
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the name of the currency.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the currency.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): Currency
    {
        $this->name = $name;

        return $this;
    }
}

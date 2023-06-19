<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkoutbasic\Models;

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
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Currency
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
     * Gets the id of the currency.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * Sets the id of the currency.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Currency
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the name of the currency.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Currency
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'name'    => $this->getName(),
            ]
        );
    }
}

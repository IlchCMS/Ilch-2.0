<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkoutbasic\Models;

class Entry extends \Ilch\Model
{
    /**
     * The id of the entry.
     *
     * @var integer
     */
    protected $id = 0;

    /**
     * The datetime of the entry.
     *
     * @var string
     */
    protected $datetime = '';

    /**
     * The name of the entry.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The usage of the entry.
     *
     * @var string
     */
    protected $usage = '';

    /**
     * The amount of the entry.
     *
     * @var float
     */
    protected $amount = 0.0;

    /**
     * @param array $entries
     * @return $this
     * @since 1.5.0
     */
    public function setByArray(array $entries): Entry
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['date_created'])) {
            $this->setDatetime($entries['date_created']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['usage'])) {
            $this->setUsage($entries['usage']);
        }
        if (isset($entries['amount'])) {
            $this->setAmount($entries['amount']);
        }

        return $this;
    }

    /**
     * Gets the id of the entry.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the datetime of the entry.
     *
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }

    /**
     * Gets the name of the entry.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the usage of the entry.
     *
     * @return string
     */
    public function getUsage(): string
    {
        return $this->usage;
    }

    /**
     * Gets the amount of the entry.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Sets the id of the entry.
     *
     * @param integer $id
     * @return $this
     */
    public function setId(int $id): Entry
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the datetime of the entry.
     *
     * @param string $datetime
     * @return $this
     */
    public function setDatetime(string $datetime): Entry
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Sets the name of the entry.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Entry
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the usage of the entry.
     *
     * @param string $usage
     * @return $this
     */
    public function setUsage(string $usage): Entry
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * Sets the amount of the entry.
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): Entry
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.5.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'date_created'    => $this->getDatetime(),
                'name'    => $this->getName(),
                'usage'    => $this->getUsage(),
                'amount'    => $this->getAmount(),
            ]
        );
    }
}

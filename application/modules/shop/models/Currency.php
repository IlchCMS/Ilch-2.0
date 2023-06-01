<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Currency extends Model
{
    /**
     * The id of the currency.
     *
     * @var int|null
     */
    protected $id;

    /**
     * The name of the currency.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The currency code according to ISO 4217.
     *
     * @link https://www.six-group.com/en/products-services/financial-information/data-standards.html
     * @var string|null
     */
    protected $code;

    /**
     * Gets the id of the currency.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of the currency.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the id of the currency.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Sets the name of the currency.
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Gets the currency code according to ISO 4217.
     *
     * @link https://www.six-group.com/en/products-services/financial-information/data-standards.html
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Sets the currency code according to ISO 4217.
     *
     * @link https://www.six-group.com/en/products-services/financial-information/data-standards.html
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}

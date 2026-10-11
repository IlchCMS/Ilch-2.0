<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Updateserver extends \Ilch\Model
{
    /**
     * The id of the updateserver.
     */
    protected ?int $id = null;

    /**
     * The url of the updateserver.
     */
    protected ?string $url = null;

    /**
     * The operator of the updateserver.
     */
    protected ?string $operator = null;

    /**
     * The country in which the updateserver is located.
     */
    protected ?string $country = null;

    /**
     * Get the id of the updateserver.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the id of the updateserver.
     *
     * @param int|float|string $id
     */
    public function setId(int|float|string $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the url of the updateserver.
     *
     * @return string|null
     */
    public function getURL(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the url of the updateserver.
     *
     * @param string $url
     */
    public function setURL(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Gets the operator of the updateserver.
     *
     * @return string|null
     */
    public function getOperator(): ?string
    {
        return $this->operator;
    }

    /**
     * Sets the operator of the updateserver.
     *
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * Gets the country in which the updateserver is located.
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Sets the country in which the updateserver is located.
     *
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}

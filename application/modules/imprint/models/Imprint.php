<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Imprint\Models;

class Imprint extends \Ilch\Model
{
    /**
     * The id of the imprint.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The imprint.
     *
     * @var string
     */
    protected $imprint = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Imprint
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['imprint'])) {
            $this->setImprint($entries['imprint']);
        }
        return $this;
    }

    /**
     * Gets the id of the imprint.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the imprint.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Imprint
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the imprint.
     *
     * @return string
     */
    public function getImprint(): string
    {
        return $this->imprint;
    }

    /**
     * Sets the imprint.
     *
     * @param string $imprint
     * @return $this
     */
    public function setImprint(string $imprint): Imprint
    {
        $this->imprint = $imprint;

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
                'imprint'        => $this->getImprint(),
            ]
        );
    }
}

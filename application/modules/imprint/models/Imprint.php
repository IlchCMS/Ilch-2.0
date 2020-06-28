<?php
/**
 * @copyright Ilch 2.0
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
    protected $id;

    /**
     * The imprint.
     *
     * @var string
     */
    protected $imprint;

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
    public function setId($id): self
    {
        $this->id = (int)$id;

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
    public function setImprint($imprint): self
    {
        $this->imprint = (string)$imprint;

        return $this;
    }
}

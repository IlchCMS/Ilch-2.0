<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Models;

use Ilch\Model;

/**
 * The model for a award recipient.
 */
class Recipient extends Model
{
    /**
     * The id of the awards.
     *
     * @var int
     */
    protected $award_id = 0;

    /**
     * The utId of the awards.
     *
     * @var int
     */
    protected $utId = 0;

    /**
     * The type of the awards.
     *
     * @var int
     */
    protected $typ = 0;

    /**
     * Get the award id.
     *
     * @return int
     */
    public function getAwardId(): int
    {
        return $this->award_id;
    }

    /**
     * Set the award id.
     *
     * @param int $award_id
     * @return Recipient
     */
    public function setAwardId(int $award_id): Recipient
    {
        $this->award_id = $award_id;
        return $this;
    }

    /**
     * Get the user or team id.
     *
     * @return int
     */
    public function getUtId(): int
    {
        return $this->utId;
    }

    /**
     * Set the user or team id.
     *
     * @param int $utId
     * @return Recipient
     */
    public function setUtId(int $utId): Recipient
    {
        $this->utId = $utId;
        return $this;
    }

    /**
     * Get the type.
     *
     * @return int
     */
    public function getTyp(): int
    {
        return $this->typ;
    }

    /**
     * Set the type.
     *
     * @param int $typ
     * @return Recipient
     */
    public function setTyp(int $typ): Recipient
    {
        $this->typ = $typ;
        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.12.2
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'date' =>   $this->getDate(),
                'rank' =>   $this->getRank(),
                'image' =>  $this->getImage(),
                'event' =>  $this->getEvent(),
                'url' =>    $this->getURL()
            ]
        );
    }
}

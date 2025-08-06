<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Models;

/**
 * Awards model
 */
class Awards extends \Ilch\Model
{
    /**
     * The id of the awards.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The date of the awards.
     *
     * @var string
     */
    protected $date = '';

    /**
     * The rank of the awards.
     *
     * @var int
     */
    protected $rank = 1;

    /**
     * The image of the awards.
     *
     * @var string
     */
    protected $image = '';

    /**
     * The event of the awards.
     *
     * @var string
     */
    protected $event = '';

    /**
     * The page of the awards.
     *
     * @var string
     */
    protected $url = '';

    /**
     * @var Recipient[]
     */
    protected $recipients = [];

    /**
     * @param array $entries
     * @return $this
     * @since 1.12.2
     */
    public function setByArray(array $entries): Awards
    {
        if (!empty($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (!empty($entries['date'])) {
            $this->setDate($entries['date']);
        }
        if (!empty($entries['rank'])) {
            $this->setRank($entries['rank']);
        }
        if (!empty($entries['image'])) {
            $this->setImage($entries['image']);
        }
        if (!empty($entries['event'])) {
            $this->setEvent($entries['event']);
        }
        if (!empty($entries['url'])) {
            $this->setURL($entries['url']);
        }

        return $this;
    }

    /**
     * Gets the id of the awards.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the awards.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Awards
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the date of the awards.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Sets the date of the awards.
     *
     * @param string $date
     * @return $this
     */
    public function setDate(string $date): Awards
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Gets the rank of the awards.
     *
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * Sets the rank of the awards.
     *
     * @param int $rank
     * @return $this
     */
    public function setRank(int $rank): Awards
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Gets the awards Image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Sets the Image of the awards.
     *
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): Awards
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the event of the awards.
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * Sets the event of the awards.
     *
     * @param string $event
     * @return $this
     */
    public function setEvent(string $event): Awards
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Gets the url of the awards.
     *
     * @return string
     */
    public function getURL(): string
    {
        return $this->url;
    }

    /**
     * Sets the url of the awards.
     *
     * @param string $url
     * @return $this
     */
    public function setURL(string $url): Awards
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the recipients of this award.
     *
     * @return Recipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Sets the recipients of this award.
     *
     * @param Recipient[] $recipients
     * @return $this
     */
    public function setRecipients(array $recipients): Awards
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Sets the recipients of this award.
     *
     * @param Recipient $recipients
     * @return $this
     */
    public function addRecipient(Recipient $recipient): Awards
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.12.2
     */
    public function getArray(): array
    {
        return [
            'award_id' =>   $this->getId(),
            'ut_id' =>   $this->getUtId(),
            'typ' =>  $this->getTyp(),
        ];
    }
}

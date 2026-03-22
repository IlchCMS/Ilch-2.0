<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Models;

class Events extends \Ilch\Model
{
    /**
     * The id of the event.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The user id of the host of the event.
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * The start date of the event.
     *
     * @var string
     */
    protected $start = '';

    /**
     * The end date of the event.
     *
     * @var string
     */
    protected $end = '';

    /**
     * The title of the event.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The place of the event.
     *
     * @var string
     */
    protected $place = '';

    /**
     * The type of the event like concert, karaoke evening, ...
     *
     * @var string
     */
    protected $type = '';

    /**
     * The website of the event.
     *
     * @var string
     */
    protected $website = '';

    /**
     * The lat and long of the event.
     *
     * @var string
     */
    protected $latLong = '';

    /**
     * The image of the event.
     *
     * @var string
     */
    protected $image = '';

    /**
     * The text of the event.
     *
     * @var string
     */
    protected $text = '';

    /**
     * The currency of the event.
     *
     * @var int
     */
    protected $currency = 0;

    /**
     * The price of the event.
     *
     * @var string
     */
    protected $price = '';

    /**
     * The price art of the event.
     *
     * @var int
     */
    protected $priceArt = 0;

    /**
     * The show of the event.
     *
     * @var int
     */
    protected $show = 0;

    /**
     * The user limit of the event.
     *
     * @var int
     */
    protected $userLimit = 0;

    /**
     * The read access of the event.
     *
     * @var string
     */
    protected $readAccess = '';

    /**
     * @param array $entries
     * @return $this
     * @since 1.23.6
     */
    public function setByArray(array $entries): Events
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['start'])) {
            $this->setStart($entries['start']);
        }
        if (isset($entries['end'])) {
            $this->setEnd($entries['end']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['place'])) {
            $this->setPlace($entries['place']);
        }
        if (isset($entries['type'])) {
            $this->setType($entries['type']);
        }
        if (isset($entries['website'])) {
            $this->setWebsite($entries['website']);
        }
        if (isset($entries['lat_long'])) {
            $this->setLatLong($entries['lat_long']);
        }
        if (isset($entries['image'])) {
            $this->setImage($entries['image']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['currency'])) {
            $this->setCurrency($entries['currency']);
        }
        if (isset($entries['price'])) {
            $this->setPrice($entries['price']);
        }
        if (isset($entries['price_art'])) {
            $this->setPriceArt($entries['price_art']);
        }
        if (isset($entries['show'])) {
            $this->setShow($entries['show']);
        }
        if (isset($entries['user_limit'])) {
            $this->setUserLimit($entries['user_limit']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }

        return $this;
    }

    /**
     * Gets the id of the event.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the event.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Events
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the user id of the host of the event.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the host of the event.
     *
     * @param int $userId
     *
     * @return $this
     */
    public function setUserId(int $userId): Events
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the start date of the event.
     *
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * Sets the start date of the event.
     *
     * @param string $start
     *
     * @return $this
     */
    public function setStart(string $start): Events
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Gets the end date of the event.
     *
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * Sets the end date of the event.
     *
     * @param string $end
     *
     * @return $this
     */
    public function setEnd(string $end): Events
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Gets the title of the event.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the event.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): Events
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the place of the event.
     *
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * Sets the place of the event.
     *
     * @param string $place
     *
     * @return $this
     */
    public function setPlace(string $place): Events
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get the type of the event.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the event.
     *
     * @param string $type
     * @return Events
     */
    public function setType(string $type): Events
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets the website of the event.
     *
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * Sets the website of the event.
     *
     * @param string $website
     *
     * @return $this
     */
    public function setWebsite(string $website): Events
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Gets the lat and long from the place.
     *
     * @return string
     */
    public function getLatLong(): string
    {
        return $this->latLong;
    }

    /**
     * Sets the lat and long of the place.
     *
     * @param string $latLong
     *
     * @return $this
     */
    public function setLatLong(string $latLong): Events
    {
        $this->latLong = $latLong;

        return $this;
    }

    /**
     * Gets the image of the event.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Sets the image of the event.
     *
     * @param string $image
     *
     * @return $this
     */
    public function setImage(string $image): Events
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the text of the event.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the event.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text): Events
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the currency of the event.
     *
     * @return int
     */
    public function getCurrency(): int
    {
        return $this->currency;
    }

    /**
     * Sets the currency of the event.
     *
     * @param int $currency
     *
     * @return $this
     */
    public function setCurrency(int $currency): Events
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Gets the price of the event.
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * Sets the price of the event.
     *
     * @param string $price
     *
     * @return $this
     */
    public function setPrice(string $price): Events
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets the price art of the event.
     *
     * @return int
     */
    public function getPriceArt(): int
    {
        return $this->priceArt;
    }

    /**
     * Sets the price art of the event.
     *
     * @param int $priceArt
     *
     * @return $this
     */
    public function setPriceArt(int $priceArt): Events
    {
        $this->priceArt = $priceArt;

        return $this;
    }

    /**
     * Gets the show of the event.
     *
     * @return int
     */
    public function getShow(): int
    {
        return $this->show;
    }

    /**
     * Sets the show of the event.
     *
     * @param int $show
     *
     * @return $this
     */
    public function setShow(int $show): Events
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Gets the user limit of the event.
     *
     * @return int
     */
    public function getUserLimit(): int
    {
        return $this->userLimit;
    }

    /**
     * Sets the user limit of the event.
     *
     * @param int $userLimit
     *
     * @return $this
     */
    public function setUserLimit(int $userLimit): Events
    {
        $this->userLimit = $userLimit;

        return $this;
    }

    /**
     * Gets the read access of the event.
     *
     * @return string
     */
    public function getReadAccess(): string
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access of the event.
     *
     * @param string $readAccess
     *
     * @return $this
     */
    public function setReadAccess(string $readAccess): Events
    {
        $this->readAccess = $readAccess;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.23.6
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'user_id' => $this->getUserId(),
                'start' => $this->getStart(),
                'end' => $this->getEnd(),
                'title' => $this->getTitle(),
                'place' => $this->getPlace(),
                'type' => $this->getType(),
                'website' => $this->getWebsite(),
                'lat_long' => $this->getLatLong(),
                'image' => $this->getImage(),
                'text' => $this->getText(),
                'currency' => $this->getCurrency(),
                'price' => $this->getPrice(),
                'price_art' => $this->getPriceArt(),
                'show' => $this->getShow(),
                'user_limit' => $this->getUserLimit(),
                'read_access' => $this->getReadAccess()
            ]
        );
    }
}

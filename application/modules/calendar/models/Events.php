<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Models;

class Events extends \Ilch\Model
{
    /**
     * The id of the events.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The Url of the events.
     *
     * @var string
     */
    protected $url = '';

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Events
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['url'])) {
            $this->setUrl($entries['url']);
        }

        return $this;
    }


    /**
     * Gets the Id of the events.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the Id of the events.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Events
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the Url of the events.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets the Url of the events.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): Events
    {
        $this->url = $url;

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
                'url' => $this->getUrl(),
            ]
        );
    }
}

<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Media extends \Ilch\Model
{
    /**
     * The id of the media.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The userid of the media.
     *
     * @var int|null
     */
    protected ?int $userid = null;

    /**
     * The url of the media.
     *
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * The urlthumb of the media.
     *
     * @var string|null
     */
    protected ?string $urlthumb = null;

    /**
     * The ending of the media.
     *
     * @var string|null
     */
    protected ?string $ending = null;

    /**
     * The name of the media.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The datetime of the media.
     *
     * @var string|null
     */
    protected ?string $datetime = null;

    /**
     * Gets the id of the media.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the userid of the media.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userid;
    }

    /**
     * Gets url of the media.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Gets urlthumb of the media.
     *
     * @return string|null
     */
    public function getUrlThumb(): ?string
    {
        return $this->urlthumb;
    }

    /**
     * Gets ending of the media.
     *
     * @return string|null
     */
    public function getEnding(): ?string
    {
        return $this->ending;
    }

    /**
     * Gets name of the media.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gets datetime of the media.
     *
     * @return string|null
     */
    public function getDatetime(): ?string
    {
        return $this->datetime;
    }

    /**
     * Sets the id of the media.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the userid of the media.
     *
     * @param int $userid
     * @return $this
     */
    public function setUserId(int $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Sets the url.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Sets the urlthumb.
     *
     * @param string $urlthumb
     * @return $this
     */
    public function setUrlThumb(string $urlthumb): static
    {
        $this->urlthumb = $urlthumb;

        return $this;
    }

    /**
     * Sets the ending.
     *
     * @param string $ending
     * @return $this
     */
    public function setEnding(string $ending): static
    {
        $this->ending = $ending;

        return $this;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the datetime.
     *
     * @param string $datetime
     * @return $this
     */
    public function setDatetime(string $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }
}

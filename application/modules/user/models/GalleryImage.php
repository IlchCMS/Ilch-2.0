<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class GalleryImage extends \Ilch\Model
{
    /**
     * The id of the image.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The userId of the image.
     *
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * The imageId of the image.
     *
     * @var string|null
     */
    protected ?string $imageId = null;

    /**
     * The thumb of the image.
     *
     * @var string|null
     */
    protected ?string $thumb = null;

    /**
     * Title of the image.
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * Description of the image.
     *
     * @var string|null
     */
    protected ?string $desc = null;

    /**
     * The cat of the image.
     *
     * @var string|null
     */
    protected ?string $cat = null;

    /**
     * The visits of the image.
     *
     * @var string|null
     */
    protected ?string $visits = null;

    /**
     * The imageUrl of the image.
     *
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * Gets the id of the image.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the userId of the image.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Gets the imageId of the image.
     *
     * @return string|null
     */
    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    /**
     * Gets the imageThumb of the image.
     *
     * @return string|null
     */
    public function getImageThumb(): ?string
    {
        return $this->thumb;
    }

    /**
     * Gets the image title.
     *
     * @return string|null
     */
    public function getImageTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Gets the image desc.
     *
     * @return string|null
     */
    public function getImageDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * Gets the cat of the image.
     *
     * @return string|null
     */
    public function getCat(): ?string
    {
        return $this->cat;
    }

    /**
     * Gets the visits of the image.
     *
     * @return string|null
     */
    public function getVisits(): ?string
    {
        return $this->visits;
    }

    /**
     * Gets the ImageUrl of the image.
     *
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the id of the image.
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
     * Sets the userId of the image.
     *
     * @param string $userId
     * @return $this
     */
    public function setUserId(string $userId): static
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Sets the imageId of the image.
     *
     * @param string $imageId
     * @return $this
     */
    public function setImageId(string $imageId): static
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * Sets the thumb of the image.
     *
     * @param string $thumb
     * @return $this
     */
    public function setImageThumb(string $thumb): static
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     * @return $this
     */
    public function setImageTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     * @return $this
     */
    public function setImageDesc(string $desc): static
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Sets the cat of the image.
     *
     * @param string $cat
     * @return $this
     */
    public function setCat(string $cat): static
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Sets the visits of the image.
     *
     * @param string $visits
     * @return $this
     */
    public function setVisits(string $visits): static
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Sets the Url of the image.
     *
     * @param string $url
     * @return $this
     */
    public function setImageUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class GalleryImage extends \Ilch\Model
{
    /**
     * The id of the gallery image row (users_gallery_imgs.id).
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The id of the user who owns the gallery image.
     *
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * The id of the referenced media entry (users_media.id).
     * Is null when the media entry no longer exists.
     *
     * @var string|null
     */
    protected ?string $imageId = null;

    /**
     * The url of the referenced media file.
     * Is null when the media entry no longer exists.
     *
     * @var string|null
     */
    protected ?string $imageUrl = null;

    /**
     * The url of the thumbnail of the referenced media file.
     * Is null when the media entry no longer exists.
     *
     * @var string|null
     */
    protected ?string $imageThumb = null;

    /**
     * The title of the gallery image.
     *
     * @var string|null
     */
    protected ?string $imageTitle = null;

    /**
     * The description of the gallery image.
     *
     * @var string|null
     */
    protected ?string $imageDesc = null;

    /**
     * The category (gallery album) the image belongs to.
     *
     * @var string|null
     */
    protected ?string $cat = null;

    /**
     * The number of visits of the gallery image.
     *
     * @var string|null
     */
    protected ?string $visits = null;

    /**
     * Get the id of the gallery image row (users_gallery_imgs.id).
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the id of the gallery image row.
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
     * Get the id of the user who owns the gallery image.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set the id of the user who owns the gallery image.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the id of the referenced media entry (users_media.id).
     * Is null when the media entry no longer exists.
     *
     * @return string|null
     */
    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    /**
     * Set the id of the referenced media entry.
     *
     * @param string|null $imageId
     * @return $this
     */
    public function setImageId(?string $imageId): static
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * Get the url of the referenced media file.
     * Is null when the media entry no longer exists.
     *
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * Set the url of the referenced media file.
     *
     * @param string|null $url
     * @return $this
     */
    public function setImageUrl(?string $url): static
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * Get the url of the thumbnail of the referenced media file.
     * Is null when the media entry no longer exists.
     *
     * @return string|null
     */
    public function getImageThumb(): ?string
    {
        return $this->imageThumb;
    }

    /**
     * Set the url of the thumbnail of the referenced media file.
     *
     * @param string|null $thumb
     * @return $this
     */
    public function setImageThumb(?string $thumb): static
    {
        $this->imageThumb = $thumb;

        return $this;
    }

    /**
     * Get the title of the gallery image.
     *
     * @return string|null
     */
    public function getImageTitle(): ?string
    {
        return $this->imageTitle;
    }

    /**
     * Set the title of the gallery image.
     *
     * @param string $title
     * @return $this
     */
    public function setImageTitle(string $title): static
    {
        $this->imageTitle = $title;

        return $this;
    }

    /**
     * Get the description of the gallery image.
     *
     * @return string|null
     */
    public function getImageDesc(): ?string
    {
        return $this->imageDesc;
    }

    /**
     * Set the description of the gallery image.
     *
     * @param string $desc
     * @return $this
     */
    public function setImageDesc(string $desc): static
    {
        $this->imageDesc = $desc;

        return $this;
    }

    /**
     * Get the category (gallery album) the image belongs to.
     *
     * @return string|null
     */
    public function getCat(): ?string
    {
        return $this->cat;
    }

    /**
     * Set the category (gallery album) the image belongs to.
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
     * Get the number of visits of the gallery image.
     *
     * @return string|null
     */
    public function getVisits(): ?string
    {
        return $this->visits;
    }

    /**
     * Set the number of visits of the gallery image.
     *
     * @param string $visits
     * @return $this
     */
    public function setVisits(string $visits): static
    {
        $this->visits = $visits;

        return $this;
    }
}

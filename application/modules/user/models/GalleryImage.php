<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class GalleryImage extends \Ilch\Model
{
    protected ?int $id = null;
    protected ?int $userId = null;
    protected ?string $imageId = null;
    protected ?string $imageUrl = null;
    protected ?string $imageThumb = null;
    protected string $imageTitle = '';
    protected string $imageDesc = '';
    protected int $cat = 0;
    protected int $visits = 0;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    /**
     * @param string|null $imageId
     * @return $this
     */
    public function setImageId(?string $imageId): static
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageThumb(): ?string
    {
        return $this->imageThumb;
    }

    /**
     * @param string|null $thumb
     * @return $this
     */
    public function setImageThumb(?string $thumb): static
    {
        $this->imageThumb = $thumb;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setImageUrl(?string $url): static
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageTitle(): string
    {
        return $this->imageTitle;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setImageTitle(string $title): static
    {
        $this->imageTitle = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageDesc(): string
    {
        return $this->imageDesc;
    }

    /**
     * @param string $desc
     * @return $this
     */
    public function setImageDesc(string $desc): static
    {
        $this->imageDesc = $desc;

        return $this;
    }

    /**
     * @return int
     */
    public function getCat(): int
    {
        return $this->cat;
    }

    /**
     * @param int $cat
     * @return $this
     */
    public function setCat(int $cat): static
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * @return int
     */
    public function getVisits(): int
    {
        return $this->visits;
    }

    /**
     * @param int $visits
     * @return $this
     */
    public function setVisits(int $visits): static
    {
        $this->visits = $visits;

        return $this;
    }
}

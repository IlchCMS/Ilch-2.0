<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Models;

class Article extends \Ilch\Model
{
    /**
     * The id of the article.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The catId of the article.
     *
     * @var string|null
     */
    protected ?string $catId = null;

    /**
     * The authorId of the article.
     *
     * @var int|null
     */
    protected ?int $authorId = null;

    /**
     * The name of the author.
     *
     * @var string|null
     */
    protected ?string $authorName = null;

    /**
     * The visits of the article.
     *
     * @var int|null
     */
    protected ?int $visits = null;

    /**
     * The perma of the article.
     *
     * @var string|null
     */
    protected ?string $perma = null;

    /**
     * The title of the article.
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * The teaser of the article.
     *
     * @var string|null
     */
    protected ?string $teaser = null;

    /**
     * The content of the article.
     *
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * The description of the article.
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * The keywords of the article.
     *
     * @var string|null
     */
    protected ?string $keywords = null;

    /**
     * The locale of the article.
     *
     * @var string|null
     */
    protected ?string $locale = null;

    /**
     * The datetime when the article got created.
     *
     * @var string|null
     */
    protected ?string $dateCreated = null;

    /**
     * True/False top article.
     *
     * @var bool|null
     */
    protected ?bool $top = null;

    /**
     * True/False comments disabled.
     *
     * @var bool|null
     */
    protected ?bool $commentsDisabled = null;

    /**
     * Read access of the article.
     *
     * @var string|null
     */
    protected ?string $readAccess = null;

    /**
     * The Image of the article.
     *
     * @var string|null
     */
    protected ?string $image = null;

    /**
     * The Image thumb of the article.
     *
     * @var string|null
     */
    protected ?string $imageThumb = null;

    /**
     * The Source of the image.
     *
     * @var string|null
     */
    protected ?string $imageSource = null;

    /**
     * The votes of this article.
     *
     * @var string|null
     */
    protected ?string $votes = null;

    /**
     * Gets the id of the article.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the article.
     *
     * @param mixed $id
     * @return $this
     */
    public function setId(mixed $id): self
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Gets the catId of the article.
     *
     * @return string|null
     */
    public function getCatId(): ?string
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the article.
     *
     * @param mixed $catId
     * @return $this
     */
    public function setCatId(mixed $catId): self
    {
        $this->catId = (string) $catId;

        return $this;
    }

    /**
     * Gets the authorId of the article.
     *
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    /**
     * Sets the authorId of the article.
     *
     * @param mixed $authorId
     * @return $this
     */
    public function setAuthorId(mixed $authorId): self
    {
        $this->authorId = (int) $authorId;

        return $this;
    }

    /**
     * Get the name of the author.
     *
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * Set the name of the author.
     *
     * @param mixed $authorName
     * @return $this
     */
    public function setAuthorName(mixed $authorName): self
    {
        $this->authorName = (string) $authorName;

        return $this;
    }

    /**
     * Gets the visits of the article.
     *
     * @return int|null
     */
    public function getVisits(): ?int
    {
        return $this->visits;
    }

    /**
     * Sets the visits of the article.
     *
     * @param mixed $visits
     * @return $this
     */
    public function setVisits(mixed $visits): self
    {
        $this->visits = (int) $visits;

        return $this;
    }

    /**
     * Gets the perma of the article.
     *
     * @return string|null
     */
    public function getPerma(): ?string
    {
        return $this->perma;
    }

    /**
     * Sets the perma of the article.
     *
     * @param mixed $perma
     * @return $this
     */
    public function setPerma(mixed $perma): self
    {
        $this->perma = (string) $perma;

        return $this;
    }

    /**
     * Gets the article title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the article title.
     *
     * @param mixed $title
     * @return $this
     */
    public function setTitle(mixed $title): self
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Gets the article teaser.
     *
     * @return string|null
     */
    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    /**
     * Sets the article teaser.
     *
     * @param mixed $teaser
     * @return $this
     */
    public function setTeaser(mixed $teaser): self
    {
        $this->teaser = (string) $teaser;

        return $this;
    }

    /**
     * Gets the content of the article.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Sets the content of the article.
     *
     * @param mixed $content
     * @return $this
     */
    public function setContent(mixed $content): self
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * Gets the description of the article.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the article.
     *
     * @param mixed $description
     * @return $this
     */
    public function setDescription(mixed $description): self
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * Gets the keywords of the article.
     *
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords of the article.
     *
     * @param mixed $keywords
     * @return $this
     */
    public function setKeywords(mixed $keywords): self
    {
        $this->keywords = (string) $keywords;

        return $this;
    }

    /**
     * Gets the locale of the article.
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the article.
     *
     * @param mixed $locale
     * @return $this
     */
    public function setLocale(mixed $locale): self
    {
        $this->locale = (string) $locale;

        return $this;
    }

    /**
     * Gets the date_created timestamp of the article.
     *
     * @return string|null
     */
    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the article.
     *
     * @param mixed $dateCreated
     * @return $this
     */
    public function setDateCreated(mixed $dateCreated): self
    {
        $this->dateCreated = (string) $dateCreated;

        return $this;
    }

    /**
     * Gets the value of top.
     *
     * @return bool|null
     */
    public function getTopArticle(): ?bool
    {
        return $this->top;
    }

    /**
     * Sets the value of top.
     *
     * @param mixed $top
     * @return $this
     */
    public function setTopArticle(mixed $top): self
    {
        $this->top = (bool) $top;

        return $this;
    }

    /**
     * Gets the value of commentsDisabled.
     *
     * @return bool|null
     */
    public function getCommentsDisabled(): ?bool
    {
        return $this->commentsDisabled;
    }

    /**
     * Sets the value of commentsDisabled.
     *
     * @param mixed $disabled
     * @return $this
     */
    public function setCommentsDisabled(mixed $disabled): self
    {
        $this->commentsDisabled = (bool) $disabled;

        return $this;
    }

    /**
     * Gets the read access.
     *
     * @return string|null
     */
    public function getReadAccess(): ?string
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access.
     *
     * @param mixed $readAccess
     * @return $this
     */
    public function setReadAccess(mixed $readAccess): self
    {
        $this->readAccess = (string) $readAccess;

        return $this;
    }

    /**
     * Gets the article Image.
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sets the Image of the article.
     *
     * @param mixed $image
     * @return $this
     */
    public function setImage(mixed $image): self
    {
        $this->image = (string) $image;

        return $this;
    }

    /**
     * Gets the article Image thumb.
     *
     * @return string|null
     */
    public function getImageThumb(): ?string
    {
        return $this->imageThumb;
    }

    /**
     * Sets the Image thumb of the article.
     *
     * @param mixed $imageThumb
     * @return $this
     */
    public function setImageThumb(mixed $imageThumb): self
    {
        $this->imageThumb = (string) $imageThumb;

        return $this;
    }

    /**
     * Gets the Image Source.
     *
     * @return string|null
     */
    public function getImageSource(): ?string
    {
        return $this->imageSource;
    }

    /**
     * Sets the source of the image.
     *
     * @param mixed $imageSource
     * @return $this
     */
    public function setImageSource(mixed $imageSource): self
    {
        $this->imageSource = (string) $imageSource;

        return $this;
    }

    /**
     * Gets the votes of this article.
     *
     * @return string|null
     */
    public function getVotes(): ?string
    {
        return $this->votes;
    }

    /**
     * Sets the votes of this article.
     *
     * @param mixed $votes
     * @return $this
     */
    public function setVotes(mixed $votes): self
    {
        $this->votes = (string) $votes;

        return $this;
    }
}

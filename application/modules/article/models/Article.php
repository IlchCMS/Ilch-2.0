<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Models;

class Article extends \Ilch\Model
{
    /**
     * The id of the article.
     *
     * @var int
     */
    protected $id;

    /**
     * The catId of the article.
     *
     * @var int
     */
    protected $catId;

    /**
     * The authorId of the article.
     *
     * @var int
     */
    protected $authorId;

    /**
     * The name of the author.
     *
     * @var string
     */
    protected $authorName;

    /**
     * The visits of the article.
     *
     * @var int
     */
    protected $visits;

    /**
     * The perma of the article.
     *
     * @var string
     */
    protected $perma;

    /**
     * The title of the article.
     *
     * @var string
     */
    protected $title;

    /**
     * The teaser of the article.
     *
     * @var string
     */
    protected $teaser;

    /**
     * The content of the article.
     *
     * @var string
     */
    protected $content;
    
    /**
     * The description of the article.
     *
     * @var string
     */
    protected $description;
    
    /**
     * The keywords of the article.
     *
     * @var string
     */
    protected $keywords;

    /**
     * The locale of the article.
     *
     * @var string
     */
    protected $locale;

    /**
     * The datetime when the article got created.
     *
     * @var string
     */
    protected $dateCreated;

    /**
     * True/False top article.
     *
     * @var boolean
     */
    protected $top;

    /**
     * True/False comments disabled.
     *
     * @var boolean
     */
    protected $commentsDisabled;

    /**
     * Read access of the article.
     *
     * @var string
     */
    protected $readAccess;

    /**
     * The Image of the article.
     *
     * @var string
     */
    protected $image;

    /**
     * The Image thumb of the article.
     *
     * @var string
     */
    protected $imageThumb;

    /**
     * The Source of the image.
     *
     * @var string
     */
    protected $imageSource;

    /**
     * The votes of this article.
     *
     * @var string
     */
    protected $votes;

    /**
     * Gets the id of the article.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the article.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Gets the catId of the article.
     *
     * @return string
     */
    public function getCatId()
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the article.
     *
     * @param string $catId
     * @return $this
     */
    public function setCatId($catId): self
    {
        $this->catId = (string) $catId;

        return $this;
    }

    /**
     * Gets the authorId of the article.
     *
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * Sets the authorId of the article.
     *
     * @param int $authorId
     * @return $this
     */
    public function setAuthorId($authorId): self
    {
        $this->authorId = (int) $authorId;

        return $this;
    }

    /**
     * Get the name of the author.
     *
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * Set the name of the author.
     *
     * @param $authorName
     * @return $this
     */
    public function setAuthorName($authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Gets the visits of the article.
     *
     * @return int
     */
    public function getVisits(): int
    {
        return $this->visits;
    }

    /**
     * Sets the visits of the article.
     *
     * @param int $visits
     * @return $this
     */
    public function setVisits($visits): self
    {
        $this->visits = (int) $visits;

        return $this;
    }

    /**
     * Gets the perma of the article.
     *
     * @return string
     */
    public function getPerma(): string
    {
        return $this->perma;
    }

    /**
     * Sets the perma of the article.
     *
     * @param int $perma
     * @return $this
     */
    public function setPerma($perma): self
    {
        $this->perma = $perma;

        return $this;
    }

    /**
     * Gets the article title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the article title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title): self
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Gets the article teaser.
     *
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Sets the article teaser.
     *
     * @param string $teaser
     * @return $this
     */
    public function setTeaser($teaser): self
    {
        $this->teaser = (string)$teaser;

        return $this;
    }

    /**
     * Gets the content of the article.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets the content of the article.
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content): self
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * Gets the description of the article.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description of the article.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description): self
    {
        $this->description = (string)$description;

        return $this;
    }

    /**
     * Gets the keywords of the article.
     *
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords of the article.
     *
     * @param string $keywords
     * @return $this
     */
    public function setKeywords($keywords): self
    {
        $this->keywords = (string)$keywords;

        return $this;
    }

    /**
     * Gets the locale of the article.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the article.
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale): self
    {
        $this->locale = (string)$locale;

        return $this;
    }

    /**
     * Gets the date_created timestamp of the article.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the article.
     *
     * @param string $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the value of top.
     *
     * @return boolean
     */
    public function getTopArticle(): bool
    {
        return $this->top;
    }

    /**
     * Sets the value of top.
     *
     * @param boolean $top
     * @return $this
     */
    public function setTopArticle($top): self
    {
        $this->top = $top;
        
        return $this;
    }

    /**
     * Gets the value of commentsDisabled.
     *
     * @return boolean
     */
    public function getCommentsDisabled(): bool
    {
        return $this->commentsDisabled;
    }

    /**
     * Sets the value of commentsDisabled.
     *
     * @param boolean $disabled
     * @return $this
     */
    public function setCommentsDisabled($disabled): self
    {
        $this->commentsDisabled = $disabled;

        return $this;
    }

    /**
     * Gets the read access.
     *
     * @return string
     */
    public function getReadAccess(): string
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access.
     *
     * @param integer $readAccess
     * @return $this
     */
    public function setReadAccess($readAccess): self
    {
        $this->readAccess = (string) $readAccess;

        return $this;
    }

    /**
     * Gets the article Image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Sets the Image of the article.
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the article Image thumb.
     *
     * @return string
     */
    public function getImageThumb(): string
    {
        return $this->imageThumb;
    }

    /**
     * Sets the Image thumb of the article.
     *
     * @param string $imageThumb
     * @return $this
     */
    public function setImageThumb($imageThumb): self
    {
        $this->imageThumb = $imageThumb;

        return $this;
    }

    /**
     * Gets the Image Source.
     *
     * @return string
     */
    public function getImageSource(): string
    {
        return $this->imageSource;
    }

    /**
     * Sets the source of the image.
     *
     * @param string $imageSource
     * @return $this
     */
    public function setImageSource($imageSource): self
    {
        $this->imageSource = $imageSource;

        return $this;
    }

    /**
     * Gets the votes of this article.
     *
     * @return string
     */
    public function getVotes(): string
    {
        return $this->votes;
    }

    /**
     * Sets the votes of this article.
     *
     * @param string $votes
     * @return $this
     */
    public function setVotes($votes): self
    {
        $this->votes = $votes;

        return $this;
    }
}

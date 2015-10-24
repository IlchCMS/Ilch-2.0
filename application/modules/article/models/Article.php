<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Models;

/**
 * The article model class.
 */
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
     * The locale of the article.
     *
     * @var string
     */
    protected $locale;

    /**
     * The datetime when the article got created.
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * The articleImage of the article.
     *
     * @var string
     */
    protected $articleImage;

    /**
     * The articleImageThumb of the article.
     *
     * @var string
     */
    protected $articleImageThumb;

    /**
     * The articleImageSource of the image.
     *
     * @var string
     */
    protected $articleImageSource;

    /**
     * Gets the id of the article.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the article.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the catId of the article.
     *
     * @return int
     */
    public function getCatId()
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the article.
     *
     * @param int $catId
     */
    public function setCatId($catId)
    {
        $this->catId = (int) $catId;
    }

    /**
     * Gets the authorId of the article.
     *
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Sets the authorId of the article.
     *
     * @param int $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = (int) $authorId;
    }

    /**
     * Gets the visits of the article.
     *
     * @return int
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Sets the visits of the article.
     *
     * @param int $visits
     */
    public function setVisits($visits)
    {
        $this->visits = (int) $visits;
    }

    /**
     * Gets the perma of the article.
     *
     * @return string
     */
    public function getPerma()
    {
        return $this->perma;
    }

    /**
     * Sets the perma of the article.
     *
     * @param int $perma
     */
    public function setPerma($perma)
    {
        $this->perma = $perma;
    }

    /**
     * Gets the article title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the article title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the content of the article.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of the article.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    /**
     * Gets the description of the page.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description of the page.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    /**
     * Gets the locale of the article.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the article.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;
    }

    /**
     * Gets the date_created timestamp of the article.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the article.
     *
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * Sets the articleImage of the article.
     *
     * @param string $articleImage
     */
    public function setArticleImage($articleImage)
    {
        $this->articleImage = $articleImage;
    }

    /**
     * Gets the article Image.
     *
     * @return string
     */
    public function getArticleImage()
    {
        return $this->articleImage;
    }

    /**
     * Sets the articleImageThumb of the article.
     *
     * @param string $articleImageThumb
     */
    public function setArticleImageThumb($articleImageThumb)
    {
        $this->articleImageThumb = $articleImageThumb;
    }

    /**
     * Gets the article ImageThumb.
     *
     * @return string
     */
    public function getArticleImageThumb()
    {
        return $this->articleImageThumb;
    }

    /**
     * Sets the articleImageSource of the image.
     *
     * @param string $articleImageSource
     */
    public function setArticleImageSource($articleImageSource)
    {
        $this->articleImageSource = $articleImageSource;
    }

    /**
     * Gets the article Image Source.
     *
     * @return string
     */
    public function getArticleImageSource()
    {
        return $this->articleImageSource;
    }
}

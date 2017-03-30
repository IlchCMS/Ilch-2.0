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
     * @var string
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
     * The sub title of the article.
     *
     * @var string
     */
    protected $subTitle;

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
     * @var DateTime
     */
    protected $dateCreated;

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
     * @return $this
     */
    public function setId($id)
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
    public function setCatId($catId)
    {
        $this->catId = (string) $catId;

        return $this;
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
     * @return $this
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = (int) $authorId;

        return $this;
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
     * @return $this
     */
    public function setVisits($visits)
    {
        $this->visits = (int) $visits;

        return $this;
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
     * @return $this
     */
    public function setPerma($perma)
    {
        $this->perma = $perma;

        return $this;
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
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Gets the article sub title.
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * Sets the article sub title.
     *
     * @param string $subTitle
     * @return $this
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = (string) $subTitle;

        return $this;
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
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * Gets the description of the article.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description of the article.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;

        return $this;
    }

    /**
     * Gets the keywords of the article.
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords of the article.
     *
     * @param string $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->keywords = (string)$keywords;

        return $this;
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
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;

        return $this;
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
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the article Image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the Image of the article.
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the article Image thumb.
     *
     * @return string
     */
    public function getImageThumb()
    {
        return $this->imageThumb;
    }

    /**
     * Sets the Image thumb of the article.
     *
     * @param string $imageThumb
     * @return $this
     */
    public function setImageThumb($imageThumb)
    {
        $this->imageThumb = $imageThumb;

        return $this;
    }

    /**
     * Gets the Image Source.
     *
     * @return string
     */
    public function getImageSource()
    {
        return $this->imageSource;
    }

    /**
     * Sets the source of the image.
     *
     * @param string $imageSource
     * @return $this
     */
    public function setImageSource($imageSource)
    {
        $this->imageSource = $imageSource;

        return $this;
    }
}

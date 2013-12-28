<?php
/**
 * Holds Article_ArticleModel.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Article\Models;
defined('ACCESS') or die('no direct access');

/**
 * The article model class.
 *
 * @package ilch
 */
class Article extends \Ilch\Model
{
    /**
     * The id of the article.
     *
     * @var int
     */
    protected $_id;

    /**
     * The perma of the article.
     *
     * @var string
     */
    protected $_perma;

    /**
     * The title of the article.
     *
     * @var string
     */
    protected $_title;

    /**
     * The content of the article.
     *
     * @var string
     */
    protected $_content;

    /**
     * The locale of the article.
     *
     * @var string
     */
    protected $_locale;

    /**
     * The datetime when the article got created.
     *
     * @var DateTime
     */
    protected $_dateCreated;

    /**
     * Gets the id of the article.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the article.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Gets the perma of the article.
     *
     * @return string
     */
    public function getPerma()
    {
        return $this->_perma;
    }

    /**
     * Sets the perma of the article.
     *
     * @param int $perma
     */
    public function setPerma($perma)
    {
        $this->_perma = $perma;
    }

    /**
     * Gets the article title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the article title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = (string) $title;
    }

    /**
     * Gets the content of the article.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the content of the article.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->_content = (string) $content;
    }

    /**
     * Gets the locale of the article.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * Sets the locale of the article.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->_locale = (string) $locale;
    }

    /**
     * Gets the date_created timestamp of the article.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * Sets the date_created date of the article.
     *
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;
    }
}

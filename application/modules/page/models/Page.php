<?php
/**
 * Holds Page_PageModel.
 *
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Page\Models;
defined('ACCESS') or die('no direct access');

/**
 * The page model class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Page extends \Ilch\Model
{
    /**
     * The id of the page.
     *
     * @var int
     */
    protected $_id;

    /**
     * The perma of the page.
     *
     * @var string
     */
    protected $_perma;

    /**
     * The title of the page.
     *
     * @var string
     */
    protected $_title;

    /**
     * The content of the page.
     *
     * @var string
     */
    protected $_content;

    /**
     * The locale of the page.
     *
     * @var string
     */
    protected $_locale;

    /**
     * The datetime when the page got created.
     *
     * @var DateTime
     */
    protected $_dateCreated;

    /**
     * Gets the id of the page.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the page.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Gets the perma of the page.
     *
     * @return string
     */
    public function getPerma()
    {
        return $this->_perma;
    }

    /**
     * Sets the perma of the page.
     *
     * @param int $perma
     */
    public function setPerma($perma)
    {
        $this->_perma = $perma;
    }

    /**
     * Gets the page title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the page title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = (string) $title;
    }

    /**
     * Gets the content of the page.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the content of the page.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->_content = (string) $content;
    }

    /**
     * Gets the locale of the page.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * Sets the locale of the page.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->_locale = (string) $locale;
    }

    /**
     * Gets the date_created timestamp of the page.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * Sets the date_created date of the page.
     *
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;
    }
}

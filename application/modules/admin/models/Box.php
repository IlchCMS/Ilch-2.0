<?php
/**
 * Holds Box\Models\Box.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Models;
defined('ACCESS') or die('no direct access');

/**
 * The box model class.
 *
 * @package ilch
 */
class Box extends \Ilch\Model
{
    /**
     * The id of the box.
     *
     * @var int
     */
    protected $_id;

    /**
     * The title of the box.
     *
     * @var string
     */
    protected $_title;

    /**
     * The content of the box.
     *
     * @var string
     */
    protected $_content;

    /**
     * The locale of the box.
     *
     * @var string
     */
    protected $_locale;

    /**
     * The datetime when the box got created.
     *
     * @var DateTime
     */
    protected $_dateCreated;

    /**
     * Gets the id of the box.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the box.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Gets the box title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the box title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = (string) $title;
    }

    /**
     * Gets the content of the box.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the content of the box.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->_content = (string) $content;
    }

    /**
     * Gets the locale of the box.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * Sets the locale of the box.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->_locale = (string) $locale;
    }

    /**
     * Gets the date_created timestamp of the box.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * Sets the date_created date of the box.
     *
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;
    }
}

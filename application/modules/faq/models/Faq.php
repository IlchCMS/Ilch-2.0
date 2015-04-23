<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Models;

defined('ACCESS') or die('no direct access');

class Faq extends \Ilch\Model
{
    /**
     * The id of the faq.
     *
     * @var int
     */
    protected $id;

    /**
     * The cat_id of the faq.
     *
     * @var int
     */
    protected $catId;

    /**
     * The title of the faq.
     *
     * @var string
     */
    protected $title;

    /**
     * The text of the faq.
     *
     * @var string
     */
    protected $text;

    /**
     * Gets the id of the faq.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the faq.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the catId of the faq.
     *
     * @return int
     */
    public function getCatId()
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the faq.
     *
     * @param int $catId
     * @return this
     */
    public function setCatId($catId)
    {
        $this->catId = (int)$catId;

        return $this;
    }

    /**
     * Gets the title of the faq.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the faq.
     *
     * @param string $title
     * @return this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the text of the faq.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the faq.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}

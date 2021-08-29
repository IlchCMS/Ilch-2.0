<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Rule\Models;

class Rule extends \Ilch\Model
{
    /**
     * The id of the rule.
     *
     * @var int
     */
    protected $id;

    /**
     * The paragraph of the rule.
     *
     * @var string
     */
    protected $paragraph;

    /**
     * The title of the rule.
     *
     * @var string
     */
    protected $title;

    /**
     * The text of the rule.
     *
     * @var string
     */
    protected $text;

    /**
     * The position of the rule.
     *
     * @var int
     */
    protected $position;

    /**
     * The parent_id of the rule.
     *
     * @var int
     */
    protected $parent_id;

    /**
     * The title of the parent rule.
     *
     * @var string
     */
    protected $parent_title;

    /**
     * Read access of the item.
     *
     * @var string
     */
    protected $access;

    /**
     * Gets the id of the rule.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the rule.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the paragraph of the rule.
     *
     * @return string
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Sets the paragraph of the rule.
     *
     * @param string $paragraph
     * @return $this
     */
    public function setParagraph($paragraph)
    {
        $this->paragraph = (string)$paragraph;

        return $this;
    }

    /**
     * Gets the title of the rule.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the rule.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the text of the rule.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the rule.
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * Gets the position of the rule.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the position of the rule.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = (int)$position;

        return $this;
    }

    /**
     * Gets the parent_id of the rule.
     *
     * @return int
     */
    public function getParent_Id()
    {
        return $this->parent_id;
    }

    /**
     * Sets the parent_id of the rule.
     *
     * @param int $parent_id
     * @return $this
     */
    public function setParent_Id($parent_id)
    {
        $this->parent_id = (int)$parent_id;

        return $this;
    }

    /**
     * Gets the title of the parent rule.
     *
     * @return string
     */
    public function getParentTitle()
    {
        return $this->parent_title;
    }

    /**
     * Sets the title of the parent rule.
     *
     * @param string $parent_title
     * @return Rule
     */
    public function setParentTitle($parent_title)
    {
        $this->parent_title = $parent_title;
        return $this;
    }

    /**
     * Gets the access.
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Sets the access.
     *
     * @param string $access
     * @return $this
     */
    public function setAccess($access)
    {
        $this->access = (string) $access;

        return $this;
    }
}

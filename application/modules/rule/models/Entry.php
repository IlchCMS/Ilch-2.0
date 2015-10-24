<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Models;

class Entry extends \Ilch\Model
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
     * @var int
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
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the paragraph of the rule.
     *
     * @return int
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Sets the paragraph of the rule.
     *
     * @param int $paragraph
     * @return this
     */
    public function setParagraph($paragraph)
    {
        $this->paragraph = (int)$paragraph;

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
     * @return this
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
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}

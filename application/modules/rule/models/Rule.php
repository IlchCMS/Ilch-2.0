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
    protected $id = 0;

    /**
     * The paragraph of the rule.
     *
     * @var string
     */
    protected $paragraph = '';

    /**
     * The title of the rule.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The text of the rule.
     *
     * @var string
     */
    protected $text = '';

    /**
     * The position of the rule.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * The parent_id of the rule.
     *
     * @var int
     */
    protected $parent_id = 0;

    /**
     * The title of the parent rule.
     *
     * @var string
     */
    protected $parent_title = '';

    /**
     * Read access of the item.
     *
     * @var string
     */
    protected $access = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Rule
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['paragraph'])) {
            $this->setParagraph($entries['paragraph']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['position'])) {
            $this->setPosition($entries['position']);
        }
        if (isset($entries['parent_id'])) {
            $this->setParentId($entries['parent_id']);
        }
        if (isset($entries['parent_title'])) {
            $this->setParentTitle($entries['parent_title']);
        }
        if (isset($entries['access'])) {
            $this->setAccess($entries['access']);
        }
        if (isset($entries['access_all'])) {
            if ($entries['access_all']) {
                $this->setAccess('all');
            }
        }
        return $this;
    }

    /**
     * Gets the id of the rule.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the rule.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Rule
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the paragraph of the rule.
     *
     * @return string
     */
    public function getParagraph(): string
    {
        return $this->paragraph;
    }

    /**
     * Sets the paragraph of the rule.
     *
     * @param string $paragraph
     * @return $this
     */
    public function setParagraph(string $paragraph): Rule
    {
        $this->paragraph = $paragraph;

        return $this;
    }

    /**
     * Gets the title of the rule.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the rule.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Rule
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the text of the rule.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the rule.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Rule
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the position of the rule.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position of the rule.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): Rule
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the parent_id of the rule.
     *
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * Sets the parent_id of the rule.
     *
     * @param int $parent_id
     * @return $this
     */
    public function setParentId(int $parent_id): Rule
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * Gets the title of the parent rule.
     *
     * @return string
     */
    public function getParentTitle(): string
    {
        return $this->parent_title;
    }

    /**
     * Sets the title of the parent rule.
     *
     * @param string $parent_title
     * @return Rule
     */
    public function setParentTitle(string $parent_title): Rule
    {
        $this->parent_title = $parent_title;
        return $this;
    }

    /**
     * Gets the access.
     *
     * @return string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * Sets the access.
     *
     * @param string $access
     * @return $this
     */
    public function setAccess(string $access): Rule
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'paragraph' => $this->getParagraph(),
                'title'     => $this->getTitle(),
                'text'      => $this->getText(),
                'parent_id' => $this->getParentId(),
                'position'  => $this->getPosition(),
                'access_all'    => ($this->getAccess() === 'all' ? 1 : 0)
            ]
        );
    }
}

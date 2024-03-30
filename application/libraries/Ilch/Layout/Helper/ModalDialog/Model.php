<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\ModalDialog;

/**
 * ModalDialog model to pass information to the GetModalDialog function to customize the dialog.
 *
 * @since Ilch 2.2.0
 * @see https://getbootstrap.com/docs/5.3/components/modal/
 */
class Model
{
    /**
     * CSS id
     *
     * @var string
     */
    protected $id = '';

    /**
     * The class of the outer div
     *
     * @var string
     */
    protected $class = '';

    /**
     * The class of the inner div
     * @var string
     */
    protected $innerClass = '';

    /**
     * Title of the dialog.
     *
     * @var string
     */
    protected $title = '';

    /**
     * Content of the dialog.
     *
     * @var string
     */
    protected $content = '';

    /**
     * @var bool
     */
    protected $submit = false;

    /**
     * Get the id attribute.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the id attribute.
     *
     * @param string $id
     * @return Model
     */
    public function setId(string $id): Model
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the class of the outer div.
     * This would be the div with the modal class.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Set the class of the outer div. Can be used to apply CSS to it.
     * This would be the div with the modal class.
     *
     * @param string $class
     * @return Model
     */
    public function setClass(string $class): Model
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Get the class of the inner div.
     * This would be the div with the modal-dialog class.
     *
     * @return string
     */
    public function getInnerClass(): string
    {
        return $this->innerClass;
    }

    /**
     * Set the class of the inner div.
     * This would be the div with the modal-dialog class.
     *
     * Can be used to add classes for the size (modal-sm, modal-lg, modal-xl),
     * fullscreen modal (modal-fullscreen, modal-fullscreen-sm-down, ...), 
     * scrollable modal (modal-dialog-scrollable), vertically centered and more.
     *
     * @see https://getbootstrap.com/docs/5.3/components/modal/
     * @param string $innerClass
     * @return Model
     */
    public function setInnerClass(string $innerClass): Model
    {
        $this->innerClass = $innerClass;
        return $this;
    }

    /**
     * Get the title of the modal dialog.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the title of the modal dialog.
     *
     * @param string $title
     * @return Model
     */
    public function setTitle(string $title): Model
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the content of the modal dialog.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content of the modal dialog.
     *
     * @param string $content
     * @return Model
     */
    public function setContent(string $content): Model
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Does add an submit button.
     *
     * @return bool
     */
    public function isSubmit(): bool
    {
        return $this->submit;
    }

    /**
     * Set to true to add an submit button to the modal dialog.
     * False for none.
     *
     * @param bool $submit
     * @return Model
     */
    public function setSubmit(bool $submit): Model
    {
        $this->submit = $submit;
        return $this;
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Emails extends \Ilch\Model
{
    /**
     * The module key of the emails.
     */
    protected ?string $moduleKey = null;

    /**
     * The type of the emails.
     */
    protected ?string $type = null;

    /**
     * The desc of the emails.
     */
    protected ?string $desc = null;

    /**
     * The text of the emails.
     */
    protected ?string $text = null;

    /**
     * The locale of the emails.
     */
    protected ?string $locale = null;

    /**
     * Gets the module key of the emails.
     *
     * @return string|null
     */
    public function getModuleKey(): ?string
    {
        return $this->moduleKey;
    }

    /**
     * Sets the module key of the emails.
     *
     * @param string $moduleKey
     * @return $this
     */
    public function setModuleKey(string $moduleKey): Emails
    {
        $this->moduleKey = $moduleKey;

        return $this;
    }

    /**
     * Gets the type of the emails.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the emails.
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): Emails
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the desc of the emails.
     *
     * @return string|null
     */
    public function getDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * Sets the desc of the emails.
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc(string $desc): Emails
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Gets the text of the emails.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Sets the text of the emails.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Emails
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the locale of the emails.
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the emails.
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): Emails
    {
        $this->locale = $locale;

        return $this;
    }
}

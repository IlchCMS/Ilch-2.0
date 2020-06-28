<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Emails extends \Ilch\Model
{
    /**
     * The module key of the emails.
     *
     * @var string
     */
    protected $moduleKey;

    /**
     * The type of the emails.
     *
     * @var string
     */
    protected $type;

    /**
     * The desc of the emails.
     *
     * @var string
     */
    protected $desc;

    /**
     * The text of the emails.
     *
     * @var string
     */
    protected $text;

    /**
     * The locale of the emails.
     *
     * @var string
     */
    protected $locale;

    /**
     * Gets the module key of the emails.
     *
     * @return string
     */
    public function getModuleKey()
    {
        return $this->moduleKey;
    }

    /**
     * Sets the module key of the emails.
     *
     * @param int $moduleKey
     * @return $this
     */
    public function setModuleKey($moduleKey)
    {
        $this->moduleKey = (string)$moduleKey;

        return $this;
    }

    /**
     * Gets the type of the emails.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type of the emails.
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = (string)$type;

        return $this;
    }

    /**
     * Gets the desc of the emails.
     **
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets the desc of the emails.
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;

        return $this;
    }

    /**
     * Gets the text of the emails.
     **
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the emails.
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
     * Gets the locale of the emails.
     **
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the emails.
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;

        return $this;
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Models;

class Cookieconsent extends \Ilch\Mapper
{
    /**
     * The text of the cookie consent.
     *
     * @var string
     */
    private $text;

    /**
     * The locale of the cookie consent.
     *
     * @var string
     */
    private $locale;

    /**
     * Returns the cookie consent text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the cookie consent text.
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
     * Returns the cookie consent locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the cookie consent locale.
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

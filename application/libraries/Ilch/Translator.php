<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

/**
 * Handles translations for ilch.
 *
 * Loads a translations array from a translation file, searches for translations
 * in the translation array and returns translations.
 *
 * @package ilch
 */
class Translator
{
    /**
     * Holds the translations for each loaded translation file.
     *
     * Key as the short locale, value as the translations array from the
     * translations file.
     *
     * @var mixed[]
     */
    private $translations = array();

    /**
     * The locale in which the texts should be translated.
     *
     * @var string
     */
    private $locale = 'de_DE';

    /**
     * Sets the locale to use for the request.
     *
     * @param string $locale
     */
    public function __construct($locale = null)
    {
        /*
         * If a setting is given, set the locale to the given one.
         */
        if ($locale !== null) {
            $this->locale = $locale;
        }
    }

    /**
     * Loads a translation array for the set locale from the given directory.
     *
     * @param  string  $transDirectory The directory where the translation resides.
     * @return boolean True if the translations got loaded, false if not.
     */
    public function load($transDir)
    {
        if (!is_dir($transDir)) {
            return false;
        }

        $localeShort = $this->shortenLocale($this->locale);
        $transFile = $transDir.'/'.$localeShort.'.php';

        if (is_file($transFile)) {
            $this->translations = array_merge($this->translations, require $transFile);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the translated text for a specific key.
     *
     * Works with an argument list like sprintf does
     * to replace placholders in the translated text.
     *
     * @param string  $key
     * @param [, mixed $args [, mixed $... ]]
     * @return string
     */
    public function trans($key)
    {
        if (isset($this->translations[$key])) {
            $translatedText = $this->translations[$key];
        } else {
            /*
             * If no translation exists, return the key as fallback.
             */
            $translatedText = $key;
        }

        $arguments = func_get_args();
        $arguments[0] = $translatedText;
        $translatedText = call_user_func_array('sprintf', $arguments);

        return $translatedText;
    }

    /**
     * Returns the translation array.
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Gets list of all supported locales.
     *
     * @return array
     */
    public function getLocaleList()
    {
        return array
        (
            'en_EN' => 'English',
            'de_DE' => 'German'
        );
    }

    /**
     * Shortens the locale so only the first to characters get returned.
     *
     * @param  string $locale
     * @return string
     */
    public function shortenLocale($locale)
    {
        return substr($locale, 0, 2);
    }

    /**
     * Returns the locale used for the translation.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale used for the translation.
     *
     * @param string
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}

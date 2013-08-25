<?php
/**
 * Holds class Ilch_Translator.
 *
 * @author Jainta Martin
 * @package ilch
 */

/**
 * Handles translations for ilch.
 *
 * Loads a translations array from a translation file, searches for translations
 * in the translation array and returns translations.
 *
 * @author Jainta Martin
 * @package ilch
 */
class Ilch_Translator
{
    /**
     * The directory in which the translation files reside.
     *
     * @var string
     */
    private $_translationsDir;

    /**
     * Holds the translations for each loaded translation file.
     *
     * Key as the short locale, value as the translations array from the
     * translations file.
     *
     * @var mixed[]
     */
    private $_translations = array();

    /**
     * The locale in which the texts should be translated.
     *
     * @var string
     */
    private $_locale = 'de_DE';

    /**
     * The request locale which should be used by default for the translation.
     *
     * @var string
     */
    private $_requestLocale = '';

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
        if($locale !== null)
        {
            $this->_locale = $locale;
        }
    }

    /**
     * Loads a translation array for the set locale from the given directory.
     *
     * @param string $transDirectory The directory where the translation resides.
     * @return boolean True if the translations got loaded, false if not.
     * @throws Exception If the translation directory or translation file was not found.
     */
    public function load($transDir)
    {
        if(!is_dir($transDir))
        {
            throw new Exception('The translation directory doesn´t exist.');
        }

        $localeShort = $this->shortenLocale($this->_locale);
        $transFile = $transDir.DIRECTORY_SEPARATOR.'translations_'.$localeShort.'.php';

        if(is_file($transFile))
        {
            $this->_translations = require $transFile;
            return true;
        }
        else
        {
            throw new Exception('Translation file "'.$transFile.'" for locale "'.$this->_locale.'" not found.');
        }
    }

    /**
     * Returns the translated text for a specific key.
     *
     * Can also replace placeholders with a given string e. g. for names. As
     * default, trans() uses the request locale.
     *
     * @param string $key
     * @param mixed[] $placeholders Key as the placeholder, value as the text which
     * the placeholder gonna be replaced with.
     * @return string
     */
    public function trans($key, $placeholders = array())
    {
        if(isset($this->_translations[$key]))
        {
            $translatedText = $this->_translations[$key];
        }
        else
        {
            /*
             * If no translation exists, return the key as fallback.
             */
            $translatedText = $key;
        }

        foreach($placeholders as $placeholder => $value)
        {
            $translatedText = str_replace($placeholder, $value, $translatedText);
        }

        return $translatedText;
    }

    /**
     * Returns the translation array.
     */
    public function getTranslations()
    {
        return $this->_translations;
    }

    /**
     * Shortens the locale so only the first to characters get returned.
     *
     * @param string $locale
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
        return $this->_locale;
    }
}
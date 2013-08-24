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
 * Loads translations from a Translation object, searches for translations in
 * the runtime and returns translations.
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
     * The locale which the translations take as its base to translate.
     *
     * @var string
     */
    private $_baseLocale = 'de_DE';

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
         * If no setting is given, take the base locale as the one for the
         * current request.
         */
        if($locale === null)
        {
            $this->_requestLocale = $this->_baseLocale;
        }
        else
        {
            $this->_requestLocale = $locale;
        }
    }

    /**
     * Sets the directory in which the translation files reside.
     *
     * @param string $dir
     * @throws InvalidArgumentException If the given directory doesn`t exist.
     */
    public function setTranslationsDir($dir)
    {
        if(is_dir($dir))
        {
            $this->_translationsDir = $dir;
        }
        else
        {
            throw InvalidArgumentException('Directory "'.$dir.'" does not exist.');
        }
    }

    /**
     * Loads translations from a translations file for the given locale.
     *
     * @param string $locale
     * @return boolean True if the translations got loaded, false if not.
     * @throws Exception If the translation file was not found.
     */
    public function load($locale)
    {
        $localeShort = $this->shortenLocale($locale);
        $transFile = $this->_translationsDir.DIRECTORY_SEPARATOR.'translations_'.$localeShort.'.php';

        if(is_file($transFile))
        {
            $this->_translations[$localeShort] = require $transFile;
            return true;
        }
        else
        {
            throw new Exception('Translation file "'.$transFile.'" for locale "'.$locale.'" not found.');
        }
    }

    /**
     * Returns the translation for a specific text.
     *
     * Can also replace placeholders with a given string e. g. for names. As
     * default, trans() uses the request locale. If a locale is given this one
     * will be used.
     *
     * @param string $text
     * @param mixed[] $placeholders Key as the placeholder, value as the text which
     * the placeholder gonna be replaced with.
     * @param string $locale
     * @return string
     */
    public function trans($text, $placeholders = array(), $locale = null)
    {
        if($locale === null)
        {
            $localeShort = $this->shortenLocale($this->_requestLocale);
        }
        else
        {
            $localeShort = $this->shortenLocale($locale);
        }

        if(isset($this->_translations[$localeShort][$text]))
        {
            $translatedText = $this->_translations[$localeShort][$text];
        }
        else
        {
            $translatedText = $text;
        }

        foreach($placeholders as $placeholder => $value)
        {
            $translatedText = str_replace($placeholder, $value, $translatedText);
        }

        return $translatedText;
    }

    /**
     * Returns a specific or all translation arrays.
     *
     * @param string $locale
     */
    public function getTranslations($locale = null, $shortenLocales = true)
    {
        $localeShort = $this->shortenLocale($locale);

        if(isset($locale, $this->_translations[$localeShort]))
        {
            return $this->_translations[$localeShort];
        }
        else
        {
            return $this->_translations;
        }
    }

    /**
     * Returns the request locale.
     *
     * @return string
     */
    public function getRequestLocale()
    {
        return $this->_requestLocale;
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
}
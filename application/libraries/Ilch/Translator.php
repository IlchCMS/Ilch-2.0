<?php
/**
 * @copyright Ilch 2
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
    private $translations = [];

    /**
     * Holds the translations for each loaded translation file of a layout.
     *
     * Key as the short locale, value as the translations array from the
     * translations file.
     *
     * @var array
     */
    private $translationsLayout = [];

    /**
     * Holds the loaded translation directories
     *
     * @var array
     */
    private $translationDirectories = [];

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
        // If a setting is given, set the locale to the given one.
        if ($locale !== null) {
            $this->locale = $locale;
        }
    }

    /**
     * Loads a translation array for the set locale from the given directory.
     *
     * @param  string  $transDir The directory where the translation resides.
     * @return boolean True if the translations got loaded, false if not.
     */
    public function load($transDir)
    {
        if (!is_dir($transDir)) {
            return false;
        }

        $this->translationDirectories[] = $transDir;

        $localeShort = $this->shortenLocale($this->locale);
        $transFile = $transDir.'/'.$localeShort.'.php';

        // Suppress warning "is_file() expects parameter 1 to be a valid path, string given"
        // If it's not a valid path, is_file() will return a falsey value.
        if (@is_file($transFile)) {
            if (strpos($transDir, 'application/layouts') !== false) {
                $this->translationsLayout = array_merge($this->translationsLayout, require $transFile);
            } else {
                $this->translations = array_merge($this->translations, require $transFile);
            }
            return true;
        }

        return false;
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
        $translatedText = $key;

        if (!$this->isCallFromLayout()) {
            if (isset($this->translations[$key])) {
                $translatedText = $this->translations[$key];
            }
        } elseif (isset($this->translationsLayout[$key])) {
            $translatedText = $this->translationsLayout[$key];
        } elseif (isset($this->translations[$key])) {
            // Call from layout, but no translation found. Fallback to other translations.
            $translatedText = $this->translations[$key];
        }

        $arguments = func_get_args();
        $arguments[0] = $translatedText;
        $translatedText = sprintf(...$arguments);

        return $translatedText;
    }

    /**
     * Set translation from code (for unit tests)
     * @param array $translations
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
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
        return [
            'en_EN' => 'English',
            'de_DE' => 'German'
        ];
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
     * @param bool $reloadTranslations
     */
    public function setLocale($locale, $reloadTranslations = false)
    {
        $this->locale = $locale;
        if ($reloadTranslations) {
            $this->translations = [];
            foreach ($this->translationDirectories as $translationDirectory) {
                $this->load($translationDirectory);
            }
        }
    }

    /**
     * Returns an amount of money of the currency supplied formatted in locale-typical style.
     *
     * @param float $amount
     * @param string $currencyCode (ISO 4217)
     * @return string
     */
    public function getFormattedCurrency($amount, $currencyCode)
    {
        $numberFormatter = new \NumberFormatter($this->getLocale(), \NumberFormatter::CURRENCY);
        $returnValue = $numberFormatter->formatCurrency($amount, $currencyCode);

        if (intl_is_failure($numberFormatter->getErrorCode())) {
            // Error occured - probably the currency-code is wrong.
            // Try to just format the number correctly and append $currencyCode.
            $numberFormatter = new \NumberFormatter($this->getLocale(), \NumberFormatter::DECIMAL); 
            $numberFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2); 
            $numberFormatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
            $returnValue = $numberFormatter->format($amount). ' ' .$currencyCode;
        }

        return $returnValue;
    }

    /**
     * Check if called from a layout.
     *
     * @return bool
     */
    private function isCallFromLayout()
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,4);

        foreach ($backtrace as $entry) {
            if ($entry['function'] === 'getTrans' && (strpos($entry['file'], 'application'.DIRECTORY_SEPARATOR.'layouts') !== false)) {
                return true;
            }
        }

        return false;
    }
}

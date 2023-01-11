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
     * @var array
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
     * @param string|null $locale
     */
    public function __construct(string $locale = null)
    {
        // If a setting is given, set the locale to the given one.
        if ($locale !== null) {
            $this->locale = $locale;
        }
    }

    /**
     * Loads a translation array for the set locale from the given directory.
     *
     * @param string $transDir The directory where the translation resides.
     * @return bool True if the translations got loaded, false if not.
     */
    public function load(string $transDir): bool
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
     * @param string $key
     * @param [, mixed $args [, mixed $... ]]
     * @return string
     */
    public function trans(string $key): string
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
        return sprintf(...$arguments);
    }

    /**
     * Returns the translated text for a specific key of a specific layout.
     * Added for usage in the advanced layout settings feature.
     *
     * Works with an argument list like sprintf does
     * to replace placholders in the translated text.
     *
     * @param string $layoutKey
     * @param string $key
     * @param [, mixed $args [, mixed $... ]]
     * @return string
     * @since 2.1.32
     */
    public function transOtherLayout(string $layoutKey, string $key): string
    {
        $translatedText = $this->translationsLayout[$key] ?? $key;

        $arguments = array_slice(func_get_args(), 1);
        $arguments[0] = $translatedText;
        return sprintf(...$arguments);
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
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Gets list of all supported locales.
     *
     * @return array
     */
    public function getLocaleList(): array
    {
        return [
            'en_EN' => 'English',
            'de_DE' => 'German'
        ];
    }

    /**
     * Shortens the locale so only the first to characters get returned.
     *
     * @param string $locale
     * @return string
     */
    public function shortenLocale(string $locale): string
    {
        return substr($locale, 0, 2);
    }

    /**
     * Returns the locale used for the translation.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the locale used for the translation.
     *
     * @param string $locale
     * @param bool $reloadTranslations
     */
    public function setLocale(string $locale, bool $reloadTranslations = false)
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
    public function getFormattedCurrency(float $amount, string $currencyCode): string
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
     * @since 2.1.31
     */
    private function isCallFromLayout(): bool
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);

        foreach ($backtrace as $entry) {
            if ($entry['function'] === 'getTrans' && (strpos($entry['file'], 'application'.DIRECTORY_SEPARATOR.'layouts') !== false)) {
                return true;
            }
        }

        return false;
    }
}

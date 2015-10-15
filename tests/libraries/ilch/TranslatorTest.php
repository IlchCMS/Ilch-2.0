<?php
/**
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the translator object.
 *
 * @package ilch_phpunit
 */
class TranslatorTest extends TestCase
{
    /**
     * Tests if the translator can handle a directory which is filled with
     * translations and can find a translation file.
     */
    public function testLoadTranslationsFile()
    {
        $translator = new Translator('de_DE');
        $this->assertTrue($translator->load(__DIR__ . '/_files'));
    }

    /**
     * Tests if load returns false if a translation file was not found.
     */
    public function testLoadTranslationFileNotExists()
    {
        $translator = new Translator('xx_xx');
        $this->assertFalse(
            $translator->load(__DIR__ . '/_files'),
            'The translator didn\'t return false when the translation file doesn\'t exist.'
        );
    }

    /**
     * Tests if load returns false if a translation dir was not found.
     */
    public function testLoadTranslationDirNotExists()
    {
        $translator = new Translator('de_DE');
        $this->assertFalse(
            $translator->load('someImaginaryFolder'),
            'The translator didn\'t return false when the given translation directory doesn\'t exist.'
        );
    }

    /**
     * Tests if the Translator translates an entry of the translation file
     * correctly.
     */
    public function testTrans()
    {
        $translator = new Translator('en_EN');
        $translator->load(__DIR__ . '/_files');

        $this->assertEquals(
            'The user gets what he wants!',
            $translator->trans('userGetsWhatHeWants'),
            'The text wasnt translated using the translation file.'
        );
    }

    /**
     * Tests if the Translator returns an entry which wasnt translated yet in
     * the translation file.
     */
    public function testTransNotTranslated()
    {
        $translator = new Translator('en_EN');
        $translator->load(__DIR__ . '/_files');

        $this->assertEquals(
            'notTranslatedText',
            $translator->trans('notTranslatedText'),
            'The text wasnt simply returned.'
        );
    }

    /**
     * Tests if the Translator replaces the placeholder withing the translated text
     * with one replacement.
     */
    public function testTransPlaceholder()
    {
        $translator = new Translator('en_EN');
        $translator->load(__DIR__ . '/_files');

        $this->assertEquals(
            'Welcome, Hans',
            $translator->trans('welcomeUser', 'Hans'),
            'The text wasnt returned with the placeholder.'
        );
    }

    /**
     * Tests if the Translator replaces the placeholder withing the translated text
     * with multiple replacements.
     */
    public function testTransMultiplePlaceholder()
    {
        $translator = new Translator('en_EN');
        $translator->load(__DIR__ . '/_files');

        $this->assertEquals(
            'Welcome, Hans, ur last login was yesterday',
            $translator->trans('welcomeUserExtended', 'Hans', 'yesterday'),
            'The text wasnt returned with the placeholder.'
        );
    }

    /**
     * Test if the locale gets set correctly.
     */
    public function testRequestLocaleDefinition()
    {
        $translator = new Translator('en_EN');
        $this->assertEquals('en_EN', $translator->getLocale());
    }

    /**
     * Test if the locale gets set correctly when no one was given in
     * the constructor.
     */
    public function testRequestLocaleDefinitionDefault()
    {
        $translator = new Translator();
        $this->assertEquals('de_DE', $translator->getLocale());
    }

    /**
     * Tests if a loaded translations array gets given back correctly.
     */
    public function testGetTranslationsArray()
    {
        $translator = new Translator('en_EN');
        $translator->load(__DIR__ . '/_files');

        $expectedTranslations = require __DIR__ . '/_files/en.php';
        $this->assertEquals(
            $expectedTranslations,
            $translator->getTranslations(),
            'The translations array was returned wrongly.'
        );
    }

    /**
     * Tests if the locale gets trimmed correctly to a shorter version.
     */
    public function testShortenLocale()
    {
        $translator = new Translator();
        $this->assertEquals('en', $translator->shortenLocale('en_EN'), 'The locale wasn\'t trimmed correctly.');
    }
}

<?php
/**
 * Holds class Libraries_Ilch_TranslatorTest.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Tests the translator object.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Libraries_Ilch_TranslatorTest extends IlchTestCase
{
    /**
     * Tests if the translator can handle a directory which is filled with
     * translations and can find a translation file.
     */
    public function testLoadTranslationsFile()
    {
        $translator = new Ilch_Translator('de_DE');
        $this->assertTrue($translator->load($this->_getFilesFolder()));
    }

    /**
     * Tests if an exception gets thrown when a translation file was not found.
     *
     * @expectedException Exception
     */
    public function testLoadTranslationFileNotExists()
    {
        $translator = new Ilch_Translator('xx_xx');
        $translator->load($this->_getFilesFolder());
    }

    /**
     * Tests if the Translator translates an entry of the translation file
     * correctly.
     */
    public function testTrans()
    {
        $translator = new Ilch_Translator('en_EN');
        $translator->load($this->_getFilesFolder());

        $this->assertEquals
        (
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
        $translator = new Ilch_Translator('en_EN');
        $translator->load($this->_getFilesFolder());

        $this->assertEquals
        (
            'notTranslatedText',
            $translator->trans('notTranslatedText'),
            'The text wasnt simply returned.'
        );
    }

    /**
     * Tests if the Translator returns an entry which wasnt translated yet in
     * the translation file.
     */
    public function testTransPlaceholder()
    {
        $translator = new Ilch_Translator('en_EN');
        $translator->load($this->_getFilesFolder());

        $this->assertEquals
        (
            'Welcome, Hans',
            $translator->trans('welcomeUser', array('%name%' => 'Hans')),
            'The text wasnt returned with the placeholder.'
        );
    }

    /**
     * Test if the locale gets set correctly.
     */
    public function testRequestLocaleDefinition()
    {
        $translator = new Ilch_Translator('en_EN');
        $this->assertEquals('en_EN', $translator->getLocale());
    }

    /**
     * Test if the locale gets set correctly when no one was given in
     * the constructor.
     */
    public function testRequestLocaleDefinitionDefault()
    {
        $translator = new Ilch_Translator();
        $this->assertEquals('de_DE', $translator->getLocale());
    }

    /**
     * Tests if a loaded translations array gets given back correctly.
     */
    public function testGetTranslationsArray()
    {
        $translator = new Ilch_Translator('en_EN');
        $translator->load($this->_getFilesFolder());

        $expectedTranslations = require __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'translations_en.php';
        $this->assertEquals($expectedTranslations, $translator->getTranslations(), 'The translations array was returned wrongly.');
    }

    /**
     * Tests if the locale gets trimmed correctly to a shorter version.
     */
    public function testShortenLocale()
    {
        $translator = new Ilch_Translator();
        $this->assertEquals('en', $translator->shortenLocale('en_EN'), 'The locale wasn´t trimmed correctly.');
    }
}
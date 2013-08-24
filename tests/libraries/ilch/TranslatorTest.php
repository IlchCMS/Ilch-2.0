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
        $translator = new Ilch_Translator();
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $this->assertTrue($translator->load('en_EN'));
    }

    /**
     * Tests if an exception gets thrown when a translation file was not found.
     *
     * @expectedException Exception
     */
    public function testLoadTranslationFileNotExists()
    {
        $translator = new Ilch_Translator();
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('xx_xx');
    }

    /**
     * Tests if the Translator translates an entry of the translation file
     * correctly.
     */
    public function testTrans()
    {
        $translator = new Ilch_Translator('en_EN');
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('en_EN');

        $this->assertEquals
        (
            'The user gets what he wants!',
            $translator->trans('Der Benutzer bekommt was er will!'),
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
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('en_EN');

        $this->assertEquals
        (
            'Der Text ist noch nicht übersetzt',
            $translator->trans('Der Text ist noch nicht übersetzt'),
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
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('en_EN');

        $this->assertEquals
        (
            'Welcome, Hans',
            $translator->trans('Willkommen, %name%', array('%name%' => 'Hans')),
            'The text wasnt returned with the placeholder.'
        );
    }

    /**
     * Tests if the Translator returns an entry with an other language than the
     * request one.
     */
    public function testTransOtherLang()
    {
        $translator = new Ilch_Translator('en_EN');
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('en_EN');
        $translator->load('fr_FR');

        $this->assertEquals
        (
            'L\'utilisateur obtient ce qu\'il veut!',
            $translator->trans('Der Benutzer bekommt was er will!', array(), 'fr_FR'),
            'The text wasnt translated at all or with the wrong language.'
        );
    }

    /**
     * Test if the request locale gets set correctly.
     */
    public function testRequestLocaleDefinition()
    {
        $translator = new Ilch_Translator('en_EN');
        $this->assertEquals('en_EN', $translator->getRequestLocale());
    }

    /**
     * Test if the request locale gets set correctly when no one was given in
     * the constructor.
     */
    public function testRequestLocaleDefinitionDefault()
    {
        $translator = new Ilch_Translator();
        $this->assertEquals('de_DE', $translator->getRequestLocale());
    }

    /**
     * Tests if a loaded translations array gets given back correctly.
     */
    public function testGetTranslationsArray()
    {
        $translator = new Ilch_Translator();
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('en_EN');

        $expectedTranslations = require __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'translations_en.php';
        $this->assertEquals($expectedTranslations, $translator->getTranslations('en_EN'), 'The translations array was returned wrongly.');
    }

    /**
     * Tests if all loaded translations arrays get given back correctly.
     */
    public function testGetTranslationsArrayAllLangs()
    {
        $translator = new Ilch_Translator();
        $translator->setTranslationsDir(__DIR__.DIRECTORY_SEPARATOR.'_files');
        $translator->load('en_EN');
        $translator->load('fr_FR');

        $expectedTranslations = array
        (
            'en' => require __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'translations_en.php',
            'fr' => require __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'translations_fr.php',
        );

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
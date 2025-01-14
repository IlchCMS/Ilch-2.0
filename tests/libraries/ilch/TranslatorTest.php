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
    protected Translator $translator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translator = new Translator('en_EN');
        $this->translator->load(__DIR__ . '/_files');
    }

    protected function tearDown(): void
    {
        unset($this->translator);
        parent::tearDown();
    }

    /**
     * Tests if the translator can handle a directory which is filled with
     * translations and can find a translation file.
     */
    public function testLoadTranslationsFile()
    {
        $translator = new Translator('de_DE');
        self::assertTrue($translator->load(__DIR__ . '/_files'));
    }

    /**
     * Tests if load returns false if a translation file was not found.
     */
    public function testLoadTranslationFileNotExists()
    {
        $translator = new Translator('xx_xx');
        self::assertFalse($translator->load(__DIR__ . '/_files'));
    }

    /**
     * Tests if load returns false if a translation dir was not found.
     */
    public function testLoadTranslationDirNotExists()
    {
        $translator = new Translator('de_DE');
        self::assertFalse($translator->load('someImaginaryFolder'));
    }

    /**
     * Tests if the Translator translates an entry of the translation file
     * correctly.
     */
    public function testTrans()
    {
        self::assertSame(
            'The user gets what he wants!',
            $this->translator->trans('userGetsWhatHeWants')
        );
    }

    /**
     * Tests if the Translator returns an entry which wasn't translated yet in
     * the translation file.
     */
    public function testTransNotTranslated()
    {
        self::assertSame(
            'notTranslatedText',
            $this->translator->trans('notTranslatedText')
        );
    }

    /**
     * Tests if the Translator replaces the placeholders withing the translated texts
     * with one replacement.
     * @dataProvider placeholderDataProvider
     */
    public function testTransPlaceholders(string $key, array $placeholders, string $expected)
    {
        self::assertSame($expected, $this->translator->trans($key, ...$placeholders));
    }

    public function placeholderDataProvider(): array
    {
        return [
            ['welcomeUser', ['Hans'], 'Welcome, Hans'],
            ['welcomeUserExtended', ['Hans', 'yesterday'], 'Welcome, Hans, your last login was yesterday'],
            ['sprintf_2percent', ['Hans'], '<span style="font-size:120%;">Hans</span>'],
            ['sprintf_3percent', ['Admin'], 'Hallo Admin <span style="font-size:120%;">!</span>'],
            ['sprintf_3percent', ['Admin', 'Hans'], 'Hallo Admin <span style="font-size:120%;">!</span>'],
            ['sprintf_percentAlreadyEscaped', ['Hans', 5], 'Welcome Hans, you gained 5 %.'],
            ['welcomeUser', [], 'Welcome, '], // No placeholder
            ['welcomeUser', ['Hans', 'Extra'], 'Welcome, Hans'], // Additional placeholder
            ['welcomeUser', ['<b>Hans</b>'], 'Welcome, <b>Hans</b>'], // Placeholder with HTML.
        ];
    }

    /**
     * Test if the locale gets set correctly.
     */
    public function testRequestLocaleDefinition()
    {
        $translator = new Translator('en_EN');
        self::assertSame('en_EN', $translator->getLocale());
    }

    /**
     * Test if the locale gets set correctly when no one was given in
     * the constructor.
     */
    public function testRequestLocaleDefinitionDefault()
    {
        $translator = new Translator();
        self::assertSame('de_DE', $translator->getLocale());
    }

    /**
     * Tests if a loaded translations array gets given back correctly.
     */
    public function testGetTranslationsArray()
    {
        $expectedTranslations = require __DIR__ . '/_files/en.php';
        self::assertSame($expectedTranslations, $this->translator->getTranslations());
    }

    /**
     * Tests if the locale gets trimmed correctly to a shorter version.
     */
    public function testShortenLocale()
    {
        $translator = new Translator();
        self::assertSame('en', $translator->shortenLocale('en_EN'));
    }

    public function testMultipleLocales()
    {
        $deTranslator = new Translator('de_DE');
        $deTranslator->load(__DIR__ . '/_files');
        $frTranslator = new Translator('fr_FR');
        $frTranslator->load(__DIR__ . '/_files');

        self::assertSame('Der Benutzer bekommt, was er will!', $deTranslator->trans('userGetsWhatHeWants'));
        self::assertSame('The user gets what he wants!', $this->translator->trans('userGetsWhatHeWants'));
        self::assertSame('L\'utilisateur obtient ce qu\'il veut !', $frTranslator->trans('userGetsWhatHeWants'));
    }
}

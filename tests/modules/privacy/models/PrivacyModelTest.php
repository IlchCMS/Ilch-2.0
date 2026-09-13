<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Privacy\Models;

use PHPUnit\Framework\TestCase;
use Modules\Privacy\Models\Privacy as PrivacyModel;

class PrivacyModelTest extends TestCase
{
    /**
     * Tests that default values are correct.
     */
    public function testDefaultValues()
    {
        $model = new PrivacyModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getUrlTitle());
        self::assertSame('', $model->getUrl());
        self::assertSame('', $model->getText());
        self::assertFalse($model->getShow());
        self::assertSame(0, $model->getPosition());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new PrivacyModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid for insert).
     */
    public function testSetIdZero()
    {
        $model = new PrivacyModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new PrivacyModel();
        $model->setTitle('Datenschutz');

        self::assertSame('Datenschutz', $model->getTitle());
    }

    /**
     * Tests that setUrlTitle() sets and returns the urltitle.
     */
    public function testSetUrlTitle()
    {
        $model = new PrivacyModel();
        $model->setUrlTitle('datenschutz');

        self::assertSame('datenschutz', $model->getUrlTitle());
    }

    /**
     * Tests that setURL() sets and returns the url.
     */
    public function testSetUrl()
    {
        $model = new PrivacyModel();
        $model->setURL('https://example.com');

        self::assertSame('https://example.com', $model->getUrl());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new PrivacyModel();
        $model->setText('<p>Privacy text</p>');

        self::assertSame('<p>Privacy text</p>', $model->getText());
    }

    /**
     * Tests that setShow() sets and returns the show flag.
     */
    public function testSetShowTrue()
    {
        $model = new PrivacyModel();
        $model->setShow(true);

        self::assertTrue($model->getShow());
    }

    /**
     * Tests that setShow(false) sets the show flag to false.
     */
    public function testSetShowFalse()
    {
        $model = new PrivacyModel();
        $model->setShow(false);

        self::assertFalse($model->getShow());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new PrivacyModel();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new PrivacyModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setUrlTitle('test'));
        self::assertSame($model, $model->setURL('https://test.example.com'));
        self::assertSame($model, $model->setText('Text'));
        self::assertSame($model, $model->setShow(true));
        self::assertSame($model, $model->setPosition(1));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new PrivacyModel())
            ->setId(3)
            ->setTitle('Soziale Medien')
            ->setUrlTitle('soziale-medien')
            ->setURL('https://www.e-recht24.de')
            ->setText('Social media content')
            ->setShow(true)
            ->setPosition(2);

        self::assertSame(3, $model->getId());
        self::assertSame('Soziale Medien', $model->getTitle());
        self::assertSame('soziale-medien', $model->getUrlTitle());
        self::assertSame('https://www.e-recht24.de', $model->getUrl());
        self::assertSame('Social media content', $model->getText());
        self::assertTrue($model->getShow());
        self::assertSame(2, $model->getPosition());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new PrivacyModel();
        $model->setId(1)->setTitle('Old')->setUrlTitle('old')->setURL('https://old.example.com')->setText('Old text')->setShow(false)->setPosition(0);

        $model->setId(2)->setTitle('New')->setUrlTitle('new')->setURL('https://new.example.com')->setText('New text')->setShow(true)->setPosition(5);

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getTitle());
        self::assertSame('new', $model->getUrlTitle());
        self::assertSame('https://new.example.com', $model->getUrl());
        self::assertSame('New text', $model->getText());
        self::assertTrue($model->getShow());
        self::assertSame(5, $model->getPosition());
    }

    /**
     * Tests that setByArray() populates all fields from an array.
     */
    public function testSetByArray()
    {
        $model = new PrivacyModel();
        $model->setByArray([
            'id'       => 1,
            'title'    => 'Datenschutz',
            'urltitle' => 'datenschutz',
            'url'      => 'https://example.com',
            'text'     => 'Privacy text',
            'show'     => 1,
            'position' => 2,
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Datenschutz', $model->getTitle());
        self::assertSame('datenschutz', $model->getUrlTitle());
        self::assertSame('https://example.com', $model->getUrl());
        self::assertSame('Privacy text', $model->getText());
        self::assertTrue($model->getShow());
        self::assertSame(2, $model->getPosition());
    }

    /**
     * Tests that setByArray() only sets fields present in the array.
     */
    public function testSetByArrayPartial()
    {
        $model = new PrivacyModel();
        $model->setByArray([
            'title' => 'Partial',
        ]);

        self::assertSame(0, $model->getId());
        self::assertSame('Partial', $model->getTitle());
        self::assertSame('', $model->getUrlTitle());
        self::assertSame('', $model->getUrl());
        self::assertSame('', $model->getText());
        self::assertFalse($model->getShow());
        self::assertSame(0, $model->getPosition());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new PrivacyModel();

        self::assertSame($model, $model->setByArray(['id' => 1]));
    }

    /**
     * Tests that getArray() returns all fields including id.
     */
    public function testGetArrayWithId()
    {
        $model = new PrivacyModel();
        $model->setId(1)
            ->setTitle('Test')
            ->setUrlTitle('test')
            ->setURL('https://test.example.com')
            ->setText('Content')
            ->setShow(true)
            ->setPosition(3);

        $array = $model->getArray();

        self::assertSame(1, $array['id']);
        self::assertSame('Test', $array['title']);
        self::assertSame('test', $array['urltitle']);
        self::assertSame('https://test.example.com', $array['url']);
        self::assertSame('Content', $array['text']);
        self::assertTrue($array['show']);
        self::assertSame(3, $array['position']);
    }

    /**
     * Tests that getArray(false) excludes the id field.
     */
    public function testGetArrayWithoutId()
    {
        $model = new PrivacyModel();
        $model->setId(5)
            ->setTitle('Test')
            ->setUrlTitle('test')
            ->setURL('https://test.example.com')
            ->setText('Content')
            ->setShow(false)
            ->setPosition(0);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame('Test', $array['title']);
        self::assertSame('test', $array['urltitle']);
        self::assertSame('https://test.example.com', $array['url']);
        self::assertSame('Content', $array['text']);
        self::assertFalse($array['show']);
        self::assertSame(0, $array['position']);
    }

    /**
     * Tests that getArray() returns correct keys.
     */
    public function testGetArrayKeys()
    {
        $model = new PrivacyModel();
        $array = $model->getArray();

        self::assertEqualsCanonicalizing(
            ['id', 'title', 'urltitle', 'url', 'text', 'show', 'position'],
            array_keys($array)
        );
    }

    /**
     * Tests that getArray(false) returns correct keys (without id).
     */
    public function testGetArrayWithoutIdKeys()
    {
        $model = new PrivacyModel();
        $array = $model->getArray(false);

        self::assertEqualsCanonicalizing(
            ['title', 'urltitle', 'url', 'text', 'show', 'position'],
            array_keys($array)
        );
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\Box as BoxModel;

/**
 * Tests the Box model class.
 *
 * @package ilch_phpunit
 */
class BoxModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new BoxModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new BoxModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new BoxModel();
        $model->setKey('testKey');

        self::assertSame('testKey', $model->getKey());
    }

    /**
     * Tests that setModule() sets and returns the module.
     */
    public function testSetModule()
    {
        $model = new BoxModel();
        $model->setModule('article');

        self::assertSame('article', $model->getModule());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new BoxModel();
        $model->setName('TestBox');

        self::assertSame('TestBox', $model->getName());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new BoxModel();
        $model->setTitle('TestTitle');

        self::assertSame('TestTitle', $model->getTitle());
    }

    /**
     * Tests that setContent() sets and returns the content as string.
     */
    public function testSetContentString()
    {
        $model = new BoxModel();
        $model->setContent('Test content');

        self::assertSame('Test content', $model->getContent());
    }

    /**
     * Tests that setContent() sets and returns the content as array.
     */
    public function testSetContentArray()
    {
        $model = new BoxModel();
        $content = ['de' => 'Deutscher Inhalt', 'en' => 'English content'];
        $model->setContent($content);

        self::assertSame($content, $model->getContent());
    }

    /**
     * Tests that setLocale() sets and returns the locale.
     */
    public function testSetLocale()
    {
        $model = new BoxModel();
        $model->setLocale('de_DE');

        self::assertSame('de_DE', $model->getLocale());
    }

    /**
     * Tests that setDateCreated() sets and returns the date created.
     */
    public function testSetDateCreated()
    {
        $model = new BoxModel();
        $model->setDateCreated('2014-01-01 12:12:12');

        self::assertSame('2014-01-01 12:12:12', $model->getDateCreated());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new BoxModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setKey('testKey'));
        self::assertSame($model, $model->setModule('article'));
        self::assertSame($model, $model->setName('TestBox'));
        self::assertSame($model, $model->setTitle('TestTitle'));
        self::assertSame($model, $model->setContent('Test content'));
        self::assertSame($model, $model->setLocale('de_DE'));
        self::assertSame($model, $model->setDateCreated('2014-01-01 12:12:12'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new BoxModel())
            ->setId(3)
            ->setKey('testKey')
            ->setModule('article')
            ->setName('Support')
            ->setTitle('Support box')
            ->setContent(['de' => 'Inhalt'])
            ->setLocale('de_DE')
            ->setDateCreated('2014-01-01 12:12:12');

        self::assertSame(3, $model->getId());
        self::assertSame('testKey', $model->getKey());
        self::assertSame('article', $model->getModule());
        self::assertSame('Support', $model->getName());
        self::assertSame('Support box', $model->getTitle());
        self::assertSame(['de' => 'Inhalt'], $model->getContent());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('2014-01-01 12:12:12', $model->getDateCreated());
    }

    /**
     * Tests that default values are 0 for id, empty string for the string fields
     * and null for content.
     */
    public function testDefaultValues()
    {
        $model = new BoxModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getKey());
        self::assertSame('', $model->getModule());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getTitle());
        self::assertNull($model->getContent());
        self::assertSame('', $model->getLocale());
        self::assertSame('', $model->getDateCreated());
    }

    /**
     * Tests that addContent() creates the content array on a fresh model.
     */
    public function testAddContentToFreshModel()
    {
        $model = new BoxModel();
        $model->addContent('de', 'Deutscher Inhalt');

        self::assertSame(['de' => 'Deutscher Inhalt'], $model->getContent());
    }

    /**
     * Tests that addContent() adds a further language to existing content.
     */
    public function testAddContentToExistingContent()
    {
        $model = new BoxModel();
        $model->setContent(['de' => 'Deutscher Inhalt']);
        $model->addContent('en', 'English content');

        self::assertSame(['de' => 'Deutscher Inhalt', 'en' => 'English content'], $model->getContent());
    }

    /**
     * Tests that setByArray() fills the model from an array and returns $this.
     */
    public function testSetByArray()
    {
        $model = new BoxModel();
        $entries = [
            'box_id' => 3,
            'key' => 'testKey',
            'module' => 'article',
            'name' => 'TestBox',
            'title' => 'TestTitle',
            'content' => ['de' => 'Inhalt'],
            'locale' => 'de_DE',
            'date_created' => '2014-01-01 12:12:12',
        ];

        self::assertSame($model, $model->setByArray($entries));

        self::assertSame(3, $model->getId());
        self::assertSame('testKey', $model->getKey());
        self::assertSame('article', $model->getModule());
        self::assertSame('TestBox', $model->getName());
        self::assertSame('TestTitle', $model->getTitle());
        self::assertSame(['de' => 'Inhalt'], $model->getContent());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('2014-01-01 12:12:12', $model->getDateCreated());
    }

    /**
     * Tests that setByArray() only sets the given keys and keeps the defaults for the rest.
     */
    public function testSetByArrayPartial()
    {
        $model = new BoxModel();
        $model->setByArray(['name' => 'TestBox', 'content' => 'Plain content']);

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getKey());
        self::assertSame('TestBox', $model->getName());
        self::assertSame('Plain content', $model->getContent());
        self::assertSame('', $model->getLocale());
    }

    /**
     * Tests that setByArray() with an empty array does not change the model.
     */
    public function testSetByArrayEmpty()
    {
        $model = new BoxModel();
        self::assertSame($model, $model->setByArray([]));

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getName());
        self::assertNull($model->getContent());
    }

    /**
     * Tests that getArray() returns all set values.
     */
    public function testGetArray()
    {
        $model = new BoxModel();
        $model->setId(3)
            ->setKey('testKey')
            ->setModule('article')
            ->setName('TestBox')
            ->setTitle('TestTitle')
            ->setContent(['de' => 'Inhalt'])
            ->setLocale('de_DE')
            ->setDateCreated('2014-01-01 12:12:12');

        $array = $model->getArray();

        self::assertSame(3, $array['box_id']);
        self::assertSame('testKey', $array['key']);
        self::assertSame('article', $array['module']);
        self::assertSame('TestBox', $array['name']);
        self::assertSame('TestTitle', $array['title']);
        self::assertSame(['de' => 'Inhalt'], $array['content']);
        self::assertSame('de_DE', $array['locale']);
        self::assertSame('2014-01-01 12:12:12', $array['date_created']);
    }

    /**
     * Tests that getArray() without id does not contain the box_id key.
     */
    public function testGetArrayWithoutId()
    {
        $model = new BoxModel();
        $model->setId(3)->setName('TestBox');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('box_id', $array);
        self::assertSame('TestBox', $array['name']);
    }

    /**
     * Tests that getArray() omits the key/module entries if the module is empty.
     */
    public function testGetArrayOmitsKeyWithoutModule()
    {
        $model = new BoxModel();
        $model->setId(3)->setKey('testKey');

        $array = $model->getArray();

        self::assertArrayNotHasKey('key', $array);
        self::assertArrayNotHasKey('module', $array);
        self::assertSame(3, $array['box_id']);
    }

    /**
     * Tests that getArray() of a fresh model returns an empty array.
     */
    public function testGetArrayEmptyModel()
    {
        $model = new BoxModel();

        self::assertSame([], $model->getArray());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new BoxModel();
        $model->setId(1)->setKey('oldKey')->setName('OldName')->setContent('Old content');

        $model->setId(2)->setKey('newKey')->setName('NewName')->setContent('New content');

        self::assertSame(2, $model->getId());
        self::assertSame('newKey', $model->getKey());
        self::assertSame('NewName', $model->getName());
        self::assertSame('New content', $model->getContent());
    }
}

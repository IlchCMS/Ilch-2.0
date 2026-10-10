<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;

class PageModelTest extends TestCase
{
    /**
     * Tests the default values of a fresh model.
     */
    public function testDefaultValues()
    {
        $model = new Page();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getPerma());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getContent());
        self::assertSame('', $model->getDescription());
        self::assertSame('', $model->getKeywords());
        self::assertSame('', $model->getLocale());
        self::assertSame('', $model->getDateCreated());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Page();
        $model->setId(5);

        self::assertSame(5, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that all setters return $this and are chainable.
     */
    public function testSettersReturnSelf()
    {
        $model = new Page();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setPerma('perma'));
        self::assertSame($model, $model->setTitle('Title'));
        self::assertSame($model, $model->setContent('Content'));
        self::assertSame($model, $model->setDescription('Description'));
        self::assertSame($model, $model->setKeywords('keywords'));
        self::assertSame($model, $model->setLocale('en'));
        self::assertSame($model, $model->setDateCreated('2024-01-01 00:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Page())
            ->setId(3)
            ->setPerma('impressum')
            ->setTitle('Impressum')
            ->setContent('Content')
            ->setLocale('en');

        self::assertSame(3, $model->getId());
        self::assertSame('impressum', $model->getPerma());
        self::assertSame('Impressum', $model->getTitle());
        self::assertSame('Content', $model->getContent());
        self::assertSame('en', $model->getLocale());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues()
    {
        $model = new Page();
        $model->setId(1);
        $model->setTitle('Old');
        $model->setContent('Old content');

        $model->setId(2);
        $model->setTitle('New');
        $model->setContent('New content');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getTitle());
        self::assertSame('New content', $model->getContent());
    }

    /**
     * Tests that setByArray() populates the model from an entry array.
     */
    public function testSetByArray()
    {
        $model = new Page();
        $model->setByArray([
            'page_id' => 12,
            'description' => 'Page description',
            'keywords' => 'page, test',
            'title' => 'Test Page',
            'content' => 'Page content',
            'perma' => 'test-page',
            'locale' => 'en',
            'date_created' => '2024-01-01 12:00:00',
        ]);

        self::assertSame(12, $model->getId());
        self::assertSame('Page description', $model->getDescription());
        self::assertSame('page, test', $model->getKeywords());
        self::assertSame('Test Page', $model->getTitle());
        self::assertSame('Page content', $model->getContent());
        self::assertSame('test-page', $model->getPerma());
        self::assertSame('en', $model->getLocale());
        self::assertSame('2024-01-01 12:00:00', $model->getDateCreated());
    }

    /**
     * Tests that setByArray() returns $this.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new Page();

        self::assertSame($model, $model->setByArray(['title' => 'Test Page']));
    }

    /**
     * Tests that setByArray() leaves untouched fields at their defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = new Page();
        $model->setByArray(['title' => 'Only Title']);

        self::assertSame('Only Title', $model->getTitle());
        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getContent());
        self::assertSame('', $model->getPerma());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = new Page();
        $model->setByArray(['unknown' => 'value', 'title' => 'Test']);

        self::assertSame('Test', $model->getTitle());
        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that getArray() includes all fields and the id.
     */
    public function testGetArrayWithId()
    {
        $model = (new Page())
            ->setId(1)
            ->setPerma('impressum')
            ->setTitle('Impressum')
            ->setContent('Content')
            ->setDescription('Description')
            ->setKeywords('keywords')
            ->setLocale('en')
            ->setDateCreated('2024-01-01 00:00:00');

        self::assertSame([
            'page_id' => 1,
            'description' => 'Description',
            'keywords' => 'keywords',
            'title' => 'Impressum',
            'content' => 'Content',
            'perma' => 'impressum',
            'locale' => 'en',
            'date_created' => '2024-01-01 00:00:00',
        ], $model->getArray());
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new Page())
            ->setId(1)
            ->setTitle('Title');

        self::assertSame([
            'description' => '',
            'keywords' => '',
            'title' => 'Title',
            'content' => '',
            'perma' => '',
            'locale' => '',
            'date_created' => '',
        ], $model->getArray(false));
    }
}

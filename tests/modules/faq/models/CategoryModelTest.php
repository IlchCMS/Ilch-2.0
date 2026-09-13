<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Faq\Models;

use PHPUnit\Framework\TestCase;

class CategoryModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Category();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Category();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new Category();
        $model->setTitle('General');

        self::assertSame('General', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new Category();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setReadAccess() sets and returns the read access.
     */
    public function testSetReadAccess()
    {
        $model = new Category();
        $model->setReadAccess('1,2,3');

        self::assertSame('1,2,3', $model->getReadAccess());
    }

    /**
     * Tests that setReadAccess() accepts 'all' as a valid value.
     */
    public function testSetReadAccessAll()
    {
        $model = new Category();
        $model->setReadAccess('all');

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Category();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setReadAccess('all'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Category())
            ->setId(3)
            ->setTitle('Technical')
            ->setReadAccess('1,2');

        self::assertSame(3, $model->getId());
        self::assertSame('Technical', $model->getTitle());
        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests that default values are 0 for id, empty string for title/read_access.
     */
    public function testDefaultValues()
    {
        $model = new Category();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getReadAccess());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid for new entries).
     */
    public function testSetIdZero()
    {
        $model = new Category();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Category();
        $model->setId(1)->setTitle('Old')->setReadAccess('1');

        $model->setId(2)->setTitle('New')->setReadAccess('all');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getTitle());
        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests getArray() with id included.
     */
    public function testGetArrayWithId()
    {
        $model = new Category();
        $model->setId(5)->setTitle('General')->setReadAccess('all');

        $array = $model->getArray();

        self::assertSame([
            'id'              => 5,
            'title'           => 'General',
            'read_access_all' => 1,
        ], $array);
    }

    /**
     * Tests getArray() without id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new Category();
        $model->setId(5)->setTitle('General')->setReadAccess('1,2');

        $array = $model->getArray(false);

        self::assertSame([
            'title'           => 'General',
            'read_access_all' => 0,
        ], $array);
    }

    /**
     * Tests that read_access_all is 1 only when readAccess is 'all'.
     */
    public function testGetArrayReadAccessAllMapping()
    {
        $modelAll = new Category();
        $modelAll->setTitle('X')->setReadAccess('all');
        self::assertSame(1, $modelAll->getArray(false)['read_access_all']);

        $modelGroups = new Category();
        $modelGroups->setTitle('X')->setReadAccess('1,2,3');
        self::assertSame(0, $modelGroups->getArray(false)['read_access_all']);

        $modelEmpty = new Category();
        $modelEmpty->setTitle('X');
        self::assertSame(0, $modelEmpty->getArray(false)['read_access_all']);
    }

    /**
     * Tests setByArray() with standard fields.
     */
    public function testSetByArray()
    {
        $model = new Category();
        $model->setByArray([
            'id'              => 4,
            'title'           => 'FAQ Category',
            'read_access_all' => 1,
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame('FAQ Category', $model->getTitle());
        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests setByArray() with explicit read_access field (from GROUP_CONCAT).
     */
    public function testSetByArrayWithReadAccess()
    {
        $model = new Category();
        $model->setByArray([
            'id'          => 2,
            'title'       => 'Technical',
            'read_access' => '1,2',
        ]);

        self::assertSame(2, $model->getId());
        self::assertSame('Technical', $model->getTitle());
        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests setByArray() with read_access_all = 0 does NOT overwrite read_access.
     */
    public function testSetByArrayReadAccessAllZero()
    {
        $model = new Category();
        $model->setByArray([
            'id'              => 2,
            'title'           => 'Technical',
            'read_access'     => '1,2',
            'read_access_all' => 0,
        ]);

        // read_access_all = 0 should NOT set readAccess to 'all'
        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests setByArray() is chainable.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new Category();
        $result = $model->setByArray(['id' => 1, 'title' => 'Test']);

        self::assertSame($model, $result);
    }
}

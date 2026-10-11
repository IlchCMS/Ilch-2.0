<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Article\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Article\Models\Category as CategoryModel;
use Modules\Article\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the category mapper class.
 *
 * @package ilch_phpunit
 */
class CategoryTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;
    private Category $categoryMapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/categories_table.yml');

        $this->categoryMapper = new CategoryMapper();
    }

    public function testGetCategories()
    {
        $categories = $this->categoryMapper->getCategories();

        self::assertCount(2, $categories);
        self::assertSame(1, $categories[0]->getId());
        self::assertSame('TestName1', $categories[0]->getName());
        self::assertSame(2, $categories[1]->getId());
        self::assertSame('TestName2', $categories[1]->getName());
    }

    public function testGetCategoryById()
    {
        $category = $this->categoryMapper->getCategoryById(1);

        self::assertSame(1, $category->getId());
        self::assertSame('TestName1', $category->getName());
    }

    public function testGetCategoryByIdInvalid()
    {
        $category = $this->categoryMapper->getCategoryById(0);

        self::assertFalse($category);
    }

    public function testSortInvalidId()
    {
        $affectedRows = $this->categoryMapper->sort(-1, 2);

        self::assertSame(0, $affectedRows);
    }

    public function testSort()
    {
        $affectedRows = $this->categoryMapper->sort(1, 2);
        $categories = $this->categoryMapper->getCategories();

        self::assertSame(1, $affectedRows);
        self::assertCount(2, $categories);
        self::assertSame(2, $categories[0]->getId());
        self::assertSame(1, $categories[1]->getId());
    }

    public function testSortReturnValueNotId()
    {
        $affectedRows = $this->categoryMapper->sort(2, 3);
        $categories = $this->categoryMapper->getCategories();

        self::assertSame(1, $affectedRows);
        self::assertCount(2, $categories);
        self::assertSame(1, $categories[0]->getId());
        self::assertSame(2, $categories[1]->getId());
    }

    public function testSave()
    {
        $model = new CategoryModel();

        $model->setName('TestName3');

        $id = $this->categoryMapper->save($model);
        $category = $this->categoryMapper->getCategoryById(3);

        self::assertSame(3, $id);
        self::assertSame(3, $category->getId());
        self::assertSame('TestName3', $category->getName());
    }

    public function testDelete()
    {
        $affectedRows = $this->categoryMapper->delete(2);
        $category = $this->categoryMapper->getCategoryById(2);

        self::assertEquals(1, $affectedRows);
        self::assertFalse($category);
    }

    /**
     * Tests that getCategories() returns null when no categories exist.
     */
    public function testGetCategoriesEmpty()
    {
        $this->categoryMapper->delete(1);
        $this->categoryMapper->delete(2);

        $categories = $this->categoryMapper->getCategories();

        self::assertNull($categories);
    }

    /**
     * Tests that getCategories() filters via the where parameter.
     */
    public function testGetCategoriesWithWhere()
    {
        $categories = $this->categoryMapper->getCategories(['id' => 2]);

        self::assertCount(1, $categories);
        self::assertInstanceOf(CategoryModel::class, $categories[0]);
        self::assertSame(2, $categories[0]->getId());
        self::assertSame('TestName2', $categories[0]->getName());
    }

    /**
     * Tests that getCategories() returns null when the where clause matches nothing.
     */
    public function testGetCategoriesWhereNoMatch()
    {
        self::assertNull($this->categoryMapper->getCategories(['id' => 9999]));
    }

    /**
     * Tests that getCategoryById() returns false for a non-existent id.
     */
    public function testGetCategoryByIdNotFound()
    {
        self::assertFalse($this->categoryMapper->getCategoryById(9999));
    }

    /**
     * Tests updating an existing category via save().
     */
    public function testSaveUpdate()
    {
        $model = new CategoryModel();
        $model->setId(1);
        $model->setName('Updated Name');

        $id = $this->categoryMapper->save($model);

        self::assertSame(1, $id);

        $category = $this->categoryMapper->getCategoryById(1);
        self::assertSame(1, $category->getId());
        self::assertSame('Updated Name', $category->getName());
    }

    /**
     * Tests that an update does not affect other categories.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CategoryModel();
        $model->setId(1);
        $model->setName('Changed Name');

        $this->categoryMapper->save($model);

        $other = $this->categoryMapper->getCategoryById(2);
        self::assertSame('TestName2', $other->getName());
    }

    /**
     * Tests that save() with a non-existent id falls back to inserting
     * a new category (auto-increment id is returned).
     */
    public function testSaveWithNonExistentIdInserts()
    {
        $model = new CategoryModel();
        $model->setId(9999);
        $model->setName('Ghost');

        $id = $this->categoryMapper->save($model);

        self::assertSame(3, $id);
        self::assertFalse($this->categoryMapper->getCategoryById(9999));

        $category = $this->categoryMapper->getCategoryById(3);
        self::assertSame('Ghost', $category->getName());
        self::assertCount(3, $this->categoryMapper->getCategories());
    }

    /**
     * Tests saving into an empty table: sort falls back to 1 and stays ordered.
     */
    public function testSaveOnEmptyTable()
    {
        $this->categoryMapper->delete(1);
        $this->categoryMapper->delete(2);

        $first = new CategoryModel();
        $first->setName('First');
        $id = $this->categoryMapper->save($first);

        $category = $this->categoryMapper->getCategoryById($id);
        self::assertSame('First', $category->getName());

        // Second insert must be sorted after the first one.
        $second = new CategoryModel();
        $second->setName('Second');
        $this->categoryMapper->save($second);

        $categories = $this->categoryMapper->getCategories();
        self::assertCount(2, $categories);
        self::assertSame('First', $categories[0]->getName());
        self::assertSame('Second', $categories[1]->getName());
    }

    /**
     * Tests that new categories are appended after the existing ones (sort order).
     */
    public function testSaveAppendsToSortOrder()
    {
        $model = new CategoryModel();
        $model->setName('TestName3');

        $id = $this->categoryMapper->save($model);

        $categories = $this->categoryMapper->getCategories();

        self::assertSame(3, $id);
        self::assertCount(3, $categories);
        self::assertSame(1, $categories[0]->getId());
        self::assertSame(2, $categories[1]->getId());
        self::assertSame(3, $categories[2]->getId());
    }

    /**
     * Tests that delete() on a non-existent id returns 0 and keeps existing rows.
     */
    public function testDeleteNotFound()
    {
        $affectedRows = $this->categoryMapper->delete(9999);

        self::assertSame(0, $affectedRows);
        self::assertCount(2, $this->categoryMapper->getCategories());
    }

    /**
     * Returns database schema SQL statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}

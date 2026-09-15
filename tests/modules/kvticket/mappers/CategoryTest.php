<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Kvticket\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Kvticket\Config\Config as ModuleConfig;
use Modules\Kvticket\Mappers\Category as CategoryMapper;
use Modules\Kvticket\Models\Category as CategoryModel;

class CategoryTest extends DatabaseTestCase
{
    /**
     * @var CategoryMapper
     */
    protected Category $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new CategoryMapper();
    }

    /**
     * Tests that getCategories() returns all categories.
     */
    public function testGetCategories()
    {
        $categories = $this->out->getCategories();

        self::assertIsArray($categories);
        self::assertCount(2, $categories);
        self::assertInstanceOf(CategoryModel::class, $categories[0]);
    }

    /**
     * Tests that getCategories() returns correct fields for the first category.
     */
    public function testGetCategoriesFields()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals('Bug Report', $categories[0]->getTitle());
    }

    /**
     * Tests that getCategories() returns correct fields for the second category.
     */
    public function testGetCategoriesSecond()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(2, $categories[1]->getId());
        self::assertEquals('Feature Request', $categories[1]->getTitle());
    }

    /**
     * Tests that getCategories() returns empty array when no categories exist.
     */
    public function testGetCategoriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        $categories = $this->out->getCategories();

        self::assertIsArray($categories);
        self::assertCount(0, $categories);
    }

    /**
     * Tests that getCategoryById() returns the correct category.
     */
    public function testGetCategoryById()
    {
        $category = $this->out->getCategoryById(1);

        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('Bug Report', $category->getTitle());
    }

    /**
     * Tests that getCategoryById() returns null for a non-existent id.
     */
    public function testGetCategoryByIdNotFound()
    {
        $category = $this->out->getCategoryById(9999);

        self::assertNull($category);
    }

    /**
     * Tests inserting a new category via save().
     */
    public function testSaveInsert()
    {
        $model = new CategoryModel();
        $model->setId(0)
            ->setTitle('Enhancement');

        $this->out->save($model);

        $categories = $this->out->getCategories();
        self::assertCount(3, $categories);

        $new = $categories[2];
        self::assertGreaterThan(2, $new->getId());
        self::assertEquals('Enhancement', $new->getTitle());
    }

    /**
     * Tests updating an existing category via save().
     */
    public function testSaveUpdate()
    {
        $model = new CategoryModel();
        $model->setId(1)
            ->setTitle('Updated Bug Report');

        $this->out->save($model);

        $category = $this->out->getCategoryById(1);
        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('Updated Bug Report', $category->getTitle());
    }

    /**
     * Tests that update does not affect other categories.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CategoryModel();
        $model->setId(1)
            ->setTitle('Changed');

        $this->out->save($model);

        $other = $this->out->getCategoryById(2);
        self::assertNotNull($other);
        self::assertEquals('Feature Request', $other->getTitle());
    }

    /**
     * Tests that delete() removes a category.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getCategoryById(1));

        $categories = $this->out->getCategories();
        self::assertCount(1, $categories);
        self::assertEquals(2, $categories[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $categories = $this->out->getCategories();
        self::assertCount(2, $categories);
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getCategories();
        self::assertCount(2, $before);

        $model = new CategoryModel();
        $model->setId(0)
            ->setTitle('New Entry');

        $this->out->save($model);

        $after = $this->out->getCategories();
        self::assertCount(3, $after);

        self::assertEquals('Bug Report', $after[0]->getTitle());
        self::assertEquals('Feature Request', $after[1]->getTitle());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}

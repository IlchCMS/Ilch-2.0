<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Link\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Link\Config\Config as ModuleConfig;
use Modules\Link\Mappers\Category as CategoryMapper;
use Modules\Link\Models\Category as CategoryModel;

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
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getCategories() returns all categories with count.
     */
    public function testGetCategories()
    {
        $categories = $this->out->getCategories();

        self::assertNotNull($categories);
        self::assertCount(3, $categories);
        self::assertInstanceOf(CategoryModel::class, $categories[0]);
    }

    /**
     * Tests that getCategories() returns correct fields for the first category.
     */
    public function testGetCategoriesFields()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals(0, $categories[0]->getParentId());
        self::assertEquals(1, $categories[0]->getPosition());
        self::assertEquals('Technology', $categories[0]->getName());
        self::assertEquals('Technology links', $categories[0]->getDesc());
    }

    /**
     * Tests that getCategories() returns correct fields for the second category.
     */
    public function testGetCategoriesSecond()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(2, $categories[1]->getId());
        self::assertEquals(1, $categories[1]->getParentId());
        self::assertEquals('Programming', $categories[1]->getName());
        self::assertEquals('Programming resources', $categories[1]->getDesc());
    }

    /**
     * Tests that getCategories() returns correct fields for the third category.
     */
    public function testGetCategoriesThird()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(3, $categories[2]->getId());
        self::assertEquals(0, $categories[2]->getParentId());
        self::assertEquals(2, $categories[2]->getPosition());
        self::assertEquals('Design', $categories[2]->getName());
        self::assertEquals('Design resources', $categories[2]->getDesc());
    }

    /**
     * Tests that getCategories() returns null when no categories exist.
     */
    public function testGetCategoriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $categories = $this->out->getCategories();

        self::assertNull($categories);
    }

    /**
     * Tests that getCategoryById() returns the correct category.
     */
    public function testGetCategoryById()
    {
        $category = $this->out->getCategoryById(1);

        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('Technology', $category->getName());
        self::assertEquals('Technology links', $category->getDesc());
        self::assertEquals(0, $category->getParentId());
    }

    /**
     * Tests that getCategoryById() returns a child category.
     */
    public function testGetCategoryByIdChild()
    {
        $category = $this->out->getCategoryById(2);

        self::assertNotNull($category);
        self::assertEquals(2, $category->getId());
        self::assertEquals(1, $category->getParentId());
        self::assertEquals('Programming', $category->getName());
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
     * Tests that getCategoriesByParentId() returns child categories.
     */
    public function testGetCategoriesByParentId()
    {
        $categories = $this->out->getCategoriesByParentId(1);

        self::assertNotNull($categories);
        self::assertCount(1, $categories);
        self::assertEquals(2, $categories[0]->getId());
        self::assertEquals('Programming', $categories[0]->getName());
    }

    /**
     * Tests that getCategoriesByParentId() returns null when no children exist.
     */
    public function testGetCategoriesByParentIdEmpty()
    {
        $categories = $this->out->getCategoriesByParentId(3);

        self::assertNull($categories);
    }

    /**
     * Tests that getCategoriesByParentId(0) returns root-level categories.
     */
    public function testGetCategoriesByParentIdRoot()
    {
        $categories = $this->out->getCategoriesByParentId(0);

        self::assertNotNull($categories);
        self::assertCount(2, $categories);
        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals(3, $categories[1]->getId());
    }

    /**
     * Tests that getCategoriesForParent() returns the parent chain (grandparent to self).
     */
    public function testGetCategoriesForParent()
    {
        // Category 2 (Programming) has parent_id=1 (Technology)
        $categories = $this->out->getCategoriesForParent(2);

        self::assertNotNull($categories);
        self::assertCount(2, $categories);

        // First should be the root (Technology), second should be the child (Programming)
        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals('Technology', $categories[0]->getName());
        self::assertEquals(2, $categories[1]->getId());
        self::assertEquals('Programming', $categories[1]->getName());
    }

    /**
     * Tests that getCategoriesForParent() returns a single-element array for root categories.
     */
    public function testGetCategoriesForParentRoot()
    {
        // Category 1 (Technology) has parent_id=0, so only itself
        $categories = $this->out->getCategoriesForParent(1);

        self::assertNotNull($categories);
        self::assertCount(1, $categories);
        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals('Technology', $categories[0]->getName());
    }

    /**
     * Tests that getCategoriesForParent() returns null for a non-existent id.
     */
    public function testGetCategoriesForParentNotFound()
    {
        $categories = $this->out->getCategoriesForParent(9999);

        self::assertNull($categories);
    }

    /**
     * Tests that updatePositionById() updates the position.
     */
    public function testUpdatePositionById()
    {
        $result = $this->out->updatePositionById(1, 5);

        self::assertTrue($result);

        $category = $this->out->getCategoryById(1);
        self::assertNotNull($category);
        self::assertEquals(5, $category->getPosition());
    }

    /**
     * Tests that updatePositionById() does not affect other categories.
     */
    public function testUpdatePositionByIdDoesNotAffectOthers()
    {
        $this->out->updatePositionById(1, 5);

        $other = $this->out->getCategoryById(2);
        self::assertNotNull($other);
        self::assertEquals(1, $other->getPosition());
    }

    /**
     * Tests that save() inserts a new category when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new CategoryModel();
        $model->setId(0)
            ->setName('Science')
            ->setDesc('Science resources')
            ->setParentId(0)
            ->setPosition(3)
            ->setAccess('');

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);

        $category = $this->out->getCategoryById($newId);
        self::assertNotNull($category);
        self::assertEquals('Science', $category->getName());
        self::assertEquals('Science resources', $category->getDesc());
        self::assertEquals(0, $category->getParentId());
        self::assertEquals(3, $category->getPosition());
    }

    /**
     * Tests that save() updates an existing category when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new CategoryModel();
        $model->setId(1)
            ->setName('Updated Technology')
            ->setDesc('Updated description')
            ->setParentId(0)
            ->setPosition(1)
            ->setAccess('');

        $returnedId = $this->out->save($model);

        self::assertEquals(1, $returnedId);

        $category = $this->out->getCategoryById(1);
        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('Updated Technology', $category->getName());
        self::assertEquals('Updated description', $category->getDesc());
    }

    /**
     * Tests that save() update does not affect other categories.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CategoryModel();
        $model->setId(1)
            ->setName('Changed')
            ->setDesc('Changed desc')
            ->setParentId(0)
            ->setPosition(1)
            ->setAccess('');

        $this->out->save($model);

        $other = $this->out->getCategoryById(2);
        self::assertNotNull($other);
        self::assertEquals('Programming', $other->getName());
        self::assertEquals('Programming resources', $other->getDesc());
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getCategories();
        self::assertCount(3, $before);

        $model = new CategoryModel();
        $model->setId(0)
            ->setName('New Category')
            ->setDesc('New desc')
            ->setParentId(0)
            ->setPosition(4)
            ->setAccess('');

        $this->out->save($model);

        $after = $this->out->getCategories();
        self::assertCount(4, $after);

        // Original categories untouched
        self::assertEquals('Technology', $after[0]->getName());
        self::assertEquals('Programming', $after[1]->getName());
        self::assertEquals('Design', $after[2]->getName());
    }

    /**
     * Tests that save() returns the id on update.
     */
    public function testSaveUpdateReturnsId()
    {
        $model = new CategoryModel();
        $model->setId(2)
            ->setName('Updated Programming')
            ->setDesc('Updated')
            ->setParentId(1)
            ->setPosition(1)
            ->setAccess('');

        $returnedId = $this->out->save($model);

        self::assertEquals(2, $returnedId);
    }

    /**
     * Tests that delete() removes a category.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);
        self::assertNull($this->out->getCategoryById(1));

        $categories = $this->out->getCategories();
        self::assertCount(2, $categories);
        self::assertEquals(2, $categories[0]->getId());
        self::assertEquals(3, $categories[1]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not remove other categories.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $categories = $this->out->getCategories();
        self::assertCount(3, $categories);
    }

    /**
     * Tests that multiple deletes remove all categories.
     */
    public function testDeleteAll()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        self::assertNull($this->out->getCategories());
    }

    /**
     * Tests that getCategories() includes linksCount via the COUNT join.
     */
    public function testGetCategoriesIncludesLinksCount()
    {
        $categories = $this->out->getCategories();

        // Category 1 (Technology) has 2 links
        self::assertEquals(1, $categories[0]->getLinksCount());

        // Category 2 (Programming) has 1 direct link (id=2)
        self::assertEquals(1, $categories[1]->getLinksCount());

        // Category 3 (Design) has 1 direct link (id=3)
        self::assertEquals(1, $categories[2]->getLinksCount());
    }

    /**
     * Tests that getCategories() returns null for a where that matches nothing.
     */
    public function testGetCategoriesWhereNoMatch()
    {
        $categories = $this->out->getEntriesBy(['lc.id' => 9999]);

        self::assertNull($categories);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}

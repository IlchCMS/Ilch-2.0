<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Category as CategoryMapper;
use Modules\Shop\Models\Category as CategoryModel;

class CategoryMapperTest extends DatabaseTestCase
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
        self::assertEquals(1, $categories[0]->getPos());
        self::assertEquals('T-Shirts', $categories[0]->getTitle());
        self::assertEquals('1,2,3', $categories[0]->getReadAccess());
    }

    /**
     * Tests that getCategories() returns correct fields for the second category.
     */
    public function testGetCategoriesSecond()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(2, $categories[1]->getId());
        self::assertEquals(2, $categories[1]->getPos());
        self::assertEquals('Cappy', $categories[1]->getTitle());
        self::assertEquals('1,2,3', $categories[1]->getReadAccess());
    }

    /**
     * Tests that getCategories() returns categories ordered by pos ASC.
     */
    public function testGetCategoriesOrdered()
    {
        $categories = $this->out->getCategories();

        self::assertEquals(1, $categories[0]->getPos());
        self::assertEquals(2, $categories[1]->getPos());
        self::assertEquals(3, $categories[2]->getPos());
    }

    /**
     * Tests that getCategories() returns an empty array when no categories exist.
     */
    public function testGetCategoriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

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

        self::assertInstanceOf(CategoryModel::class, $category);
        self::assertEquals(1, $category->getId());
        self::assertEquals(1, $category->getPos());
        self::assertEquals('T-Shirts', $category->getTitle());
        self::assertEquals('1,2,3', $category->getReadAccess());
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
     * Tests that getCategoriesByAccess() returns categories accessible by the given group(s).
     */
    public function testGetCategoriesByAccess()
    {
        $categories = $this->out->getCategoriesByAccess('1,2');

        self::assertIsArray($categories);
        self::assertCount(3, $categories);
    }

    /**
     * Tests that getCategoriesByAccess() accepts an array of group ids.
     */
    public function testGetCategoriesByAccessArray()
    {
        $categories = $this->out->getCategoriesByAccess([1, 2, 3]);

        self::assertIsArray($categories);
        self::assertCount(3, $categories);
    }

    /**
     * Tests that getCategoriesByAccess() returns empty array when no category has access for the group.
     */
    public function testGetCategoriesByAccessNoAccess()
    {
        $categories = $this->out->getCategoriesByAccess('9999');

        self::assertIsArray($categories);
        self::assertCount(0, $categories);
    }

    /**
     * Tests that updatePositionById() updates the position of a category.
     */
    public function testUpdatePositionById()
    {
        $updatedId = $this->out->updatePositionById(1, 10);

        self::assertSame(1, $updatedId);

        $category = $this->out->getCategoryById(1);
        self::assertInstanceOf(CategoryModel::class, $category);
        self::assertEquals(10, $category->getPos());
    }

    /**
     * Tests that updatePositionById() returns null for a non-existent category id.
     */
    public function testUpdatePositionByIdNotFound()
    {
        self::assertNull($this->out->updatePositionById(9999, 10));
    }

    /**
     * Tests that updatePositionById() does not affect other categories.
     */
    public function testUpdatePositionByIdDoesNotAffectOthers()
    {
        $this->out->updatePositionById(1, 10);

        $other = $this->out->getCategoryById(2);
        self::assertInstanceOf(CategoryModel::class, $other);
        self::assertEquals(2, $other->getPos());
    }

    /**
     * Tests inserting a new category via save().
     */
    public function testSaveInsert()
    {
        $model = new CategoryModel();
        $model->setId(0);
        $model->setTitle('Neue Kategorie');
        $model->setReadAccess('1,2');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(3, $newId);

        $category = $this->out->getCategoryById($newId);
        self::assertInstanceOf(CategoryModel::class, $category);
        self::assertEquals(4, $category->getPos());
        self::assertEquals('Neue Kategorie', $category->getTitle());
        self::assertEquals('1,2', $category->getReadAccess());

        self::assertCount(4, $this->out->getCategories());
    }

    /**
     * Tests that save() with id 0 does not affect existing categories.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getCategories();
        self::assertCount(3, $before);

        $model = new CategoryModel();
        $model->setId(0);
        $model->setTitle('New Entry');
        $model->setReadAccess('1');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(3, $newId);

        $after = $this->out->getCategories();
        self::assertCount(4, $after);

        // Original categories untouched
        self::assertEquals('T-Shirts', $after[0]->getTitle());
        self::assertEquals('Cappy', $after[1]->getTitle());
        self::assertEquals('Taschen', $after[2]->getTitle());
    }

    /**
     * Tests updating an existing category via save().
     */
    public function testSaveUpdate()
    {
        $model = new CategoryModel();
        $model->setId(1);
        $model->setTitle('Updated Title');
        $model->setReadAccess('2,3');

        $savedId = $this->out->save($model);

        self::assertSame(1, $savedId);

        $category = $this->out->getCategoryById(1);
        self::assertInstanceOf(CategoryModel::class, $category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('Updated Title', $category->getTitle());
        self::assertEquals('2,3', $category->getReadAccess());
    }

    /**
     * Tests that save() update does not affect the position of the category.
     */
    public function testSaveUpdateDoesNotChangePosition()
    {
        $model = new CategoryModel();
        $model->setId(1);
        $model->setTitle('Changed Title');
        $model->setReadAccess('1');

        $this->out->save($model);

        $category = $this->out->getCategoryById(1);
        self::assertEquals(1, $category->getPos());
    }

    /**
     * Tests that update does not affect other categories.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CategoryModel();
        $model->setId(1);
        $model->setTitle('Changed Name');
        $model->setReadAccess('1');

        $this->out->save($model);

        $other = $this->out->getCategoryById(2);
        self::assertInstanceOf(CategoryModel::class, $other);
        self::assertEquals('Cappy', $other->getTitle());
        self::assertEquals('1,2,3', $other->getReadAccess());
    }

    /**
     * Tests that save() with empty readAccess removes all access entries.
     */
    public function testSaveWithEmptyReadAccess()
    {
        $model = new CategoryModel();
        $model->setId(1);
        $model->setTitle('No Access');
        $model->setReadAccess('');

        $this->out->save($model);

        $category = $this->out->getCategoryById(1);
        self::assertInstanceOf(CategoryModel::class, $category);
        self::assertEquals('No Access', $category->getTitle());
        self::assertEquals('', $category->getReadAccess());
    }

    /**
     * Tests that delete() removes a category.
     */
    public function testDelete()
    {
        $deleted = $this->out->delete(1);

        self::assertTrue($deleted);
        self::assertNull($this->out->getCategoryById(1));

        // Remaining categories should still be present
        $categories = $this->out->getCategories();
        self::assertCount(2, $categories);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $deleted = $this->out->delete(9999);

        self::assertFalse($deleted);

        // Existing categories should be unaffected
        $categories = $this->out->getCategories();
        self::assertCount(3, $categories);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $coreSql = 'CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                    `moduleKey` VARCHAR(255) NOT NULL,
                    `type` VARCHAR(255) NOT NULL,
                    `desc` VARCHAR(255) NOT NULL,
                    `text` TEXT NOT NULL,
                    `locale` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                    (1, "Admin"),
                    (2, "Member"),
                    (3, "Guest");';

        $config = new ModuleConfig();

        return $coreSql . "\n" . $config->getInstallSql();
    }
}

<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Faq\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Faq\Config\Config as ModuleConfig;
use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Models\Category as CategoryModel;

class CategoryTest extends DatabaseTestCase
{
    /**
     * @var CategoryMapper
     */
    protected CategoryMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new Category();
    }

    /**
     * Tests that checkDB() returns true when both tables exist.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getCategories() with no group filter returns all categories.
     */
    public function testGetCategoriesAll()
    {
        $categories = $this->out->getCategories([], [], null);

        self::assertNotNull($categories);
        self::assertCount(2, $categories);
        self::assertInstanceOf(CategoryModel::class, $categories[0]);
    }

    /**
     * Tests that getCategories() with default groupIds ('3'/guest) returns only
     * categories with read_access_all = 1.
     */
    public function testGetCategoriesGuestDefault()
    {
        // Default groupIds is '3' (guest). Cat 1 has read_access_all=1, Cat 2 does not.
        $categories = $this->out->getCategories();

        self::assertNotNull($categories);
        self::assertCount(1, $categories);
        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals('General', $categories[0]->getTitle());
    }

    /**
     * Tests that getCategories() with groupIds '1' (admin) returns both categories.
     */
    public function testGetCategoriesAdminGroup()
    {
        $categories = $this->out->getCategories([], [], '1');

        self::assertNotNull($categories);
        self::assertCount(2, $categories);
        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals(2, $categories[1]->getId());
    }

    /**
     * Tests that getCategories() returns correct fields.
     */
    public function testGetCategoriesFields()
    {
        $categories = $this->out->getCategories([], [], null);

        self::assertEquals(1, $categories[0]->getId());
        self::assertEquals('General', $categories[0]->getTitle());
        self::assertEquals('all', $categories[0]->getReadAccess());

        self::assertEquals(2, $categories[1]->getId());
        self::assertEquals('Technical', $categories[1]->getTitle());
    }

    /**
     * Tests that getCategoryById() returns the correct category.
     */
    public function testGetCategoryById()
    {
        $category = $this->out->getCategoryById(1, null);

        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('General', $category->getTitle());
        self::assertEquals('all', $category->getReadAccess());
    }

    /**
     * Tests that getCategoryById() with group filter excludes unauthorized categories.
     */
    public function testGetCategoryByIdWithGroupFilter()
    {
        // Cat 2 is only accessible to groups 1,2 — not group 3.
        $category = $this->out->getCategoryById(2);

        self::assertNull($category);
    }

    /**
     * Tests that getCategoryById() with group 1 returns Cat 2.
     */
    public function testGetCategoryByIdWithGroupOne()
    {
        $category = $this->out->getCategoryById(2, '1');

        self::assertNotNull($category);
        self::assertEquals(2, $category->getId());
        self::assertEquals('Technical', $category->getTitle());
    }

    /**
     * Tests that getCategoryById() returns null for a non-existent id.
     */
    public function testGetCategoryByIdNotFound()
    {
        $category = $this->out->getCategoryById(9999, null);

        self::assertNull($category);
    }

    /**
     * Tests inserting a new category via save().
     */
    public function testSaveInsert()
    {
        $model = new CategoryModel();
        $model->setId(0)
            ->setTitle('Shipping')
            ->setReadAccess('1,2,3');

        $result = $this->out->save($model);

        self::assertGreaterThan(2, $result);

        $categories = $this->out->getCategories([], [], null);
        self::assertCount(3, $categories);

        $new = null;
        foreach ($categories as $cat) {
            if ($cat->getTitle() === 'Shipping') {
                $new = $cat;
                break;
            }
        }
        self::assertNotNull($new);
        self::assertEquals('Shipping', $new->getTitle());
    }

    /**
     * Tests updating an existing category via save().
     */
    public function testSaveUpdate()
    {
        $model = new CategoryModel();
        $model->setId(1)
            ->setTitle('Updated General')
            ->setReadAccess('all');

        $this->out->save($model);

        $category = $this->out->getCategoryById(1, null);
        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals('Updated General', $category->getTitle());
        self::assertEquals('all', $category->getReadAccess());
    }

    /**
     * Tests that update does not affect other categories.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CategoryModel();
        $model->setId(1)
            ->setTitle('Changed Name')
            ->setReadAccess('1');

        $this->out->save($model);

        $other = $this->out->getCategoryById(2, null);
        self::assertNotNull($other);
        self::assertEquals('Technical', $other->getTitle());
    }

    /**
     * Tests that save() with readAccess 'all' does NOT insert rows into faqs_cats_access.
     */
    public function testSaveReadAccessAllNoAccessRows()
    {
        $model = new CategoryModel();
        $model->setId(0)
            ->setTitle('Public')
            ->setReadAccess('all');

        $id = $this->out->save($model);

        // Verify no rows in faqs_cats_access for this category
        $result = $this->db->select()
            ->from('faqs_cats_access')
            ->where(['cat_id' => $id])
            ->execute()
            ->fetchRows();

        self::assertEmpty($result);
    }

    /**
     * Tests that save() with specific groupIds inserts rows into faqs_cats_access
     * and always adds admin (group 1).
     */
    public function testSaveReadAccessSpecificGroups()
    {
        $model = new CategoryModel();
        $model->setId(0)
            ->setTitle('Members Only')
            ->setReadAccess('2,3');

        $id = $this->out->save($model);

        // Should have group 1 (admin auto-added) + 2 + 3
        $result = $this->db->select('group_id')
            ->from('faqs_cats_access')
            ->where(['cat_id' => $id])
            ->execute()
            ->fetchList();

        sort($result);
        self::assertEquals([1, 2, 3], array_map('intval', $result));
    }

    /**
     * Tests that saveReadAccess() with addAdmin=false does not add admin group.
     */
    public function testSaveReadAccessWithoutAdmin()
    {
        // Insert a real category row to satisfy the FK constraint
        $id = $this->db->insert('faqs_cats')
            ->values(['title' => 'Test No Admin', 'read_access_all' => 0])
            ->execute();

        $this->out->saveReadAccess($id, '2,3', false);

        $result = $this->db->select('group_id')
            ->from('faqs_cats_access')
            ->where(['cat_id' => $id])
            ->execute()
            ->fetchList();

        sort($result);
        self::assertEquals([2, 3], array_map('intval', $result));
    }

    /**
     * Tests that saveReadAccess() clears old entries before inserting new ones.
     */
    public function testSaveReadAccessClearsOldEntries()
    {
        // Cat 2 initially has access for groups 1 and 2
        $this->out->saveReadAccess(2, '2,3');

        $result = $this->db->select('group_id')
            ->from('faqs_cats_access')
            ->where(['cat_id' => 2])
            ->execute()
            ->fetchList();

        sort($result);
        // Should be 1 (admin) + 2 + 3
        self::assertEquals([1, 2, 3], array_map('intval', $result));
    }

    /**
     * Tests that delete() removes a category.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getCategoryById(1, null));

        $categories = $this->out->getCategories([], [], null);
        self::assertCount(1, $categories);
        self::assertEquals(2, $categories[0]->getId());
    }

    /**
     * Tests that delete() cascades to faqs (FK ON DELETE CASCADE).
     */
    public function testDeleteCascadesToFaqs()
    {
        // Cat 1 has faq id=1
        $this->out->delete(1);

        $faqMapper = new Faq();
        $faq = $faqMapper->getFaqById(1);
        self::assertNull($faq);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $categories = $this->out->getCategories([], [], null);
        self::assertCount(2, $categories);
    }

    /**
     * Tests that getCategories() with array groupIds works the same as string.
     */
    public function testGetCategoriesWithArrayGroupIds()
    {
        $byString = $this->out->getCategories([], [], '1');
        $byArray = $this->out->getCategories([], [], [1]);

        self::assertCount(2, $byString);
        self::assertCount(2, $byArray);
        self::assertEquals($byString[0]->getId(), $byArray[0]->getId());
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

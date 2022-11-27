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
    protected $phpunitDataset;
    private $categoryMapper;

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
        $categories = $this->categoryMapper->getCategories();

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
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}

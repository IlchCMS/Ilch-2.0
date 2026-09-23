<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Properties as PropertiesMapper;
use Modules\Shop\Models\Property as PropertyModel;

class PropertiesMapperTest extends DatabaseTestCase
{
    protected PropertiesMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PropertiesMapper();
    }

    /**
     * Tests that getProperties() orders properties by name ascending.
     */
    public function testGetProperties(): void
    {
        $nameA = $this->uniqueName('aa');
        $nameZ = $this->uniqueName('zz');

        $this->out->save($this->createPropertyModel(0, $nameA, true));
        $this->out->save($this->createPropertyModel(0, $nameZ, false));

        $properties = $this->out->getProperties();

        self::assertIsArray($properties);

        $names = [];
        foreach ($properties as $property) {
            $names[] = $property->getName();
        }

        $positionA = array_search($nameA, $names, true);
        $positionZ = array_search($nameZ, $names, true);

        self::assertIsInt($positionA);
        self::assertIsInt($positionZ);
        self::assertLessThan($positionZ, $positionA);
    }

    /**
     * Tests that getProperties() filters by where.
     */
    public function testGetPropertiesWithWhere(): void
    {
        $name = $this->uniqueName('prop');
        $id = $this->out->save($this->createPropertyModel(0, $name, true));

        $properties = $this->out->getProperties(['name' => $name]);

        self::assertIsArray($properties);
        self::assertCount(1, $properties);
        self::assertSame([$id], array_keys($properties));
        self::assertInstanceOf(PropertyModel::class, $properties[$id]);
        self::assertEquals($id, $properties[$id]->getId());
        self::assertEquals($name, $properties[$id]->getName());
        self::assertEquals(1, $properties[$id]->isEnabled());
    }

    /**
     * Tests that getProperties() returns an empty array when no matching properties exist.
     */
    public function testGetPropertiesEmpty(): void
    {
        $properties = $this->out->getProperties(['name' => $this->uniqueName('missing')]);

        self::assertIsArray($properties);
        self::assertCount(0, $properties);
    }

    /**
     * Tests that getPropertyById() returns the correct property.
     */
    public function testGetPropertyById(): void
    {
        $name = $this->uniqueName('prop');
        $id = $this->out->save($this->createPropertyModel(0, $name, false));

        $property = $this->out->getPropertyById($id);

        self::assertInstanceOf(PropertyModel::class, $property);
        self::assertEquals($id, $property->getId());
        self::assertEquals($name, $property->getName());
        self::assertEquals(0, $property->isEnabled());
    }

    /**
     * Tests that getPropertyById() returns null for a non-existent id.
     */
    public function testGetPropertyByIdNotFound(): void
    {
        $property = $this->out->getPropertyById(9999999);

        self::assertNull($property);
    }

    /**
     * Tests inserting a new property via save().
     */
    public function testSaveInsert(): void
    {
        $name = $this->uniqueName('new-prop');

        self::assertCount(0, $this->out->getProperties(['name' => $name]));

        $id = $this->out->save($this->createPropertyModel(0, $name, true));

        self::assertIsInt($id);
        self::assertGreaterThan(0, $id);

        $properties = $this->out->getProperties(['name' => $name]);

        self::assertCount(1, $properties);
        self::assertSame([$id], array_keys($properties));
        self::assertEquals($name, $properties[$id]->getName());
        self::assertEquals(1, $properties[$id]->isEnabled());
    }

    /**
     * Tests updating an existing property via save().
     */
    public function testSaveUpdate(): void
    {
        $originalName = $this->uniqueName('original');
        $updatedName = $this->uniqueName('updated');

        $id = $this->out->save($this->createPropertyModel(0, $originalName, true));

        $result = $this->out->save($this->createPropertyModel($id, $updatedName, false));

        self::assertIsInt($result);
        self::assertSame($id, $result);

        $property = $this->out->getPropertyById($id);

        self::assertInstanceOf(PropertyModel::class, $property);
        self::assertEquals($id, $property->getId());
        self::assertEquals($updatedName, $property->getName());
        self::assertEquals(0, $property->isEnabled());
    }

    /**
     * Tests that updateEnabled() changes only the enabled flag.
     */
    public function testUpdateEnabled(): void
    {
        $name = $this->uniqueName('enable-test');
        $id = $this->out->save($this->createPropertyModel(0, $name, true));

        $updatedId = $this->out->updateEnabled($id, false);

        self::assertSame($id, $updatedId);

        $property = $this->out->getPropertyById($id);

        self::assertInstanceOf(PropertyModel::class, $property);
        self::assertEquals($name, $property->getName());
        self::assertEquals(0, $property->isEnabled());

        $updatedId = $this->out->updateEnabled($id, true);

        self::assertSame($id, $updatedId);

        $property = $this->out->getPropertyById($id);

        self::assertInstanceOf(PropertyModel::class, $property);
        self::assertEquals($name, $property->getName());
        self::assertEquals(1, $property->isEnabled());
    }

    /**
     * Tests that updateEnabled() returns null when no property is updated.
     */
    public function testUpdateEnabledNotFound(): void
    {
        self::assertNull($this->out->updateEnabled(9999999, true));
    }

    /**
     * Tests that deletePropertyById() removes a property.
     */
    public function testDeletePropertyById(): void
    {
        $nameA = $this->uniqueName('delete-a');
        $nameZ = $this->uniqueName('delete-z');

        $idA = $this->out->save($this->createPropertyModel(0, $nameA, true));
        $idZ = $this->out->save($this->createPropertyModel(0, $nameZ, true));

        self::assertTrue($this->out->deletePropertyById($idA));

        self::assertNull($this->out->getPropertyById($idA));

        $remaining = $this->out->getProperties(['id' => $idZ]);

        self::assertCount(1, $remaining);
        self::assertSame([$idZ], array_keys($remaining));
        self::assertEquals($nameZ, $remaining[$idZ]->getName());
    }

    /**
     * Tests that deletePropertyById() on a non-existent id does not remove other properties.
     */
    public function testDeletePropertyByIdNotFound(): void
    {
        $name = $this->uniqueName('keep');
        $id = $this->out->save($this->createPropertyModel(0, $name, true));

        self::assertFalse($this->out->deletePropertyById(9999999));

        $property = $this->out->getPropertyById($id);

        self::assertInstanceOf(PropertyModel::class, $property);
        self::assertEquals($id, $property->getId());
        self::assertEquals($name, $property->getName());
    }

    private function createPropertyModel(int $id, string $name, bool $enabled): PropertyModel
    {
        $model = new PropertyModel();
        $model->setId($id);
        $model->setName($name);
        $model->setEnabled($enabled);

        return $model;
    }

    private function uniqueName(string $prefix): string
    {
        return $prefix . '_' . uniqid();
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

<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Propertyvalues as PropertyvaluesMapper;
use Modules\Shop\Mappers\Properties as PropertiesMapper;
use Modules\Shop\Models\Propertyvalue as PropertyvalueModel;
use Modules\Shop\Models\Property as PropertyModel;

class PropertyvaluesMapperTest extends DatabaseTestCase
{
    protected PropertyvaluesMapper $out;
    protected PropertiesMapper $propertyMapper;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PropertyvaluesMapper();
        $this->propertyMapper = new PropertiesMapper();
    }

    /**
     * Tests that getValues() returns values ordered by position ascending.
     */
    public function testGetValues(): void
    {
        $propertyId = $this->createProperty();

        $valueA = $this->uniqueValue('aa');
        $valueB = $this->uniqueValue('zz');

        $this->out->save($this->createValueModel($propertyId, 2, $valueB));
        $this->out->save($this->createValueModel($propertyId, 1, $valueA));

        $values = $this->out->getValues(['property_id' => $propertyId]);

        self::assertIsArray($values);
        self::assertCount(2, $values);

        $orderedValues = array_values($values);

        self::assertInstanceOf(PropertyvalueModel::class, $orderedValues[0]);
        self::assertEquals($propertyId, $orderedValues[0]->getPropertyId());
        self::assertEquals(1, $orderedValues[0]->getPosition());
        self::assertEquals($valueA, $orderedValues[0]->getValue());

        self::assertInstanceOf(PropertyvalueModel::class, $orderedValues[1]);
        self::assertEquals($propertyId, $orderedValues[1]->getPropertyId());
        self::assertEquals(2, $orderedValues[1]->getPosition());
        self::assertEquals($valueB, $orderedValues[1]->getValue());
    }

    /**
     * Tests that getValues() returns an empty array when no values match.
     */
    public function testGetValuesEmpty(): void
    {
        $propertyId = $this->createProperty();

        $values = $this->out->getValues(['property_id' => $propertyId]);

        self::assertIsArray($values);
        self::assertCount(0, $values);
    }

    /**
     * Tests that getValuesByPropertyId() returns null when no values exist.
     */
    public function testGetValuesByPropertyIdEmpty(): void
    {
        $propertyId = $this->createProperty();

        $values = $this->out->getValuesByPropertyId($propertyId);

        self::assertNull($values);
    }

    /**
     * Tests that getValuesByPropertyId() returns values for a property.
     */
    public function testGetValuesByPropertyId(): void
    {
        $propertyId = $this->createProperty();
        $emptyPropertyId = $this->createProperty();

        self::assertNull($this->out->getValuesByPropertyId($propertyId));

        $valueA = $this->uniqueValue('value-a');
        $valueB = $this->uniqueValue('value-b');

        $this->out->save($this->createValueModel($propertyId, 1, $valueA));
        $this->out->save($this->createValueModel($propertyId, 2, $valueB));

        $values = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($values);
        self::assertCount(2, $values);

        self::assertNull($this->out->getValuesByPropertyId($emptyPropertyId));
    }

    /**
     * Tests inserting a new value via save().
     */
    public function testSaveInsert(): void
    {
        $propertyId = $this->createProperty();
        $value = $this->uniqueValue('new');

        self::assertNull($this->out->getValuesByPropertyId($propertyId));

        $newId = $this->out->save($this->createValueModel($propertyId, 1, $value));

        self::assertIsInt($newId);
        self::assertGreaterThan(0, $newId);

        $values = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($values);
        self::assertCount(1, $values);

        $valueModel = reset($values);
        self::assertInstanceOf(PropertyvalueModel::class, $valueModel);
        self::assertEquals($newId, $valueModel->getId());
        self::assertEquals($propertyId, $valueModel->getPropertyId());
        self::assertEquals(1, $valueModel->getPosition());
        self::assertEquals($value, $valueModel->getValue());
    }

    /**
     * Tests updating an existing value via save().
     */
    public function testSaveUpdate(): void
    {
        $propertyId = $this->createProperty();
        $originalValue = $this->uniqueValue('original');
        $updatedValue = $this->uniqueValue('updated');

        $originalId = $this->out->save($this->createValueModel($propertyId, 1, $originalValue));

        self::assertIsInt($originalId);
        self::assertGreaterThan(0, $originalId);

        $values = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($values);
        self::assertCount(1, $values);

        $valueModel = reset($values);
        self::assertInstanceOf(PropertyvalueModel::class, $valueModel);

        $valueId = $valueModel->getId();

        self::assertEquals($originalId, $valueId);

        $updatedId = $this->out->save($this->createValueModel($propertyId, 5, $updatedValue, $valueId));

        self::assertIsInt($updatedId);
        self::assertEquals($valueId, $updatedId);

        $updatedValues = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($updatedValues);
        self::assertCount(1, $updatedValues);

        $updatedModel = reset($updatedValues);
        self::assertInstanceOf(PropertyvalueModel::class, $updatedModel);
        self::assertEquals($valueId, $updatedModel->getId());
        self::assertEquals($propertyId, $updatedModel->getPropertyId());
        self::assertEquals(5, $updatedModel->getPosition());
        self::assertEquals($updatedValue, $updatedModel->getValue());
    }

    /**
     * Tests that deleteValueById() removes a value.
     */
    public function testDeleteValueById(): void
    {
        $propertyId = $this->createProperty();

        $valueA = $this->uniqueValue('delete-a');
        $valueB = $this->uniqueValue('delete-b');

        $this->out->save($this->createValueModel($propertyId, 1, $valueA));
        $this->out->save($this->createValueModel($propertyId, 2, $valueB));

        $values = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($values);
        self::assertCount(2, $values);

        $firstValue = reset($values);
        self::assertInstanceOf(PropertyvalueModel::class, $firstValue);

        $deleted = $this->out->deleteValueById($firstValue->getId());

        self::assertTrue($deleted);

        $remainingValues = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($remainingValues);
        self::assertCount(1, $remainingValues);

        $remainingValue = reset($remainingValues);
        self::assertInstanceOf(PropertyvalueModel::class, $remainingValue);
        self::assertNotEquals($firstValue->getId(), $remainingValue->getId());
    }

    /**
     * Tests that deleteValueById() on a non-existent id does not remove other values.
     */
    public function testDeleteValueByIdNotFound(): void
    {
        $propertyId = $this->createProperty();
        $value = $this->uniqueValue('keep');

        $this->out->save($this->createValueModel($propertyId, 1, $value));

        $deleted = $this->out->deleteValueById(9999999);

        self::assertFalse($deleted);

        $values = $this->out->getValuesByPropertyId($propertyId);

        self::assertIsArray($values);
        self::assertCount(1, $values);

        $valueModel = reset($values);
        self::assertInstanceOf(PropertyvalueModel::class, $valueModel);
        self::assertEquals($value, $valueModel->getValue());
    }

    private function createProperty(): int
    {
        $model = new PropertyModel();
        $model->setId(0);
        $model->setName($this->uniqueName('property'));
        $model->setEnabled(true);

        $id = $this->propertyMapper->save($model);

        self::assertIsInt($id);

        return $id;
    }

    private function createValueModel(
        int $propertyId,
        int $position,
        string $value,
        int $id = 0
    ): PropertyvalueModel {
        $model = new PropertyvalueModel();
        $model->setId($id);
        $model->setPropertyId($propertyId);
        $model->setPosition($position);
        $model->setValue($value);

        return $model;
    }

    private function uniqueName(string $prefix): string
    {
        return $prefix . '_' . uniqid();
    }

    private function uniqueValue(string $prefix): string
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

<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Propertyvariants as PropertyvariantsMapper;
use Modules\Shop\Models\Propertyvariant as PropertyVariantModel;

class PropertyvariantsMapperTest extends DatabaseTestCase
{
    /**
     * @var PropertyvariantsMapper
     */
    protected PropertyvariantsMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PropertyvariantsMapper();
    }

    /**
     * Tests that getPropertiesVariants() returns all variants.
     */
    public function testGetPropertiesVariants()
    {
        $variants = $this->out->getPropertiesVariants();

        self::assertIsArray($variants);
        self::assertCount(3, $variants);
        self::assertInstanceOf(PropertyVariantModel::class, $variants[1]);
    }

    /**
     * Tests that getPropertiesVariants() returns correct fields for the first variant.
     */
    public function testGetPropertiesVariantsFields()
    {
        $variants = $this->out->getPropertiesVariants();

        $variant = $variants[1];
        self::assertEquals(1, $variant->getId());
        self::assertEquals(1, $variant->getItemId());
        self::assertEquals(1, $variant->getItemVariantId());
        self::assertEquals(1, $variant->getPropertyId());
        self::assertEquals(1, $variant->getValueId());
    }

    /**
     * Tests that getPropertiesVariants() returns correct fields for the second variant.
     */
    public function testGetPropertiesVariantsSecond()
    {
        $variants = $this->out->getPropertiesVariants();

        $variant = $variants[2];
        self::assertEquals(2, $variant->getId());
        self::assertEquals(1, $variant->getItemId());
        self::assertEquals(1, $variant->getItemVariantId());
        self::assertEquals(2, $variant->getPropertyId());
        self::assertEquals(3, $variant->getValueId());
    }

    /**
     * Tests that getPropertiesVariants() returns correct fields for the third variant.
     */
    public function testGetPropertiesVariantsThird()
    {
        $variants = $this->out->getPropertiesVariants();

        $variant = $variants[3];
        self::assertEquals(3, $variant->getId());
        self::assertEquals(2, $variant->getItemId());
        self::assertEquals(2, $variant->getItemVariantId());
        self::assertEquals(1, $variant->getPropertyId());
        self::assertEquals(2, $variant->getValueId());
    }

    /**
     * Tests that getPropertiesVariants() returns empty array when no variants match.
     */
    public function testGetPropertiesVariantsEmpty()
    {
        $variants = $this->out->getPropertiesVariants(['property_id' => 9999]);

        self::assertIsArray($variants);
        self::assertEmpty($variants);
    }

    /**
     * Tests that getPropertiesVariants() filters by where clause.
     */
    public function testGetPropertiesVariantsWithWhere()
    {
        $variants = $this->out->getPropertiesVariants(['property_id' => 1]);

        self::assertIsArray($variants);
        self::assertCount(2, $variants);

        foreach ($variants as $variant) {
            self::assertEquals(1, $variant->getPropertyId());
        }
    }

    /**
     * Tests that getPropertiesVariants() filters by item_id.
     */
    public function testGetPropertiesVariantsWithWhereItemId()
    {
        $variants = $this->out->getPropertiesVariants(['item_id' => 1]);

        self::assertIsArray($variants);
        self::assertCount(2, $variants);

        foreach ($variants as $variant) {
            self::assertEquals(1, $variant->getItemId());
        }
    }

    /**
     * Tests that getPropertyVariant() returns a single variant.
     */
    public function testGetPropertyVariant()
    {
        $variant = $this->out->getPropertyVariant(['id' => 1]);

        self::assertNotNull($variant);
        self::assertInstanceOf(PropertyVariantModel::class, $variant);
        self::assertEquals(1, $variant->getId());
        self::assertEquals(1, $variant->getItemId());
        self::assertEquals(1, $variant->getItemVariantId());
        self::assertEquals(1, $variant->getPropertyId());
        self::assertEquals(1, $variant->getValueId());
    }

    /**
     * Tests that getPropertyVariant() returns a different variant with different where.
     */
    public function testGetPropertyVariantByItemIdAndPropertyId()
    {
        $variant = $this->out->getPropertyVariant(['item_id' => 2, 'property_id' => 1]);

        self::assertNotNull($variant);
        self::assertEquals(3, $variant->getId());
        self::assertEquals(2, $variant->getItemId());
        self::assertEquals(2, $variant->getItemVariantId());
        self::assertEquals(1, $variant->getPropertyId());
        self::assertEquals(2, $variant->getValueId());
    }

    /**
     * Tests that getPropertyVariant() returns null when no variant matches.
     */
    public function testGetPropertyVariantNotFound()
    {
        $variant = $this->out->getPropertyVariant(['id' => 9999]);

        self::assertNull($variant);
    }

    /**
     * Tests that exists() returns true for an existing entry.
     */
    public function testExistsTrue()
    {
        $result = $this->out->exists(['id' => 1]);

        self::assertTrue($result);
    }

    /**
     * Tests that exists() returns true with multiple where conditions.
     */
    public function testExistsTrueWithMultipleWhere()
    {
        $result = $this->out->exists(['item_id' => 1, 'property_id' => 2]);

        self::assertTrue($result);
    }

    /**
     * Tests that exists() returns false for a non-existent entry.
     */
    public function testExistsFalse()
    {
        $result = $this->out->exists(['id' => 9999]);

        self::assertFalse($result);
    }

    /**
     * Tests that exists() returns false when no where matches.
     */
    public function testExistsFalseByPropertyId()
    {
        $result = $this->out->exists(['property_id' => 9999]);

        self::assertFalse($result);
    }

    /**
     * Tests inserting a new variant via save().
     */
    public function testSaveInsert()
    {
        $model = new PropertyVariantModel();
        $model->setItemId(1)
            ->setItemVariantId(1)
            ->setPropertyId(2)
            ->setValueId(3);

        $newId = $this->out->save($model);

        self::assertNotNull($newId);
        self::assertGreaterThan(3, $newId);

        $variant = $this->out->getPropertyVariant(['id' => $newId]);
        self::assertNotNull($variant);
        self::assertEquals(1, $variant->getItemId());
        self::assertEquals(1, $variant->getItemVariantId());
        self::assertEquals(2, $variant->getPropertyId());
        self::assertEquals(3, $variant->getValueId());
    }

    /**
     * Tests that save() returns the new id after insert.
     */
    public function testSaveInsertReturnsId()
    {
        $before = $this->out->getPropertiesVariants();
        self::assertCount(3, $before);

        $model = new PropertyVariantModel();
        $model->setItemId(2)
            ->setItemVariantId(2)
            ->setPropertyId(1)
            ->setValueId(1);

        $newId = $this->out->save($model);

        $after = $this->out->getPropertiesVariants();
        self::assertCount(4, $after);
        self::assertArrayHasKey($newId, $after);
    }

    /**
     * Tests updating an existing variant via save().
     */
    public function testSaveUpdate()
    {
        $model = new PropertyVariantModel();
        $model->setId(1)
            ->setItemId(1)
            ->setItemVariantId(1)
            ->setPropertyId(2)
            ->setValueId(3);

        $result = $this->out->save($model);

        self::assertEquals(1, $result);

        $variant = $this->out->getPropertyVariant(['id' => 1]);
        self::assertNotNull($variant);
        self::assertEquals(1, $variant->getId());
        self::assertEquals(2, $variant->getPropertyId());
        self::assertEquals(3, $variant->getValueId());
    }

    /**
     * Tests that update does not affect other variants.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new PropertyVariantModel();
        $model->setId(1)
            ->setItemId(1)
            ->setItemVariantId(1)
            ->setPropertyId(2)
            ->setValueId(3);

        $this->out->save($model);

        $other = $this->out->getPropertyVariant(['id' => 2]);
        self::assertNotNull($other);
        self::assertEquals(1, $other->getItemId());
        self::assertEquals(1, $other->getItemVariantId());
        self::assertEquals(2, $other->getPropertyId());
        self::assertEquals(3, $other->getValueId());
    }

    /**
     * Tests that save() with null id performs an insert.
     */
    public function testSaveNullIdInserts()
    {
        $before = $this->out->getPropertiesVariants();
        self::assertCount(3, $before);

        $model = new PropertyVariantModel();
        $model->setItemId(3)
            ->setItemVariantId(3)
            ->setPropertyId(1)
            ->setValueId(2);

        $this->out->save($model);

        $after = $this->out->getPropertiesVariants();
        self::assertCount(4, $after);

        // Original variants untouched
        self::assertEquals(1, $after[1]->getPropertyId());
        self::assertEquals(2, $after[2]->getPropertyId());
        self::assertEquals(1, $after[3]->getPropertyId());
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

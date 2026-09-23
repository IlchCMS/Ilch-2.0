<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Propertytranslations as PropertytranslationsMapper;
use Modules\Shop\Mappers\Properties as PropertiesMapper;
use Modules\Shop\Models\Propertytranslation as PropertyTranslationModel;
use Modules\Shop\Models\Property as PropertyModel;

class PropertytranslationsMapperTest extends DatabaseTestCase
{
    protected PropertytranslationsMapper $out;
    protected PropertiesMapper $propertyMapper;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PropertytranslationsMapper();
        $this->propertyMapper = new PropertiesMapper();
    }

    /**
     * Tests that getTranslations() returns translations filtered by property id.
     */
    public function testGetTranslations(): void
    {
        $propertyId = $this->createProperty();

        $localeA = $this->uniqueLocale('tr');
        $localeB = $this->uniqueLocale('tr');

        $this->out->save($this->createTranslationModel($propertyId, $localeA, 'Text A'));
        $this->out->save($this->createTranslationModel($propertyId, $localeB, 'Text B'));

        $translations = $this->out->getTranslations(['property_id' => $propertyId]);

        self::assertIsArray($translations);
        self::assertCount(2, $translations);

        $translationA = $this->findTranslationByLocale($translations, $localeA);
        self::assertInstanceOf(PropertyTranslationModel::class, $translationA);
        self::assertEquals($propertyId, $translationA->getPropertyId());
        self::assertEquals($localeA, $translationA->getLocale());
        self::assertEquals('Text A', $translationA->getText());

        $translationB = $this->findTranslationByLocale($translations, $localeB);
        self::assertInstanceOf(PropertyTranslationModel::class, $translationB);
        self::assertEquals($propertyId, $translationB->getPropertyId());
        self::assertEquals($localeB, $translationB->getLocale());
        self::assertEquals('Text B', $translationB->getText());
    }

    /**
     * Tests that getTranslations() returns an empty array when no translations match.
     */
    public function testGetTranslationsEmpty(): void
    {
        $propertyId = $this->createProperty();

        $translations = $this->out->getTranslations(['property_id' => $propertyId]);

        self::assertIsArray($translations);
        self::assertCount(0, $translations);
    }

    /**
     * Tests that getTranslationsByPropertyId() returns null when no translations exist.
     */
    public function testGetTranslationsByPropertyIdEmpty(): void
    {
        $propertyId = $this->createProperty();

        $translations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertNull($translations);
    }

    /**
     * Tests that getTranslationsByPropertyId() returns translations for a property.
     */
    public function testGetTranslationsByPropertyId(): void
    {
        $propertyId = $this->createProperty();

        $localeA = $this->uniqueLocale('tr');
        $localeB = $this->uniqueLocale('tr');

        $this->out->save($this->createTranslationModel($propertyId, $localeA, 'Text A'));
        $this->out->save($this->createTranslationModel($propertyId, $localeB, 'Text B'));

        $translations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($translations);
        self::assertCount(2, $translations);

        self::assertNotNull($this->findTranslationByLocale($translations, $localeA));
        self::assertNotNull($this->findTranslationByLocale($translations, $localeB));
    }

    /**
     * Tests that getTranslationsByLocaleAndPropertyIds() filters by locale and property ids.
     */
    public function testGetTranslationsByLocaleAndPropertyIds(): void
    {
        $propertyId1 = $this->createProperty();
        $propertyId2 = $this->createProperty();

        $locale = $this->uniqueLocale('common');
        $otherLocale = $this->uniqueLocale('other');

        $this->out->save($this->createTranslationModel($propertyId1, $locale, 'Text A'));
        $this->out->save($this->createTranslationModel($propertyId2, $locale, 'Text B'));
        $this->out->save($this->createTranslationModel($propertyId1, $otherLocale, 'Other Text'));

        $translations = $this->out->getTranslationsByLocaleAndPropertyIds($locale, [$propertyId1, $propertyId2]);

        self::assertIsArray($translations);
        self::assertCount(2, $translations);

        $translation1 = $this->findTranslationByPropertyId($translations, $propertyId1);
        self::assertInstanceOf(PropertyTranslationModel::class, $translation1);
        self::assertEquals($propertyId1, $translation1->getPropertyId());
        self::assertEquals($locale, $translation1->getLocale());
        self::assertEquals('Text A', $translation1->getText());

        $translation2 = $this->findTranslationByPropertyId($translations, $propertyId2);
        self::assertInstanceOf(PropertyTranslationModel::class, $translation2);
        self::assertEquals($propertyId2, $translation2->getPropertyId());
        self::assertEquals($locale, $translation2->getLocale());
        self::assertEquals('Text B', $translation2->getText());

        $singleTranslations = $this->out->getTranslationsByLocaleAndPropertyIds($locale, [$propertyId1]);

        self::assertIsArray($singleTranslations);
        self::assertCount(1, $singleTranslations);

        $singleTranslation = reset($singleTranslations);
        self::assertInstanceOf(PropertyTranslationModel::class, $singleTranslation);
        self::assertEquals($propertyId1, $singleTranslation->getPropertyId());

        $missingTranslations = $this->out->getTranslationsByLocaleAndPropertyIds(
            $this->uniqueLocale('missing'),
            [$propertyId1]
        );

        self::assertIsArray($missingTranslations);
        self::assertCount(0, $missingTranslations);
    }

    /**
     * Tests inserting a new translation via save().
     */
    public function testSaveInsert(): void
    {
        $propertyId = $this->createProperty();
        $locale = $this->uniqueLocale('new');

        self::assertNull($this->out->getTranslationsByPropertyId($propertyId));

        $translationId = $this->out->save($this->createTranslationModel($propertyId, $locale, 'Inserted Text'));

        self::assertIsInt($translationId);
        self::assertGreaterThan(0, $translationId);

        $translations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($translations);
        self::assertCount(1, $translations);
        self::assertSame([$translationId], array_keys($translations));

        $translation = $translations[$translationId];
        self::assertInstanceOf(PropertyTranslationModel::class, $translation);
        self::assertEquals($translationId, $translation->getId());
        self::assertEquals($propertyId, $translation->getPropertyId());
        self::assertEquals($locale, $translation->getLocale());
        self::assertEquals('Inserted Text', $translation->getText());
    }

    /**
     * Tests updating an existing translation via save().
     */
    public function testSaveUpdate(): void
    {
        $propertyId = $this->createProperty();
        $locale = $this->uniqueLocale('update');

        $originalId = $this->out->save($this->createTranslationModel($propertyId, $locale, 'Original Text'));

        self::assertIsInt($originalId);
        self::assertGreaterThan(0, $originalId);

        $translations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($translations);
        self::assertCount(1, $translations);
        self::assertSame([$originalId], array_keys($translations));

        $translation = $translations[$originalId];
        self::assertInstanceOf(PropertyTranslationModel::class, $translation);

        $translationId = $translation->getId();

        $updatedId = $this->out->save(
            $this->createTranslationModel($propertyId, $locale, 'Updated Text', $translationId)
        );

        self::assertIsInt($updatedId);
        self::assertSame($translationId, $updatedId);

        $updatedTranslations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($updatedTranslations);
        self::assertCount(1, $updatedTranslations);
        self::assertSame([$updatedId], array_keys($updatedTranslations));

        $updatedTranslation = $updatedTranslations[$updatedId];
        self::assertInstanceOf(PropertyTranslationModel::class, $updatedTranslation);
        self::assertEquals($updatedId, $updatedTranslation->getId());
        self::assertEquals($propertyId, $updatedTranslation->getPropertyId());
        self::assertEquals($locale, $updatedTranslation->getLocale());
        self::assertEquals('Updated Text', $updatedTranslation->getText());
    }

    /**
     * Tests that deleteTranslationById() removes a translation.
     */
    public function testDeleteTranslationById(): void
    {
        $propertyId = $this->createProperty();

        $localeA = $this->uniqueLocale('delete-a');
        $localeB = $this->uniqueLocale('delete-b');

        $this->out->save($this->createTranslationModel($propertyId, $localeA, 'Text A'));
        $this->out->save($this->createTranslationModel($propertyId, $localeB, 'Text B'));

        $translations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($translations);
        self::assertCount(2, $translations);

        $translationA = $this->findTranslationByLocale($translations, $localeA);
        self::assertInstanceOf(PropertyTranslationModel::class, $translationA);

        self::assertTrue($this->out->deleteTranslationById($translationA->getId()));

        $remainingTranslations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($remainingTranslations);
        self::assertCount(1, $remainingTranslations);
        self::assertNull($this->findTranslationByLocale($remainingTranslations, $localeA));
        self::assertNotNull($this->findTranslationByLocale($remainingTranslations, $localeB));
    }

    /**
     * Tests that deleteTranslationById() on a non-existent id does not remove other translations.
     */
    public function testDeleteTranslationByIdNotFound(): void
    {
        $propertyId = $this->createProperty();
        $locale = $this->uniqueLocale('keep');

        $this->out->save($this->createTranslationModel($propertyId, $locale, 'Kept Text'));

        self::assertFalse($this->out->deleteTranslationById(9999999));

        $translations = $this->out->getTranslationsByPropertyId($propertyId);

        self::assertIsArray($translations);
        self::assertCount(1, $translations);
        self::assertNotNull($this->findTranslationByLocale($translations, $locale));
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

    private function createTranslationModel(
        int $propertyId,
        string $locale,
        string $text,
        int $id = 0
    ): PropertyTranslationModel {
        $model = new PropertyTranslationModel();
        $model->setId($id);
        $model->setPropertyId($propertyId);
        $model->setLocale($locale);
        $model->setText($text);

        return $model;
    }

    private function findTranslationByLocale(array $translations, string $locale): ?PropertyTranslationModel
    {
        foreach ($translations as $translation) {
            if ($translation->getLocale() === $locale) {
                return $translation;
            }
        }

        return null;
    }

    private function findTranslationByPropertyId(array $translations, int $propertyId): ?PropertyTranslationModel
    {
        foreach ($translations as $translation) {
            if ($translation->getPropertyId() === $propertyId) {
                return $translation;
            }
        }

        return null;
    }

    private function uniqueName(string $prefix): string
    {
        return $prefix . '_' . uniqid();
    }

    private function uniqueLocale(string $prefix): string
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

<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Models\ProfileFieldTranslation as ProfileFieldTranslationModel;

class ProfileFieldsTranslationTest extends DatabaseTestCase
{
    protected ProfileFieldsTranslation $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/database_profile_fields.yml');
        $this->out = new ProfileFieldsTranslation();
    }

    /**
     * Tests that getProfileFieldTranslationByLocale() returns all translations of a locale.
     */
    public function testGetProfileFieldTranslationByLocale()
    {
        $translations = $this->out->getProfileFieldTranslationByLocale('en_EN');

        self::assertIsArray($translations);
        self::assertCount(2, $translations);
        self::assertInstanceOf(ProfileFieldTranslationModel::class, $translations[0]);
    }

    /**
     * Tests that getProfileFieldTranslationByLocale() returns correct names.
     */
    public function testGetProfileFieldTranslationByLocaleFields()
    {
        $translations = $this->out->getProfileFieldTranslationByLocale('en_EN');

        $names = [];
        foreach ($translations as $translation) {
            $names[$translation->getFieldId()] = $translation->getName();
        }

        self::assertEquals('Website', $names[1]);
        self::assertEquals('Discord', $names[2]);
    }

    /**
     * Tests that getProfileFieldTranslationByLocale() returns an empty array for an unknown locale.
     */
    public function testGetProfileFieldTranslationByLocaleNotFound()
    {
        $translations = $this->out->getProfileFieldTranslationByLocale('fr_FR');

        self::assertIsArray($translations);
        self::assertCount(0, $translations);
    }

    /**
     * Tests that getProfileFieldTranslationByFieldId() returns all translations of a field.
     */
    public function testGetProfileFieldTranslationByFieldId()
    {
        $translations = $this->out->getProfileFieldTranslationByFieldId(1);

        self::assertCount(2, $translations);

        $names = [];
        foreach ($translations as $translation) {
            $names[$translation->getLocale()] = $translation->getName();
        }

        self::assertEquals('Webseite', $names['de_DE']);
        self::assertEquals('Website', $names['en_EN']);
    }

    /**
     * Tests that getProfileFieldTranslationByFieldId() returns an empty array for a non-existent field.
     */
    public function testGetProfileFieldTranslationByFieldIdNotFound()
    {
        $translations = $this->out->getProfileFieldTranslationByFieldId(9999);

        self::assertIsArray($translations);
        self::assertCount(0, $translations);
    }

    /**
     * Tests that loadFromArray() creates a model from an array.
     */
    public function testLoadFromArray()
    {
        $model = $this->out->loadFromArray([
            'field_id' => 1,
            'locale' => 'en_EN',
            'name' => 'Website',
        ]);

        self::assertInstanceOf(ProfileFieldTranslationModel::class, $model);
        self::assertEquals(1, $model->getFieldId());
        self::assertEquals('en_EN', $model->getLocale());
        self::assertEquals('Website', $model->getName());
    }

    /**
     * Tests that loadFromArray() returns a model without values for an empty array.
     */
    public function testLoadFromEmptyArray()
    {
        self::assertInstanceOf(ProfileFieldTranslationModel::class, $this->out->loadFromArray());
    }

    /**
     * Tests that save() inserts a new translation.
     */
    public function testSaveInsert()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setFieldId(1)
            ->setLocale('fr_FR')
            ->setName('Site web');

        $this->out->save($model);

        $translations = $this->out->getProfileFieldTranslationByLocale('fr_FR');
        self::assertCount(1, $translations);
        self::assertEquals(1, $translations[0]->getFieldId());
        self::assertEquals('Site web', $translations[0]->getName());

        self::assertCount(3, $this->out->getProfileFieldTranslationByFieldId(1));
    }

    /**
     * Tests that save() updates an existing translation.
     */
    public function testSaveUpdate()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setFieldId(1)
            ->setLocale('en_EN')
            ->setName('Updated Website');

        $this->out->save($model);

        $translations = $this->out->getProfileFieldTranslationByFieldId(1);
        self::assertCount(2, $translations);

        $names = [];
        foreach ($translations as $translation) {
            $names[$translation->getLocale()] = $translation->getName();
        }

        self::assertEquals('Updated Website', $names['en_EN']);
        self::assertEquals('Webseite', $names['de_DE']);
    }

    /**
     * Tests that updating one locale does not affect other locales.
     */
    public function testSaveUpdateDoesNotAffectOtherLocales()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setFieldId(2)
            ->setLocale('en_EN')
            ->setName('Changed Discord');

        $this->out->save($model);

        $translations = $this->out->getProfileFieldTranslationByFieldId(2);

        $names = [];
        foreach ($translations as $translation) {
            $names[$translation->getLocale()] = $translation->getName();
        }

        self::assertEquals('Changed Discord', $names['en_EN']);
        self::assertEquals('Discord', $names['de_DE']);
    }

    /**
     * Tests that deleteProfileFieldTranslationsByFieldId() removes all translations of a field.
     */
    public function testDeleteProfileFieldTranslationsByFieldId()
    {
        $this->out->deleteProfileFieldTranslationsByFieldId(2);

        self::assertCount(0, $this->out->getProfileFieldTranslationByFieldId(2));
        self::assertCount(2, $this->out->getProfileFieldTranslationByFieldId(1));
    }

    /**
     * Tests that deleteProfileFieldTranslation() removes a single translation.
     */
    public function testDeleteProfileFieldTranslation()
    {
        $this->out->deleteProfileFieldTranslation('en_EN', 1);

        $translations = $this->out->getProfileFieldTranslationByFieldId(1);
        self::assertCount(1, $translations);
        self::assertEquals('de_DE', $translations[0]->getLocale());

        $byLocale = $this->out->getProfileFieldTranslationByLocale('en_EN');
        self::assertCount(1, $byLocale);
        self::assertEquals(2, $byLocale[0]->getFieldId());
    }

    /**
     * Tests that deleteProfileFieldTranslation() on a non-existent entry does not throw.
     */
    public function testDeleteProfileFieldTranslationNotFound()
    {
        $this->out->deleteProfileFieldTranslation('fr_FR', 1);

        self::assertCount(2, $this->out->getProfileFieldTranslationByFieldId(1));
    }

    /**
     * Returns database schema sql statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}

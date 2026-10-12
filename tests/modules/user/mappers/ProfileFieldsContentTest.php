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
use Modules\User\Models\ProfileFieldContent as ProfileFieldContentModel;

class ProfileFieldsContentTest extends DatabaseTestCase
{
    protected ProfileFieldsContent $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/database_profile_fields.yml');
        $this->out = new ProfileFieldsContent();
    }

    /**
     * Tests that getProfileFieldContentByUserId() returns the content of a user.
     */
    public function testGetProfileFieldContentByUserId()
    {
        $content = $this->out->getProfileFieldContentByUserId(1);

        self::assertIsArray($content);
        self::assertCount(2, $content);
        self::assertInstanceOf(ProfileFieldContentModel::class, $content[0]);
    }

    /**
     * Tests that getProfileFieldContentByUserId() returns correct values.
     */
    public function testGetProfileFieldContentByUserIdFields()
    {
        $content = $this->out->getProfileFieldContentByUserId(1);

        $values = [];
        foreach ($content as $profileFieldContent) {
            $values[$profileFieldContent->getFieldId()] = $profileFieldContent->getValue();
        }

        self::assertEquals('https://example.com', $values[1]);
        self::assertEquals('https://discord.gg/example', $values[2]);
    }

    /**
     * Tests that getProfileFieldContentByUserId() returns the content of the second user.
     */
    public function testGetProfileFieldContentByUserIdSecond()
    {
        $content = $this->out->getProfileFieldContentByUserId(2);

        self::assertCount(1, $content);
        self::assertEquals(2, $content[0]->getUserId());
        self::assertEquals(1, $content[0]->getFieldId());
        self::assertEquals('https://example.org', $content[0]->getValue());
    }

    /**
     * Tests that getProfileFieldContentByUserId() returns an empty array for a user without content.
     */
    public function testGetProfileFieldContentByUserIdEmpty()
    {
        $content = $this->out->getProfileFieldContentByUserId(9999);

        self::assertIsArray($content);
        self::assertCount(0, $content);
    }

    /**
     * Tests that loadFromArray() creates a model from an array.
     */
    public function testLoadFromArray()
    {
        $model = $this->out->loadFromArray([
            'user_id' => 1,
            'field_id' => 1,
            'value' => 'https://example.com',
        ]);

        self::assertInstanceOf(ProfileFieldContentModel::class, $model);
        self::assertEquals(1, $model->getUserId());
        self::assertEquals(1, $model->getFieldId());
        self::assertEquals('https://example.com', $model->getValue());
    }

    /**
     * Tests that loadFromArray() returns a model without values for an empty array.
     */
    public function testLoadFromEmptyArray()
    {
        self::assertInstanceOf(ProfileFieldContentModel::class, $this->out->loadFromArray());
    }

    /**
     * Tests that save() inserts new profile content.
     */
    public function testSaveInsert()
    {
        $model = new ProfileFieldContentModel();
        $model->setUserId(2)
            ->setFieldId(2)
            ->setValue('https://discord.gg/new');

        $this->out->save($model);

        $content = $this->out->getProfileFieldContentByUserId(2);
        self::assertCount(2, $content);

        $values = [];
        foreach ($content as $profileFieldContent) {
            $values[$profileFieldContent->getFieldId()] = $profileFieldContent->getValue();
        }

        self::assertEquals('https://example.org', $values[1]);
        self::assertEquals('https://discord.gg/new', $values[2]);
    }

    /**
     * Tests that save() updates existing profile content.
     */
    public function testSaveUpdate()
    {
        $model = new ProfileFieldContentModel();
        $model->setUserId(1)
            ->setFieldId(1)
            ->setValue('https://updated.example.com');

        $this->out->save($model);

        $content = $this->out->getProfileFieldContentByUserId(1);
        self::assertCount(2, $content);

        $values = [];
        foreach ($content as $profileFieldContent) {
            $values[$profileFieldContent->getFieldId()] = $profileFieldContent->getValue();
        }

        self::assertEquals('https://updated.example.com', $values[1]);
        self::assertEquals('https://discord.gg/example', $values[2]);
    }

    /**
     * Tests that updating one user's content does not affect other users.
     */
    public function testSaveUpdateDoesNotAffectOtherUsers()
    {
        $model = new ProfileFieldContentModel();
        $model->setUserId(1)
            ->setFieldId(1)
            ->setValue('https://updated.example.com');

        $this->out->save($model);

        $content = $this->out->getProfileFieldContentByUserId(2);
        self::assertCount(1, $content);
        self::assertEquals('https://example.org', $content[0]->getValue());
    }

    /**
     * Tests that deleteProfileFieldContentByUserId() removes all content of a user.
     */
    public function testDeleteProfileFieldContentByUserId()
    {
        $this->out->deleteProfileFieldContentByUserId(2);

        self::assertCount(0, $this->out->getProfileFieldContentByUserId(2));
        self::assertCount(2, $this->out->getProfileFieldContentByUserId(1));
    }

    /**
     * Tests that deleteProfileFieldContentByFieldId() removes all content of a field.
     */
    public function testDeleteProfileFieldContentByFieldId()
    {
        $this->out->deleteProfileFieldContentByFieldId(1);

        $contentUserOne = $this->out->getProfileFieldContentByUserId(1);
        self::assertCount(1, $contentUserOne);
        self::assertEquals(2, $contentUserOne[0]->getFieldId());

        self::assertCount(0, $this->out->getProfileFieldContentByUserId(2));
    }

    /**
     * Tests that deleteProfileFieldContentByUserAndFieldId() removes a single entry and returns affected rows.
     */
    public function testDeleteProfileFieldContentByUserAndFieldId()
    {
        $affected = $this->out->deleteProfileFieldContentByUserAndFieldId(1, 2);

        self::assertEquals(1, $affected);

        $content = $this->out->getProfileFieldContentByUserId(1);
        self::assertCount(1, $content);
        self::assertEquals(1, $content[0]->getFieldId());
    }

    /**
     * Tests that deleteProfileFieldContentByUserAndFieldId() returns 0 for a non-existent entry.
     */
    public function testDeleteProfileFieldContentByUserAndFieldIdNotFound()
    {
        $affected = $this->out->deleteProfileFieldContentByUserAndFieldId(2, 2);

        self::assertEquals(0, $affected);
        self::assertCount(1, $this->out->getProfileFieldContentByUserId(2));
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

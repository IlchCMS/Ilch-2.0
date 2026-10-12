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
use Modules\User\Models\ProfileField as ProfileFieldModel;

class ProfileFieldsTest extends DatabaseTestCase
{
    protected ProfileFields $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/database_profile_fields.yml');
        $this->out = new ProfileFields();
    }

    /**
     * Tests that getProfileFields() returns all profile fields.
     */
    public function testGetProfileFields()
    {
        $profileFields = $this->out->getProfileFields();

        self::assertIsArray($profileFields);
        self::assertCount(2, $profileFields);
        self::assertInstanceOf(ProfileFieldModel::class, $profileFields[0]);
    }

    /**
     * Tests that getProfileFields() returns fields ordered by position ascending.
     */
    public function testGetProfileFieldsOrderedByPosition()
    {
        $profileFields = $this->out->getProfileFields();

        self::assertEquals('website', $profileFields[0]->getKey());
        self::assertEquals('discord', $profileFields[1]->getKey());
    }

    /**
     * Tests that getProfileFields() returns correct fields for the first field.
     */
    public function testGetProfileFieldsFields()
    {
        $profileFields = $this->out->getProfileFields();

        self::assertEquals(1, $profileFields[0]->getId());
        self::assertEquals('website', $profileFields[0]->getKey());
        self::assertEquals(2, $profileFields[0]->getType());
        self::assertEquals('fa-solid fa-globe', $profileFields[0]->getIcon());
        self::assertEquals('', $profileFields[0]->getAddition());
        self::assertEquals('', $profileFields[0]->getOptions());
        self::assertEquals(1, $profileFields[0]->getShow());
        self::assertEquals(0, $profileFields[0]->getHidden());
        self::assertEquals(0, $profileFields[0]->getRegistration());
        self::assertEquals(0, $profileFields[0]->getPosition());
    }

    /**
     * Tests that getProfileFields() supports a where condition.
     */
    public function testGetProfileFieldsWithWhere()
    {
        $visible = $this->out->getProfileFields(['show' => 1]);

        self::assertCount(1, $visible);
        self::assertEquals('website', $visible[0]->getKey());

        $hidden = $this->out->getProfileFields(['hidden' => 1]);

        self::assertIsArray($hidden);
        self::assertCount(0, $hidden);
    }

    /**
     * Tests that getProfileFieldById() returns the correct field.
     */
    public function testGetProfileFieldById()
    {
        $profileField = $this->out->getProfileFieldById(1);

        self::assertNotNull($profileField);
        self::assertInstanceOf(ProfileFieldModel::class, $profileField);
        self::assertEquals(1, $profileField->getId());
        self::assertEquals('website', $profileField->getKey());
        self::assertEquals('fa-solid fa-globe', $profileField->getIcon());
        self::assertEquals(0, $profileField->getPosition());
    }

    /**
     * Tests that getProfileFieldById() returns null for a non-existent id.
     */
    public function testGetProfileFieldByIdNotFound()
    {
        self::assertNull($this->out->getProfileFieldById(9999));
    }

    /**
     * Tests that getProfileFieldIdByKey() returns the correct field.
     */
    public function testGetProfileFieldIdByKey()
    {
        $profileField = $this->out->getProfileFieldIdByKey('discord');

        self::assertNotNull($profileField);
        self::assertEquals(2, $profileField->getId());
        self::assertEquals('discord', $profileField->getKey());
        self::assertEquals('https://discord.gg/', $profileField->getAddition());
    }

    /**
     * Tests that getProfileFieldIdByKey() returns null for a non-existent key.
     */
    public function testGetProfileFieldIdByKeyNotFound()
    {
        self::assertNull($this->out->getProfileFieldIdByKey('nonexistent'));
    }

    /**
     * Tests that updatePositionById() updates the position.
     */
    public function testUpdatePositionById()
    {
        $this->out->updatePositionById(2, 5);

        self::assertEquals(5, $this->out->getProfileFieldById(2)->getPosition());
    }

    /**
     * Tests that updatePositionById() does not affect other fields.
     */
    public function testUpdatePositionByIdDoesNotAffectOthers()
    {
        $this->out->updatePositionById(1, 3);

        self::assertEquals(1, $this->out->getProfileFieldById(2)->getPosition());
    }

    /**
     * Tests that save() inserts a new profile field when the id does not exist.
     */
    public function testSaveInsert()
    {
        $model = new ProfileFieldModel();
        $model->setId(0)
            ->setKey('steam')
            ->setType(2)
            ->setIcon('fa-brands fa-steam-square')
            ->setAddition('https://steamcommunity.com/id/')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(0)
            ->setRegistration(0)
            ->setPosition(2);

        $id = $this->out->save($model);

        self::assertGreaterThan(2, $id);
        self::assertCount(3, $this->out->getProfileFields());

        $new = $this->out->getProfileFieldById($id);
        self::assertNotNull($new);
        self::assertEquals('steam', $new->getKey());
        self::assertEquals('fa-brands fa-steam-square', $new->getIcon());
        self::assertEquals(2, $new->getPosition());
    }

    /**
     * Tests that save() updates an existing profile field.
     */
    public function testSaveUpdate()
    {
        $model = new ProfileFieldModel();
        $model->setId(1)
            ->setKey('website')
            ->setType(2)
            ->setIcon('fa-solid fa-house')
            ->setAddition('https://new.example.com/')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(0)
            ->setRegistration(1)
            ->setPosition(0);

        $id = $this->out->save($model);

        self::assertEquals(1, $id);

        $updated = $this->out->getProfileFieldById(1);
        self::assertNotNull($updated);
        self::assertEquals('fa-solid fa-house', $updated->getIcon());
        self::assertEquals('https://new.example.com/', $updated->getAddition());
        self::assertEquals(1, $updated->getRegistration());
    }

    /**
     * Tests that save() does not affect other profile fields.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new ProfileFieldModel();
        $model->setId(1)
            ->setKey('website')
            ->setType(2)
            ->setIcon('fa-solid fa-house')
            ->setAddition('')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(0)
            ->setRegistration(0)
            ->setPosition(0);

        $this->out->save($model);

        $other = $this->out->getProfileFieldById(2);
        self::assertNotNull($other);
        self::assertEquals('discord', $other->getKey());
        self::assertEquals('fa-brands fa-discord', $other->getIcon());
    }

    /**
     * Tests that save() does not write the hidden flag on insert:
     * the database default (0) applies.
     */
    public function testSaveDoesNotPersistHiddenOnInsert()
    {
        $model = new ProfileFieldModel();
        $model->setId(0)
            ->setKey('twitch')
            ->setType(2)
            ->setIcon('fa-brands fa-twitch')
            ->setAddition('https://www.twitch.tv/')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(1)
            ->setRegistration(0)
            ->setPosition(3);

        $id = $this->out->save($model);

        $saved = $this->out->getProfileFieldById($id);
        self::assertNotNull($saved);
        self::assertEquals(0, $saved->getHidden());
    }

    /**
     * Tests that save() preserves an existing hidden flag when updating a field,
     * even if the model carries a different value.
     */
    public function testSavePreservesHiddenOnUpdate()
    {
        // Simulate a field that got hidden outside of save()
        // (e.g. by a module install or migration).
        $this->db->update('profile_fields')
            ->values(['hidden' => 1])
            ->where(['id' => 1])
            ->execute();

        $model = new ProfileFieldModel();
        $model->setId(1)
            ->setKey('website')
            ->setType(2)
            ->setIcon('fa-solid fa-house')
            ->setAddition('')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(0)
            ->setRegistration(0)
            ->setPosition(0);

        $this->out->save($model);

        self::assertEquals(1, $this->out->getProfileFieldById(1)->getHidden());
    }

    /**
     * Tests that update() toggles the show flag.
     */
    public function testUpdateTogglesShow()
    {
        $this->out->update(1);

        self::assertEquals(0, $this->out->getProfileFieldById(1)->getShow());

        $this->out->update(1);

        self::assertEquals(1, $this->out->getProfileFieldById(1)->getShow());
    }

    /**
     * Tests that update() on a non-existent id does not throw.
     */
    public function testUpdateNotFound()
    {
        $this->out->update(9999);

        self::assertCount(2, $this->out->getProfileFields());
    }

    /**
     * Tests that deleteProfileField() removes a profile field.
     */
    public function testDeleteProfileField()
    {
        self::assertTrue($this->out->deleteProfileField(1));

        self::assertNull($this->out->getProfileFieldById(1));

        $profileFields = $this->out->getProfileFields();
        self::assertCount(1, $profileFields);
        self::assertEquals(2, $profileFields[0]->getId());
    }

    /**
     * Tests that deleteProfileField() on a non-existent id does not throw.
     */
    public function testDeleteProfileFieldNotFound()
    {
        $this->out->deleteProfileField(9999);

        self::assertCount(2, $this->out->getProfileFields());
    }

    /**
     * Tests that getProfileFields() returns an empty array when no fields exist.
     */
    public function testGetProfileFieldsEmpty()
    {
        $this->out->deleteProfileField(1);
        $this->out->deleteProfileField(2);

        $profileFields = $this->out->getProfileFields();

        self::assertIsArray($profileFields);
        self::assertCount(0, $profileFields);
    }

    /**
     * Tests that profileFieldWithIdExists() returns the correct result.
     */
    public function testProfileFieldWithIdExists()
    {
        self::assertTrue($this->out->profileFieldWithIdExists(1));
        self::assertFalse($this->out->profileFieldWithIdExists(9999));
    }

    /**
     * Tests that getCountOfProfileFields() returns the count of profile fields.
     */
    public function testGetCountOfProfileFields()
    {
        self::assertEquals(2, $this->out->getCountOfProfileFields());

        $model = new ProfileFieldModel();
        $model->setId(0)
            ->setKey('twitch')
            ->setType(2)
            ->setIcon('fa-brands fa-twitch')
            ->setAddition('')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(0)
            ->setRegistration(0)
            ->setPosition(3);

        $this->out->save($model);

        self::assertEquals(3, $this->out->getCountOfProfileFields());
    }

    /**
     * Tests that loadFromArray() creates a model from an array.
     */
    public function testLoadFromArray()
    {
        $model = $this->out->loadFromArray([
            'id' => 1,
            'key' => 'website',
            'type' => 2,
            'icon' => 'fa-solid fa-globe',
            'addition' => '',
            'options' => '',
            'show' => 1,
            'hidden' => 0,
            'registration' => 0,
            'position' => 0,
        ]);

        self::assertInstanceOf(ProfileFieldModel::class, $model);
        self::assertEquals(1, $model->getId());
        self::assertEquals('website', $model->getKey());
        self::assertEquals('fa-solid fa-globe', $model->getIcon());
        self::assertEquals(0, $model->getPosition());
    }

    /**
     * Tests that loadFromArray() returns a model without values for an empty array.
     */
    public function testLoadFromEmptyArray()
    {
        self::assertInstanceOf(ProfileFieldModel::class, $this->out->loadFromArray());
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

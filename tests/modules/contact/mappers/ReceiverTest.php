<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Contact\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Contact\Config\Config as ModuleConfig;
use Modules\Contact\Mappers\Receiver as ReceiverMapper;
use Modules\Contact\Models\Receiver as ReceiverModel;

class ReceiverTest extends DatabaseTestCase
{
    /**
     * @var ReceiverMapper
     */
    protected Receiver $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ReceiverMapper();
    }

    /**
     * Tests that getReceivers() returns all receivers.
     */
    public function testGetReceivers()
    {
        $receivers = $this->out->getReceivers();

        self::assertNotNull($receivers);
        self::assertCount(2, $receivers);
        self::assertInstanceOf(ReceiverModel::class, $receivers[0]);
    }

    /**
     * Tests that getReceivers() returns correct fields for the first receiver.
     */
    public function testGetReceiversFields()
    {
        $receivers = $this->out->getReceivers();

        self::assertEquals(1, $receivers[0]->getId());
        self::assertEquals('webmaster@example.com', $receivers[0]->getEmail());
        self::assertEquals('Webmaster', $receivers[0]->getName());
    }

    /**
     * Tests that getReceivers() returns correct fields for the second receiver.
     */
    public function testGetReceiversSecond()
    {
        $receivers = $this->out->getReceivers();

        self::assertEquals(2, $receivers[1]->getId());
        self::assertEquals('support@example.com', $receivers[1]->getEmail());
        self::assertEquals('Support Team', $receivers[1]->getName());
    }

    /**
     * Tests that getReceivers() returns null when no receivers exist.
     */
    public function testGetReceiversEmpty()
    {
        // Delete all receivers
        $this->out->delete(1);
        $this->out->delete(2);

        $receivers = $this->out->getReceivers();

        self::assertNull($receivers);
    }

    /**
     * Tests that getReceiverById() returns the correct receiver.
     */
    public function testGetReceiverById()
    {
        $receiver = $this->out->getReceiverById(1);

        self::assertNotNull($receiver);
        self::assertEquals(1, $receiver->getId());
        self::assertEquals('webmaster@example.com', $receiver->getEmail());
        self::assertEquals('Webmaster', $receiver->getName());
    }

    /**
     * Tests that getReceiverById() returns null for a non-existent id.
     */
    public function testGetReceiverByIdNotFound()
    {
        $receiver = $this->out->getReceiverById(9999);

        self::assertNull($receiver);
    }

    /**
     * Tests inserting a new receiver via save().
     */
    public function testSaveInsert()
    {
        $model = new ReceiverModel();
        $model->setId(0)
            ->setName('Marketing')
            ->setEmail('marketing@example.com');

        $this->out->save($model);

        $receivers = $this->out->getReceivers();
        self::assertCount(3, $receivers);

        // The new receiver should be the last one (auto-increment)
        $new = $receivers[2];
        self::assertGreaterThan(2, $new->getId());
        self::assertEquals('Marketing', $new->getName());
        self::assertEquals('marketing@example.com', $new->getEmail());
    }

    /**
     * Tests updating an existing receiver via save().
     */
    public function testSaveUpdate()
    {
        $model = new ReceiverModel();
        $model->setId(1)
            ->setName('Updated Webmaster')
            ->setEmail('updated@example.com');

        $this->out->save($model);

        $receiver = $this->out->getReceiverById(1);
        self::assertNotNull($receiver);
        self::assertEquals(1, $receiver->getId());
        self::assertEquals('Updated Webmaster', $receiver->getName());
        self::assertEquals('updated@example.com', $receiver->getEmail());
    }

    /**
     * Tests that update does not affect other receivers.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new ReceiverModel();
        $model->setId(1)
            ->setName('Changed Name')
            ->setEmail('changed@example.com');

        $this->out->save($model);

        $other = $this->out->getReceiverById(2);
        self::assertNotNull($other);
        self::assertEquals('Support Team', $other->getName());
        self::assertEquals('support@example.com', $other->getEmail());
    }

    /**
     * Tests that delete() removes a receiver.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getReceiverById(1));

        // Remaining receiver should still be present
        $receivers = $this->out->getReceivers();
        self::assertCount(1, $receivers);
        self::assertEquals(2, $receivers[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        // Existing receivers should be unaffected
        $receivers = $this->out->getReceivers();
        self::assertCount(2, $receivers);
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getReceivers();
        self::assertCount(2, $before);

        $model = new ReceiverModel();
        $model->setId(0)
            ->setName('New Entry')
            ->setEmail('new@example.com');

        $this->out->save($model);

        $after = $this->out->getReceivers();
        self::assertCount(3, $after);

        // Original receivers untouched
        self::assertEquals('Webmaster', $after[0]->getName());
        self::assertEquals('Support Team', $after[1]->getName());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}

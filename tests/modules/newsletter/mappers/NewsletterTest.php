<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Newsletter\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Newsletter\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class NewsletterTest extends DatabaseTestCase
{
    /**
     * @var NewsletterMapper
     */
    protected Newsletter $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new NewsletterMapper();
    }

    /**
     * Tests that getEntries() returns all newsletters.
     */
    public function testGetEntries()
    {
        $entries = $this->out->getEntries();

        self::assertIsArray($entries);
        self::assertCount(2, $entries);
        self::assertInstanceOf(NewsletterModel::class, $entries[0]);
    }

    /**
     * Tests that getEntries() returns correct fields for the newest entry (id 2).
     */
    public function testGetEntriesFields()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals(2, $entries[0]->getUserId());
        self::assertEquals('2024-02-20 14:30:00', $entries[0]->getDateCreated());
        self::assertEquals('Monthly Update', $entries[0]->getSubject());
        self::assertEquals('Here are the latest updates.', $entries[0]->getText());
    }

    /**
     * Tests that getEntries() returns correct fields for the older entry (id 1).
     */
    public function testGetEntriesSecond()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(1, $entries[1]->getId());
        self::assertEquals(1, $entries[1]->getUserId());
        self::assertEquals('2024-01-15 10:00:00', $entries[1]->getDateCreated());
        self::assertEquals('Welcome', $entries[1]->getSubject());
        self::assertEquals('Welcome to our newsletter!', $entries[1]->getText());
    }

    /**
     * Tests that getEntries() returns null when no entries exist.
     */
    public function testGetEntriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        $entries = $this->out->getEntries();

        self::assertNull($entries);
    }

    /**
     * Tests that getNewsletterById() returns the correct newsletter.
     */
    public function testGetNewsletterById()
    {
        $newsletter = $this->out->getNewsletterById(1);

        self::assertNotNull($newsletter);
        self::assertEquals(1, $newsletter->getId());
        self::assertEquals(1, $newsletter->getUserId());
        self::assertEquals('2024-01-15 10:00:00', $newsletter->getDateCreated());
        self::assertEquals('Welcome', $newsletter->getSubject());
        self::assertEquals('Welcome to our newsletter!', $newsletter->getText());
    }

    /**
     * Tests that getNewsletterById() returns null for a non-existent id.
     */
    public function testGetNewsletterByIdNotFound()
    {
        $newsletter = $this->out->getNewsletterById(9999);

        self::assertNull($newsletter);
    }

    /**
     * Tests that getLastId() returns the highest id.
     */
    public function testGetLastId()
    {
        $lastId = $this->out->getLastId();

        self::assertEquals(2, $lastId);
    }

    /**
     * Tests inserting a new newsletter via save().
     */
    public function testSave()
    {
        $model = new NewsletterModel();
        $model->setUserId(3)
            ->setDateCreated('2024-03-01 08:00:00')
            ->setSubject('Q1 Special')
            ->setText('Quarterly special edition.');

        $this->out->save($model);

        $entries = $this->out->getEntries();
        self::assertCount(3, $entries);

        // New entry should be first (newest date_created)
        $new = $entries[0];
        self::assertGreaterThan(2, $new->getId());
        self::assertEquals(3, $new->getUserId());
        self::assertEquals('2024-03-01 08:00:00', $new->getDateCreated());
        self::assertEquals('Q1 Special', $new->getSubject());
        self::assertEquals('Quarterly special edition.', $new->getText());
    }

    /**
     * Tests that getLastId() reflects a new insert.
     */
    public function testGetLastIdAfterInsert()
    {
        $model = new NewsletterModel();
        $model->setUserId(4)
            ->setDateCreated('2024-04-01 00:00:00')
            ->setSubject('New')
            ->setText('New entry text.');

        $this->out->save($model);

        self::assertGreaterThan(2, $this->out->getLastId());
    }

    /**
     * Tests that save() does not modify existing entries.
     */
    public function testSaveDoesNotModifyExisting()
    {
        $model = new NewsletterModel();
        $model->setUserId(9)
            ->setDateCreated('2024-05-01 00:00:00')
            ->setSubject('Additional')
            ->setText('Another entry.');

        $this->out->save($model);

        $original = $this->out->getNewsletterById(1);
        self::assertNotNull($original);
        self::assertEquals('Welcome', $original->getSubject());
        self::assertEquals('Welcome to our newsletter!', $original->getText());
    }

    /**
     * Tests that delete() removes a newsletter.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getNewsletterById(1));

        $entries = $this->out->getEntries();
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $entries = $this->out->getEntries();
        self::assertCount(2, $entries);
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

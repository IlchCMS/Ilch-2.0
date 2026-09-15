<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Kvticket\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Kvticket\Config\Config as ModuleConfig;
use Modules\Kvticket\Mappers\Ticket as TicketMapper;
use Modules\Kvticket\Models\Ticket as TicketModel;

class TicketTest extends DatabaseTestCase
{
    /**
     * @var TicketMapper
     */
    protected Ticket $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new TicketMapper();
    }

    /**
     * Tests that getTickets() returns all tickets.
     */
    public function testGetTickets()
    {
        $tickets = $this->out->getTickets();

        self::assertIsArray($tickets);
        self::assertCount(2, $tickets);
        self::assertInstanceOf(TicketModel::class, $tickets[0]);
    }

    /**
     * Tests that getTickets() returns correct fields for the first ticket.
     */
    public function testGetTicketsFields()
    {
        $tickets = $this->out->getTickets();

        self::assertEquals(2, $tickets[0]->getId());
        self::assertEquals('Add dark mode', $tickets[0]->getTitle());
        self::assertEquals('Please add a dark mode option', $tickets[0]->getText());
        self::assertEquals(1, $tickets[0]->getStatus());
        self::assertEquals(2, $tickets[0]->getEditor());
        self::assertEquals(3, $tickets[0]->getCreator());
        self::assertEquals(2, $tickets[0]->getCat());
        self::assertEquals('2024-01-02 12:00:00', $tickets[0]->getCreatedAt());
        self::assertEquals('2024-01-03 14:00:00', $tickets[0]->getUpdatedAt());
    }

    /**
     * Tests that getTickets() returns correct fields for the second ticket.
     */
    public function testGetTicketsSecond()
    {
        $tickets = $this->out->getTickets();

        self::assertEquals(1, $tickets[1]->getId());
        self::assertEquals('Login issue', $tickets[1]->getTitle());
        self::assertEquals('Cannot login to the site', $tickets[1]->getText());
        self::assertEquals(0, $tickets[1]->getStatus());
        self::assertEquals(0, $tickets[1]->getEditor());
        self::assertEquals(1, $tickets[1]->getCreator());
        self::assertEquals(1, $tickets[1]->getCat());
        self::assertEquals('2024-01-01 10:00:00', $tickets[1]->getCreatedAt());
        self::assertEquals('2024-01-01 10:00:00', $tickets[1]->getUpdatedAt());
    }

    /**
     * Tests that getTickets() returns empty array when no tickets exist.
     */
    public function testGetTicketsEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        $tickets = $this->out->getTickets();

        self::assertIsArray($tickets);
        self::assertCount(0, $tickets);
    }

    /**
     * Tests that getTicketById() returns the correct ticket.
     */
    public function testGetTicketById()
    {
        $ticket = $this->out->getTicketById(1);

        self::assertNotNull($ticket);
        self::assertEquals(1, $ticket->getId());
        self::assertEquals('Login issue', $ticket->getTitle());
        self::assertEquals('Cannot login to the site', $ticket->getText());
        self::assertEquals(0, $ticket->getStatus());
        self::assertEquals(0, $ticket->getEditor());
        self::assertEquals(1, $ticket->getCreator());
        self::assertEquals(1, $ticket->getCat());
    }

    /**
     * Tests that getTicketById() returns null for a non-existent id.
     */
    public function testGetTicketByIdNotFound()
    {
        $ticket = $this->out->getTicketById(9999);

        self::assertNull($ticket);
    }

    /**
     * Tests inserting a new ticket via save().
     */
    public function testSaveInsert()
    {
        $model = new TicketModel();
        $model->setId(0)
            ->setTitle('Crash on startup')
            ->setText('App crashes immediately')
            ->setStatus(0)
            ->setEditor(0)
            ->setCreator(4)
            ->setCat(1);

        $this->out->save($model);

        $tickets = $this->out->getTickets();
        self::assertCount(3, $tickets);

        $new = $tickets[0];
        self::assertGreaterThan(2, $new->getId());
        self::assertEquals('Crash on startup', $new->getTitle());
        self::assertEquals('App crashes immediately', $new->getText());
        self::assertEquals(4, $new->getCreator());
        self::assertEquals(1, $new->getCat());
    }

    /**
     * Tests updating an existing ticket via save().
     */
    public function testSaveUpdate()
    {
        $model = new TicketModel();
        $model->setId(1)
            ->setTitle('Updated Login Issue')
            ->setText('Still cannot login')
            ->setStatus(1)
            ->setEditor(5)
            ->setCreator(1)
            ->setCat(1);

        $this->out->save($model);

        $ticket = $this->out->getTicketById(1);
        self::assertNotNull($ticket);
        self::assertEquals(1, $ticket->getId());
        self::assertEquals('Updated Login Issue', $ticket->getTitle());
        self::assertEquals('Still cannot login', $ticket->getText());
        self::assertEquals(1, $ticket->getStatus());
        self::assertEquals(5, $ticket->getEditor());
    }

    /**
     * Tests that update does not affect other tickets.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new TicketModel();
        $model->setId(1)
            ->setTitle('Changed')
            ->setText('changed text')
            ->setStatus(1)
            ->setEditor(0)
            ->setCreator(1)
            ->setCat(1);

        $this->out->save($model);

        $other = $this->out->getTicketById(2);
        self::assertNotNull($other);
        self::assertEquals('Add dark mode', $other->getTitle());
        self::assertEquals('Please add a dark mode option', $other->getText());
        self::assertEquals(1, $other->getStatus());
        self::assertEquals(2, $other->getEditor());
    }

    /**
     * Tests that delete() removes a ticket.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getTicketById(1));

        $tickets = $this->out->getTickets();
        self::assertCount(1, $tickets);
        self::assertEquals(2, $tickets[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $tickets = $this->out->getTickets();
        self::assertCount(2, $tickets);
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getTickets();
        self::assertCount(2, $before);

        $model = new TicketModel();
        $model->setId(0)
            ->setTitle('New Entry')
            ->setText('New ticket text')
            ->setStatus(0)
            ->setEditor(0)
            ->setCreator(1)
            ->setCat(1);

        $this->out->save($model);

        $after = $this->out->getTickets();
        self::assertCount(3, $after);

        self::assertEquals('New Entry', $after[0]->getTitle());
        self::assertEquals('Add dark mode', $after[1]->getTitle());
        self::assertEquals('Login issue', $after[2]->getTitle());
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

<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Newsletter\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Newsletter\Config\Config as ModuleConfig;
use Modules\Newsletter\Mappers\Subscriber as SubscriberMapper;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;

class SubscriberTest extends DatabaseTestCase
{
    /**
     * @var SubscriberMapper
     */
    protected Subscriber $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new SubscriberMapper();
    }

    /**
     * Tests that getSubscribers() returns all subscribers.
     */
    public function testGetSubscribers()
    {
        $subscribers = $this->out->getSubscribers();

        self::assertIsArray($subscribers);
        self::assertCount(2, $subscribers);
        self::assertInstanceOf(SubscriberModel::class, $subscribers[0]);
    }

    /**
     * Tests that getSubscribers() returns correct fields for the first subscriber.
     */
    public function testGetSubscribersFields()
    {
        $subscribers = $this->out->getSubscribers();

        self::assertEquals(1, $subscribers[0]->getId());
        self::assertEquals('john@example.com', $subscribers[0]->getEmail());
        self::assertEquals('abc123def456ghi7', $subscribers[0]->getSelector());
        self::assertEquals('hash111111111111111111111111111111111111111111111111', $subscribers[0]->getConfirmCode());
        self::assertEquals('2024-01-10 08:00:00', $subscribers[0]->getDoubleOptInDate());
        self::assertTrue($subscribers[0]->getDoubleOptInConfirmed());
    }

    /**
     * Tests that getSubscribers() returns correct fields for the second subscriber.
     */
    public function testGetSubscribersSecond()
    {
        $subscribers = $this->out->getSubscribers();

        self::assertEquals(2, $subscribers[1]->getId());
        self::assertEquals('jane@example.com', $subscribers[1]->getEmail());
        self::assertEquals('xyz789uvw456rst3', $subscribers[1]->getSelector());
        self::assertEquals('hash222222222222222222222222222222222222222222222222', $subscribers[1]->getConfirmCode());
        self::assertEquals('2024-02-05 12:00:00', $subscribers[1]->getDoubleOptInDate());
        self::assertFalse($subscribers[1]->getDoubleOptInConfirmed());
    }

    /**
     * Tests that getSubscribers() returns null when no subscribers exist.
     */
    public function testGetSubscribersEmpty()
    {
        $this->out->deleteSubscriberByEmail('john@example.com');
        $this->out->deleteSubscriberByEmail('jane@example.com');

        $subscribers = $this->out->getSubscribers();

        self::assertNull($subscribers);
    }

    /**
     * Tests that getSubscriberByEMail() returns the correct subscriber.
     */
    public function testGetSubscriberByEMail()
    {
        $subscriber = $this->out->getSubscriberByEMail('john@example.com');

        self::assertNotNull($subscriber);
        self::assertEquals(1, $subscriber->getId());
        self::assertEquals('john@example.com', $subscriber->getEmail());
        self::assertEquals('abc123def456ghi7', $subscriber->getSelector());
        self::assertTrue($subscriber->getDoubleOptInConfirmed());
    }

    /**
     * Tests that getSubscriberByEMail() returns null for a non-existent email.
     */
    public function testGetSubscriberByEMailNotFound()
    {
        $subscriber = $this->out->getSubscriberByEMail('nobody@example.com');

        self::assertNull($subscriber);
    }

    /**
     * Tests that getSubscriberBySelector() returns the correct subscriber.
     */
    public function testGetSubscriberBySelector()
    {
        $subscriber = $this->out->getSubscriberBySelector('xyz789uvw456rst3');

        self::assertNotNull($subscriber);
        self::assertEquals(2, $subscriber->getId());
        self::assertEquals('jane@example.com', $subscriber->getEmail());
        self::assertFalse($subscriber->getDoubleOptInConfirmed());
    }

    /**
     * Tests that getSubscriberBySelector() returns null for a non-existent selector.
     */
    public function testGetSubscriberBySelectorNotFound()
    {
        $subscriber = $this->out->getSubscriberBySelector('nonexistentselector1');

        self::assertNull($subscriber);
    }

    /**
     * Tests that countEmails() returns the correct count.
     */
    public function testCountEmails()
    {
        $count = $this->out->countEmails('john@example.com');

        self::assertEquals(1, $count);
    }

    /**
     * Tests that countEmails() returns 0 for a non-existent email.
     */
    public function testCountEmailsNotFound()
    {
        $count = $this->out->countEmails('nobody@example.com');

        self::assertEquals(0, $count);
    }

    /**
     * Tests inserting a new subscriber via saveSubscriber().
     */
    public function testSaveSubscriberInsert()
    {
        $model = new SubscriberModel();
        $model->setId(0)
            ->setEmail('new@example.com')
            ->setSelector('newsel123')
            ->setConfirmCode('newhash456')
            ->setDoubleOptInDate('2024-07-01 09:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveSubscriber($model);

        $subscribers = $this->out->getSubscribers();
        self::assertCount(3, $subscribers);

        $found = $this->out->getSubscriberByEMail('new@example.com');
        self::assertNotNull($found);
        self::assertGreaterThan(2, $found->getId());
        self::assertEquals('newsel123', $found->getSelector());
        self::assertEquals('newhash456', $found->getConfirmCode());
        self::assertEquals('2024-07-01 09:00:00', $found->getDoubleOptInDate());
        self::assertTrue($found->getDoubleOptInConfirmed());
    }

    /**
     * Tests updating an existing subscriber via saveSubscriber().
     */
    public function testSaveSubscriberUpdate()
    {
        $model = new SubscriberModel();
        $model->setId(1)
            ->setEmail('john_updated@example.com')
            ->setSelector('updatedsel')
            ->setConfirmCode('updatedhash')
            ->setDoubleOptInDate('2024-08-01 10:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveSubscriber($model);

        $subscriber = $this->out->getSubscriberByEMail('john_updated@example.com');
        self::assertNotNull($subscriber);
        self::assertEquals(1, $subscriber->getId());
        self::assertEquals('updatedsel', $subscriber->getSelector());
        self::assertEquals('updatedhash', $subscriber->getConfirmCode());
        self::assertEquals('2024-08-01 10:00:00', $subscriber->getDoubleOptInDate());
    }

    /**
     * Tests that update does not affect other subscribers.
     */
    public function testSaveSubscriberUpdateDoesNotAffectOthers()
    {
        $model = new SubscriberModel();
        $model->setId(1)
            ->setEmail('john_changed@example.com')
            ->setSelector('changedsel')
            ->setConfirmCode('changedhash')
            ->setDoubleOptInDate('2024-08-01 10:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveSubscriber($model);

        $other = $this->out->getSubscriberByEMail('jane@example.com');
        self::assertNotNull($other);
        self::assertEquals('xyz789uvw456rst3', $other->getSelector());
        self::assertFalse($other->getDoubleOptInConfirmed());
    }

    /**
     * Tests that deleteSubscriberByEmail() removes a subscriber.
     */
    public function testDeleteSubscriberByEmail()
    {
        $this->out->deleteSubscriberByEmail('john@example.com');

        self::assertNull($this->out->getSubscriberByEMail('john@example.com'));

        $subscribers = $this->out->getSubscribers();
        self::assertCount(1, $subscribers);
        self::assertEquals(2, $subscribers[0]->getId());
    }

    /**
     * Tests that deleteSubscriberByEmail() on a non-existent email does not throw.
     */
    public function testDeleteSubscriberByEmailNotFound()
    {
        $this->out->deleteSubscriberByEmail('ghost@example.com');

        $subscribers = $this->out->getSubscribers();
        self::assertCount(2, $subscribers);
    }

    /**
     * Tests that deleteSubscriberBySelector() removes a subscriber.
     */
    public function testDeleteSubscriberBySelector()
    {
        $this->out->deleteSubscriberBySelector('xyz789uvw456rst3');

        self::assertNull($this->out->getSubscriberBySelector('xyz789uvw456rst3'));

        $subscribers = $this->out->getSubscribers();
        self::assertCount(1, $subscribers);
        self::assertEquals(1, $subscribers[0]->getId());
    }

    /**
     * Tests that deleteSubscriberBySelector() on a non-existent selector does not throw.
     */
    public function testDeleteSubscriberBySelectorNotFound()
    {
        $this->out->deleteSubscriberBySelector('ghostselector123456789');

        $subscribers = $this->out->getSubscribers();
        self::assertCount(2, $subscribers);
    }

    /**
     * Tests that deleteOldUnconfirmedDoubleOptIn() removes only old unconfirmed entries.
     */
    public function testDeleteOldUnconfirmedDoubleOptIn()
    {
        // Subscriber 2 is unconfirmed with a date far in the past (> 24h)
        // Subscriber 1 is confirmed, so it should remain
        $this->out->deleteOldUnconfirmedDoubleOptIn();

        self::assertNull($this->out->getSubscriberByEMail('jane@example.com'));

        $remaining = $this->out->getSubscriberByEMail('john@example.com');
        self::assertNotNull($remaining);
        self::assertEquals(1, $remaining->getId());
    }

    /**
     * Tests that getSendMailUser() returns email, selector, and name for subscribers.
     */
    public function testGetSendMailUser()
    {
        $rows = $this->out->getSendMailUser();

        self::assertIsArray($rows);
        self::assertCount(2, $rows);

        self::assertEquals('john@example.com', $rows[0]['email']);
        self::assertEquals('abc123def456ghi7', $rows[0]['selector']);
        self::assertEquals('John Doe', $rows[0]['name']);

        self::assertEquals('jane@example.com', $rows[1]['email']);
        self::assertEquals('xyz789uvw456rst3', $rows[1]['selector']);
        self::assertEquals('Jane Smith', $rows[1]['name']);
    }

    /**
     * Tests that getSendMailUser() returns null name for a subscriber
     * with no matching user (LEFT JOIN behavior).
     */
    public function testGetSendMailUserNoMatchingUser()
    {
        $model = new SubscriberModel();
        $model->setId(0)
            ->setEmail('orphan@example.com')
            ->setSelector('orphansel123')
            ->setConfirmCode('orphanhash456')
            ->setDoubleOptInDate('2024-09-01 00:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveSubscriber($model);

        $rows = $this->out->getSendMailUser();

        self::assertCount(3, $rows);

        $found = null;
        foreach ($rows as $row) {
            if ($row['email'] === 'orphan@example.com') {
                $found = $row;
                break;
            }
        }

        self::assertNotNull($found);
        self::assertEquals('orphansel123', $found['selector']);
        self::assertNull($found['name']);
    }

    /**
     * Tests that getSendMailUser() returns empty array when no subscribers exist.
     */
    public function testGetSendMailUserEmpty()
    {
        $this->out->deleteSubscriberByEmail('john@example.com');
        $this->out->deleteSubscriberByEmail('jane@example.com');

        $rows = $this->out->getSendMailUser();

        self::assertIsArray($rows);
        self::assertCount(0, $rows);
    }

    /**
     * Tests that saveUserAsSubscriber() inserts a subscriber
     * for a user who is not yet subscribed.
     */
    public function testSaveUserAsSubscriberInsert()
    {
        // User id 3 (newuser@example.com) is NOT in newsletter_mails
        $model = new SubscriberModel();
        $model->setId(3)
            ->setSelector('newusersel1')
            ->setConfirmCode('newuserhash1')
            ->setDoubleOptInDate('2024-09-15 10:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveUserAsSubscriber($model);

        $subscriber = $this->out->getSubscriberByEMail('newuser@example.com');
        self::assertNotNull($subscriber);
        self::assertEquals('newusersel1', $subscriber->getSelector());
        self::assertEquals('newuserhash1', $subscriber->getConfirmCode());
        self::assertEquals('2024-09-15 10:00:00', $subscriber->getDoubleOptInDate());
        self::assertTrue($subscriber->getDoubleOptInConfirmed());
    }

    /**
     * Tests that saveUserAsSubscriber() deletes a subscriber
     * for a user who is already subscribed (toggle off).
     */
    public function testSaveUserAsSubscriberDelete()
    {
        // User id 1 (john@example.com) IS in newsletter_mails
        $model = new SubscriberModel();
        $model->setId(1)
            ->setSelector('unused')
            ->setConfirmCode('unused')
            ->setDoubleOptInDate('2024-09-15 10:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveUserAsSubscriber($model);

        self::assertNull($this->out->getSubscriberByEMail('john@example.com'));

        // Other subscribers unaffected
        $remaining = $this->out->getSubscriberByEMail('jane@example.com');
        self::assertNotNull($remaining);
        self::assertEquals('xyz789uvw456rst3', $remaining->getSelector());
    }

    /**
     * Tests the full toggle cycle: delete then re-insert.
     */
    public function testSaveUserAsSubscriberToggle()
    {
        // First call: delete john@example.com
        $model = new SubscriberModel();
        $model->setId(1)
            ->setSelector('sel1')
            ->setConfirmCode('code1')
            ->setDoubleOptInDate('2024-09-15 10:00:00')
            ->setDoubleOptInConfirmed(true);

        $this->out->saveUserAsSubscriber($model);
        self::assertNull($this->out->getSubscriberByEMail('john@example.com'));

        // Second call: re-insert john@example.com
        $this->out->saveUserAsSubscriber($model);
        $reinserted = $this->out->getSubscriberByEMail('john@example.com');
        self::assertNotNull($reinserted);
        self::assertEquals('sel1', $reinserted->getSelector());
        self::assertEquals('code1', $reinserted->getConfirmCode());
        self::assertEquals('2024-09-15 10:00:00', $reinserted->getDoubleOptInDate());
    }

    /**
     * Tests that saveUserAsSubscriber() throws for a non-existent user id.
     */
    public function testSaveUserAsSubscriberNonExistentUser()
    {
        $model = new SubscriberModel();
        $model->setId(9999)
            ->setSelector('sel')
            ->setConfirmCode('code')
            ->setDoubleOptInDate('2024-09-15 10:00:00')
            ->setDoubleOptInConfirmed(true);

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('User not found.');

        $this->out->saveUserAsSubscriber($model);
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

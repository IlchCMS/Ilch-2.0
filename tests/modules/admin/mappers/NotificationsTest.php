<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use PHPUnit\Ilch\PhpunitDataset;
use Ilch\Registry;
use Ilch\Translator;

/**
 * Tests the Notifications mapper class.
 *
 * @package ilch_phpunit
 */
class NotificationsTest extends DatabaseTestCase
{
    /**
     * @var NotificationsMapper
     */
    protected Notifications $out;

    protected PhpunitDataset $phpunitDataset;

    /**
     * Filling the config object with individual testcase data.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new NotificationsMapper();

        // Validation (used by isValidNotification()) gets the translator from the
        // registry to translate error messages of failed validators. No translator
        // is registered in the test environment, so register one here.
        // The registry is static and persists between tests, therefore only set
        // it if it is not set yet.
        if (Registry::get('translator') === null) {
            Registry::set('translator', new Translator());
        }
    }

    /**
     * Tests if getNotificationById() returns the sample from the database.
     * Do some basic checks if it contains the expected values.
     *
     */
    public function testGetNotificationById()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setId(1);
        $notificationModel->setTimestamp('2014-01-01 12:12:12');
        $notificationModel->setModule('article');
        $notificationModel->setMessage('Testmessage1');
        $notificationModel->setURL('https://www.google.de');
        $notificationModel->setType('articleNewArticle');

        $notification = $this->out->getNotificationById(1);
        self::assertEquals(1, $notification->getId());
        // The timestamp can vary by one hour. Therefore, for example comparing
        // the notificationModel with the one from the database assertEquals() would not work always.
        self::assertSame('article', $notification->getModule());
        self::assertSame('Testmessage1', $notification->getMessage());
        self::assertSame('https://www.google.de', $notification->getURL());
        self::assertSame('articleNewArticle', $notification->getType());
    }

    /**
     * Tests if getNotificationById() returns null when trying to get a notification with a
     * non-existing id.
     *
     */
    public function testGetNotificationByIdNotExisting()
    {
        self::assertNull($this->out->getNotificationById(99));
    }

    /**
     * Tests if getNotifications() returns all samples from the database.
     *
     */
    public function testGetNotifications()
    {
        self::assertCount(2, $this->out->getNotifications());
    }

    /**
     * Tests if getNotifications() returns NotificationModel instances.
     *
     */
    public function testGetNotificationsReturnsModels()
    {
        $notifications = $this->out->getNotifications();

        self::assertInstanceOf(NotificationModel::class, $notifications[0]);
        self::assertInstanceOf(NotificationModel::class, $notifications[1]);
        self::assertSame('article', $notifications[0]->getModule());
        self::assertSame('awards', $notifications[1]->getModule());
    }

    /**
     * Tests if getNotificationsBy() returns all samples matching a custom where clause.
     *
     */
    public function testGetNotificationsBy()
    {
        self::assertCount(1, $this->out->getNotificationsBy(['module' => 'article']));
        self::assertSame('article', $this->out->getNotificationsBy(['module' => 'article'])[0]->getModule());
    }

    /**
     * Tests if getNotificationsBy() returns an empty array if no sample matches the where clause.
     *
     */
    public function testGetNotificationsByNotExisting()
    {
        self::assertEmpty($this->out->getNotificationsBy(['module' => 'xyzmodule']));
    }

    /**
     * Tests if getNotificationsByModule() returns all samples from the database for a specific module.
     *
     */
    public function testGetNotificationsByModule()
    {
        self::assertCount(1, $this->out->getNotificationsByModule('article'));
    }

    /**
     * Tests if getNotificationsByModule() returns an empty array if there is no notification from a module.
     *
     */
    public function testGetNotificationsByModuleNotExisting()
    {
        self::assertEmpty($this->out->getNotificationsByModule('xyzmodule'));
    }

    /**
     * Tests if getNotificationsByType() returns all samples from the database with a specific type.
     *
     */
    public function testGetNotificationsByType()
    {
        self::assertCount(1, $this->out->getNotificationsByType('articleNewArticle'));
    }

    /**
     * Tests if getNotificationsByType() returns an empty array if there is no notification with a specific type.
     *
     */
    public function testGetNotificationsByTypeNotExisting()
    {
        self::assertEmpty($this->out->getNotificationsByType('xyzmodule'));
    }

    /**
     * Tests if isValidNotification() returns true for a valid NotificationModel.
     *
     */
    public function testIsValidNotification()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setId(1);
        $notificationModel->setTimestamp('2014-01-01 12:12:12');
        $notificationModel->setModule('article');
        $notificationModel->setMessage('Testmessage1');
        $notificationModel->setURL('https://www.google.de');
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        self::assertTrue($this->out->isValidNotification($notificationModel));
    }

    /**
     * Tests if isValidNotification() returns false if the url is invalid.
     *
     */
    public function testIsValidNotificationInvalidUrl()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setModule('article');
        $notificationModel->setMessage('Testmessage1');
        $notificationModel->setURL('invalid-url');

        self::assertFalse($this->out->isValidNotification($notificationModel));
    }

    /**
     * Tests if isValidNotification() returns false if required fields are missing.
     *
     */
    public function testIsValidNotificationMissingFields()
    {
        $notificationModel = new NotificationModel();

        self::assertFalse($this->out->isValidNotification($notificationModel));
    }

    /**
     * Tests if addNotification() successfully adds a valid NotificationModel.
     * Do some basic checks if it contains the expected values.
     *
     */
    public function testAddNotification()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setModule('awards');
        $notificationModel->setMessage('Testmessage3');
        $notificationModel->setURL('https://www.google.de');
        $notificationModel->setType('awardsNewAward');
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        self::assertEquals(3, $this->out->addNotification($notificationModel));
        $notification = $this->out->getNotificationById(3);
        self::assertSame('awards', $notification->getModule());
        self::assertSame('Testmessage3', $notification->getMessage());
        self::assertSame('https://www.google.de', $notification->getURL());
        self::assertSame('awardsNewAward', $notification->getType());
    }

    /**
     * Tests if addNotification() returns 0 for an invalid NotificationModel.
     *
     */
    public function testAddNotificationInvalid()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setModule('article');
        $notificationModel->setURL('invalid-url');

        self::assertEquals(0, $this->out->addNotification($notificationModel));
        self::assertCount(1, $this->out->getNotificationsByModule('article'));
    }

    /**
     * Tests if addNotification() returns 0 if the module is not granted to issue notifications.
     *
     */
    public function testAddNotificationNotGranted()
    {
        $notificationPermissionMapper = new NotificationPermissionMapper();
        $notificationPermissionMapper->updatePermissionGrantedOfModule('article', false);

        $notificationModel = new NotificationModel();
        $notificationModel->setModule('article');
        $notificationModel->setMessage('Testmessage4');
        $notificationModel->setURL('https://www.google.de');
        $notificationModel->setType('articleNewArticle');

        self::assertEquals(0, $this->out->addNotification($notificationModel));
        self::assertCount(1, $this->out->getNotificationsByModule('article'));
    }

    /**
     * Tests if addNotification() returns 0 if the limit of notifications for the module is reached.
     *
     */
    public function testAddNotificationLimitReached()
    {
        $notificationPermissionMapper = new NotificationPermissionMapper();
        $notificationPermissionMapper->updateLimitOfModule('awards', 1);

        $notificationModel = new NotificationModel();
        $notificationModel->setModule('awards');
        $notificationModel->setMessage('Testmessage4');
        $notificationModel->setURL('https://www.ilch.de');
        $notificationModel->setType('awardsNewAward');

        self::assertEquals(0, $this->out->addNotification($notificationModel));
        self::assertCount(1, $this->out->getNotificationsByModule('awards'));
    }

    /**
     * Tests if addNotification() adds notifications without restriction if the limit is 0.
     *
     */
    public function testAddNotificationUnlimited()
    {
        $notificationPermissionMapper = new NotificationPermissionMapper();
        $notificationPermissionMapper->updateLimitOfModule('awards', 0);

        for ($i = 1; $i <= 3; $i++) {
            $notificationModel = new NotificationModel();
            $notificationModel->setModule('awards');
            $notificationModel->setMessage('Testmessage' . $i);
            $notificationModel->setURL('https://www.ilch.de');
            $notificationModel->setType('awardsNewAward');

            self::assertGreaterThan(0, $this->out->addNotification($notificationModel));
        }

        self::assertCount(4, $this->out->getNotificationsByModule('awards'));
    }

    /**
     * Tests if addNotification() creates a permission for a module which does not have one yet.
     *
     */
    public function testAddNotificationCreatesPermissionForNewModule()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setModule('guestbook');
        $notificationModel->setMessage('Testmessage4');
        $notificationModel->setURL('https://www.google.de');
        $notificationModel->setType('guestbookNewGuestbook');

        self::assertEquals(3, $this->out->addNotification($notificationModel));

        $notificationPermissionMapper = new NotificationPermissionMapper();
        $permission = $notificationPermissionMapper->getPermissionOfModule('guestbook');
        self::assertNotNull($permission);
        self::assertSame('guestbook', $permission->getModule());
        self::assertEquals(1, $permission->getGranted());
        self::assertEquals(5, $permission->getLimit());
    }

    /**
     * Tests if updateNotification() successfully updates a NotificationModel.
     * Do some basic checks if it contains the expected values after updating.
     *
     */
    public function testUpdateNotificationById()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setId(2);
        $notificationModel->setModule('awards');
        $notificationModel->setMessage('Testmessage3');
        $notificationModel->setURL('https://www.google.de');
        $notificationModel->setType('awardsNewAward2');
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        $this->out->updateNotificationById($notificationModel);
        $notification = $this->out->getNotificationById(2);
        self::assertSame('awards', $notification->getModule());
        self::assertSame('Testmessage3', $notification->getMessage());
        self::assertSame('https://www.google.de', $notification->getURL());
        self::assertSame('awardsNewAward2', $notification->getType());
    }

    /**
     * Tests if updateNotificationById() returns 0 for an invalid NotificationModel and does not update.
     *
     */
    public function testUpdateNotificationByIdInvalid()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setId(1);
        $notificationModel->setModule('article');
        $notificationModel->setMessage('ChangedMessage');
        $notificationModel->setURL('invalid-url');

        self::assertEquals(0, $this->out->updateNotificationById($notificationModel));
        self::assertSame('Testmessage1', $this->out->getNotificationById(1)->getMessage());
        self::assertSame('https://www.google.de', $this->out->getNotificationById(1)->getURL());
    }

    /**
     * Tests if updateNotificationById() doesn't update anything if the id was wrong.
     *
     */
    public function testUpdateNotificationByIdNotExisting()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->setId(99);
        $notificationModel->setModule('article');
        $notificationModel->setMessage('ChangedMessage');
        $notificationModel->setURL('https://www.google.de');
        $notificationModel->setType('articleNewArticle');

        self::assertEquals(0, $this->out->updateNotificationById($notificationModel));
        self::assertCount(2, $this->out->getNotifications());
    }

    /**
     * Tests if deleteNotificationById() successfully deletes the notification with the id 1.
     *
     */
    public function testDeleteNotificationById()
    {
        $this->out->deleteNotificationById(1);
        self::assertNull($this->out->getNotificationById(1));
    }

    /**
     * Tests if deleteNotificationById() doesn't delete anything if the id was wrong.
     *
     */
    public function testDeleteNotificationByIdNotExisting()
    {
        $this->out->deleteNotificationById(99);
        self::assertCount(2, $this->out->getNotifications());
    }

    /**
     * Tests if deleteNotificationsByModule() deletes the entry for the article module.
     *
     */
    public function testDeleteNotificationsByModule()
    {
        $this->out->deleteNotificationsByModule('article');
        self::assertCount(0, $this->out->getNotificationsByModule('article'));
    }

    /**
     * Tests if deleteNotificationsByModule() doesn't delete anything if the module was wrong.
     *
     */
    public function testDeleteNotificationsByModuleNotExisting()
    {
        $this->out->deleteNotificationsByModule('xyzmodule');
        self::assertCount(2, $this->out->getNotifications());
    }

    /**
     * Tests if deleteNotificationsByType() deletes the entry with the specified type.
     *
     */
    public function testDeleteNotificationsByType()
    {
        $this->out->deleteNotificationsByType('awardsNewAward');
        self::assertCount(0, $this->out->getNotificationsByType('awardsNewAward'));
    }

    /**
     * Tests if deleteNotificationsByType() doesn't delete anything if the type was wrong.
     *
     */
    public function testDeleteNotificationsByTypeNotExisting()
    {
        $this->out->deleteNotificationsByType('xyzmodule');
        self::assertCount(2, $this->out->getNotifications());
    }

    /**
     * Tests if deleteAllNotifications() deletes all notifications.
     *
     */
    public function testDeleteAllNotifications()
    {
        $this->out->deleteAllNotifications();
        self::assertCount(0, $this->out->getNotifications());
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        return $config->getInstallSql();
    }
}

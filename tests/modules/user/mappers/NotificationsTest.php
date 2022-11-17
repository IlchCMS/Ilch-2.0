<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Models\NotificationPermission as NotificationPermissionModel;
use Modules\User\Models\Notification as NotificationModel;
use PHPUnit\Ilch\PhpunitDataset;
use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\User\Mappers\Notifications as NotificationsMapper;

/**
 * Tests the notification mapper class.
 *
 * @package ilch_phpunit
 */
class NotificationsTest extends DatabaseTestCase
{
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/database_users_notifications.yml');
    }

    /**
     * Tests getting a notification by id.
     */
    public function testGetNotificationById()
    {
        $mapper = new NotificationsMapper();
        $notification = $mapper->getNotificationById(1);

        self::assertNotNull($notification);

        self::assertEquals(1, $notification->getId());
        self::assertEquals(1, $notification->getUserId());
        self::assertEquals('2014-01-01 12:12:12', $notification->getTimestamp());
        self::assertEquals('forum', $notification->getModule());
        self::assertEquals('test', $notification->getMessage());
        self::assertEquals('https://www.ilch.de', $notification->getURL());
        self::assertEquals('forumTest', $notification->getType());
    }

    /**
     * Tests getting notifications of user.
     */
    public function testGetNotifications()
    {
        $mapper = new NotificationsMapper();
        $notifications = $mapper->getNotifications(1);

        self::assertNotNull($notifications);

        self::assertEquals(1, $notifications[0]->getId());
        self::assertEquals(1, $notifications[0]->getUserId());
        self::assertEquals('2014-01-01 12:12:12', $notifications[0]->getTimestamp());
        self::assertEquals('forum', $notifications[0]->getModule());
        self::assertEquals('test', $notifications[0]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[0]->getURL());
        self::assertEquals('forumTest', $notifications[0]->getType());
    }

    /**
     * Tests getting notifications with an invalid user id. No result.
     */
    public function testGetNotificationsNoResult()
    {
        $mapper = new NotificationsMapper();
        $notifications = $mapper->getNotifications(0);

        self::assertSame([], $notifications);
    }

    /**
     * Tests getting notifications (sorted by date and type)
     */
    public function testGetNotificationsSortedByDateType()
    {
        $mapper = new NotificationsMapper();
        $notifications = $mapper->getNotificationsSortedByDateType(1);

        self::assertNotNull($notifications);
        self::assertCount(3, $notifications);

        self::assertEquals(3, $notifications[0]->getId());
        self::assertEquals(1, $notifications[0]->getUserId());
        self::assertEquals('2014-01-03 12:12:12', $notifications[0]->getTimestamp());
        self::assertEquals('forum', $notifications[0]->getModule());
        self::assertEquals('test3', $notifications[0]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[0]->getURL());
        self::assertEquals('forumTest', $notifications[0]->getType());

        self::assertEquals(1, $notifications[1]->getId());
        self::assertEquals(1, $notifications[1]->getUserId());
        self::assertEquals('2014-01-01 12:12:12', $notifications[1]->getTimestamp());
        self::assertEquals('forum', $notifications[1]->getModule());
        self::assertEquals('test', $notifications[1]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[1]->getURL());
        self::assertEquals('forumTest', $notifications[1]->getType());

        self::assertEquals(2, $notifications[2]->getId());
        self::assertEquals(1, $notifications[2]->getUserId());
        self::assertEquals('2014-01-02 12:12:12', $notifications[2]->getTimestamp());
        self::assertEquals('forum', $notifications[2]->getModule());
        self::assertEquals('test2', $notifications[2]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[2]->getURL());
        self::assertEquals('forumTest2', $notifications[2]->getType());
    }

    /**
     * Tests getting notifications by module and user id.
     */
    public function testGetNotificationsByModule()
    {
        $mapper = new NotificationsMapper();
        $notifications = $mapper->getNotificationsByModule('forum', '1');

        self::assertNotNull($notifications);
        self::assertCount(3, $notifications);

        self::assertEquals(1, $notifications[0]->getId());
        self::assertEquals(1, $notifications[0]->getUserId());
        self::assertEquals('2014-01-01 12:12:12', $notifications[0]->getTimestamp());
        self::assertEquals('forum', $notifications[0]->getModule());
        self::assertEquals('test', $notifications[0]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[0]->getURL());
        self::assertEquals('forumTest', $notifications[0]->getType());

        self::assertEquals(2, $notifications[1]->getId());
        self::assertEquals(1, $notifications[1]->getUserId());
        self::assertEquals('2014-01-02 12:12:12', $notifications[1]->getTimestamp());
        self::assertEquals('forum', $notifications[1]->getModule());
        self::assertEquals('test2', $notifications[1]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[1]->getURL());
        self::assertEquals('forumTest2', $notifications[1]->getType());

        self::assertEquals(3, $notifications[2]->getId());
        self::assertEquals(1, $notifications[2]->getUserId());
        self::assertEquals('2014-01-03 12:12:12', $notifications[2]->getTimestamp());
        self::assertEquals('forum', $notifications[2]->getModule());
        self::assertEquals('test3', $notifications[2]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[2]->getURL());
        self::assertEquals('forumTest', $notifications[2]->getType());
    }

    /**
     * Tests getting notifications by type and user id.
     */
    public function testGetNotificationsByType()
    {
        $mapper = new NotificationsMapper();
        $notifications = $mapper->getNotificationsByType('forumTest', '1');

        self::assertNotNull($notifications);
        self::assertCount(2, $notifications);

        self::assertEquals(1, $notifications[0]->getId());
        self::assertEquals(1, $notifications[0]->getUserId());
        self::assertEquals('2014-01-01 12:12:12', $notifications[0]->getTimestamp());
        self::assertEquals('forum', $notifications[0]->getModule());
        self::assertEquals('test', $notifications[0]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[0]->getURL());
        self::assertEquals('forumTest', $notifications[0]->getType());

        self::assertEquals(3, $notifications[1]->getId());
        self::assertEquals(1, $notifications[1]->getUserId());
        self::assertEquals('2014-01-03 12:12:12', $notifications[1]->getTimestamp());
        self::assertEquals('forum', $notifications[1]->getModule());
        self::assertEquals('test3', $notifications[1]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[1]->getURL());
        self::assertEquals('forumTest', $notifications[1]->getType());
    }

    /**
     * Tests if the validation function returns true for a valid notification.
     */
    public function testIsValidNotification()
    {
        $_SERVER['HTTP_HOST'] = 'www.ilch.de';

        $mapper = new NotificationsMapper();
        $notificationModel = new NotificationModel();

        $notificationModel->setId(1);
        $notificationModel->setUserId(1);
        $notificationModel->setTimestamp('2014-01-03 12:12:12');
        $notificationModel->setModule('forum');
        $notificationModel->setMessage('test');
        $notificationModel->setURL('https://www.ilch.de');
        $notificationModel->setType('forumTest');

        self::assertTrue($mapper->isValidNotification($notificationModel));
    }

    /**
     * Tests adding a notification.
     */
    public function testAddNotification()
    {
        $mapper = new NotificationsMapper();
        $notificationModel = new NotificationModel();

        $notificationModel->setUserId(1);
        $notificationModel->setModule('forum');
        $notificationModel->setMessage('test');
        $notificationModel->setURL('https://www.ilch.de');
        $notificationModel->setType('forumTest');

        $lastInsertId = $mapper->addNotification($notificationModel);
        $notification = $mapper->getNotificationById($lastInsertId);

        self::assertSame(4, $lastInsertId);

        self::assertEquals($lastInsertId, $notification->getId());
        self::assertEquals(1, $notification->getUserId());
        self::assertEquals('forum', $notification->getModule());
        self::assertEquals('test', $notification->getMessage());
        self::assertEquals('https://www.ilch.de', $notification->getURL());
        self::assertEquals('forumTest', $notification->getType());
    }

    /**
     * Tests adding multiple notifications.
     * Checks if the permissions for these got added.
     */
    public function testAddNotifications()
    {
        $mapper = new NotificationsMapper();
        $permissionMapper = new NotificationPermissionMapper();
        $notificationModels = [];

        for ($index = 1; $index <= 3; $index++) {
            $notificationModel = new NotificationModel();
            $notificationModel->setUserId(2);
            $notificationModel->setModule('forum');
            $notificationModel->setMessage('test' . $index);
            $notificationModel->setURL('https://www.ilch.de');
            $notificationModel->setType('forumTest' . $index);
            $notificationModels[] = $notificationModel;
        }

        $affectedRows = $mapper->addNotifications($notificationModels);
        $notifications = $mapper->getNotifications(2);
        $permissions = $permissionMapper->getPermissionsOfModule('forum', 2);

        self::assertEquals(3, $affectedRows);
        // Should have added three permissions for "forumTest1", "forumTest2" and "forumTest3" to the
        // existing for "".
        self::assertCount(4, $permissions);

        self::assertEquals(4, $notifications[0]->getId());
        self::assertEquals(2, $notifications[0]->getUserId());
        self::assertEquals('forum', $notifications[0]->getModule());
        self::assertEquals('test1', $notifications[0]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[0]->getURL());
        self::assertEquals('forumTest1', $notifications[0]->getType());

        self::assertEquals(5, $notifications[1]->getId());
        self::assertEquals(2, $notifications[1]->getUserId());
        self::assertEquals('forum', $notifications[1]->getModule());
        self::assertEquals('test2', $notifications[1]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[1]->getURL());
        self::assertEquals('forumTest2', $notifications[1]->getType());

        self::assertEquals(6, $notifications[2]->getId());
        self::assertEquals(2, $notifications[2]->getUserId());
        self::assertEquals('forum', $notifications[2]->getModule());
        self::assertEquals('test3', $notifications[2]->getMessage());
        self::assertEquals('https://www.ilch.de', $notifications[2]->getURL());
        self::assertEquals('forumTest3', $notifications[2]->getType());
    }

    /**
     * Tests updating by notification.
     */
    public function testUpdateNotificationById()
    {
        $mapper = new NotificationsMapper();
        $notificationModel = new NotificationModel();

        $notificationModel->setId(3);
        $notificationModel->setUserId(1);
        $notificationModel->setModule('forum');
        $notificationModel->setMessage('test');
        $notificationModel->setURL('https://www.ilch.de');
        $notificationModel->setType('forumTest');

        $affectedRows = $mapper->updateNotificationById($notificationModel);
        $notification = $mapper->getNotificationById(3);

        self::assertEquals(1, $affectedRows);

        self::assertEquals(3, $notification->getId());
        self::assertEquals(1, $notification->getUserId());
        self::assertEquals('2014-01-03 12:12:12', $notification->getTimestamp());
        self::assertEquals('forum', $notification->getModule());
        self::assertEquals('test', $notification->getMessage());
        self::assertEquals('https://www.ilch.de', $notification->getURL());
        self::assertEquals('forumTest', $notification->getType());
    }

    /**
     * Tests deleting a notification by its id and user id.
     */
    public function testDeleteNotificationById()
    {
        $mapper = new NotificationsMapper();
        $affectedRows = $mapper->deleteNotificationById(3, 1);

        self::assertEquals(1, $affectedRows);
    }

    /**
     * Tests deleting notifications by module and user id.
     */
    public function testDeleteNotificationsByModule()
    {
        $mapper = new NotificationsMapper();
        $affectedRows = $mapper->deleteNotificationsByModule('forum', 1);

        self::assertEquals(3, $affectedRows);
    }

    /**
     * Tests deleting notifications by module, type and user id.
     */
    public function testDeleteNotificationsByType()
    {
        $mapper = new NotificationsMapper();
        $affectedRows = $mapper->deleteNotificationsByType('forum', 'forumTest', 1);

        self::assertEquals(2, $affectedRows);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}

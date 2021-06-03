<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use PHPUnit\Ilch\PhpunitDataset;

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
    protected $out;

    protected $phpunitDataset;

    public function setUp()
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new NotificationsMapper();
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
        // The timestamp can vary by one hour. Therefore for example comparing
        // the notificationModel with the one from the database assertEquals() would not work always.
        self::assertSame($notification->getModule(), 'article');
        self::assertSame($notification->getMessage(), 'Testmessage1');
        self::assertSame($notification->getURL(), 'https://www.google.de');
        self::assertSame($notification->getType(), 'articleNewArticle');
    }

    /**
     * Tests if getNotificationById() returns null when trying to get a notification with an
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
        self::assertSame($notification->getModule(), 'awards');
        self::assertSame($notification->getMessage(), 'Testmessage3');
        self::assertSame($notification->getURL(), 'https://www.google.de');
        self::assertSame($notification->getType(), 'awardsNewAward');
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
        self::assertSame($notification->getModule(), 'awards');
        self::assertSame($notification->getMessage(), 'Testmessage3');
        self::assertSame($notification->getURL(), 'https://www.google.de');
        self::assertSame($notification->getType(), 'awardsNewAward2');
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
    public function testDeleteAllNotifications() {
        $this->out->deleteAllNotifications();
        self::assertCount(0, $this->out->getNotifications());
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        return $config->getInstallSql();
    }
}

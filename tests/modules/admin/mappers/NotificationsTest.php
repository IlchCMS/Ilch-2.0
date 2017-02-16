<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;

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

    public function setUp()
    {
        parent::setUp();
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
        $notificationModel->setURL('http://www.google.de');

        $notification = $this->out->getNotificationById(1);
        $this->assertTrue($notification->getId() == 1);
        // The timestamp can vary by one hour. Therefore for example comparing
        // the notificationModel with the one from the database assertEquals() would not work always.
        $this->assertTrue($notification->getModule() == 'article');
        $this->assertTrue($notification->getMessage() == 'Testmessage1');
        $this->assertTrue($notification->getURL() == 'http://www.google.de');
    }

    /**
     * Tests if getNotificationById() returns null when trying to get a notification with an
     * non-existing id.
     *
     */
    public function testGetNotificationByIdNotExisting()
    {
        $this->assertNull($this->out->getNotificationById(99));
    }

    /**
     * Tests if getNotifications() returns all samples from the database.
     *
     */
    public function testGetNotifications()
    {
        $this->assertTrue(count($this->out->getNotifications()) == 2);
    }

    /**
     * Tests if getNotificationsByModule() returns all samples from the database for a specific module.
     *
     */
    public function testGetNotificationsByModule()
    {
        $this->assertTrue(count($this->out->getNotificationsByModule('article')) == 1);
    }

    /**
     * Tests if getNotificationsByModule() returns an empty array if there is no notification from a module.
     *
     */
    public function testGetNotificationsByModuleNotExisting()
    {
        $this->assertEmpty($this->out->getNotificationsByModule('xyzmodule'));
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
        $notificationModel->setURL('http://www.google.de');
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        $this->assertTrue($this->out->isValidNotification($notificationModel));
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
        $notificationModel->setURL('http://www.google.de');
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        $this->assertTrue($this->out->addNotification($notificationModel) == 3);
        $notification = $this->out->getNotificationById(3);
        $this->assertTrue($notification->getModule() == 'awards');
        $this->assertTrue($notification->getMessage() == 'Testmessage3');
        $this->assertTrue($notification->getURL() == 'http://www.google.de');
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
        $notificationModel->setURL('http://www.google.de');
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        $this->out->updateNotificationById($notificationModel);
        $notification = $this->out->getNotificationById(2);
        $this->assertTrue($notification->getModule() == 'awards');
        $this->assertTrue($notification->getMessage() == 'Testmessage3');
        $this->assertTrue($notification->getURL() == 'http://www.google.de');
    }

    /**
     * Tests if deleteNotificationById() successfully deletes the notification with the id 1.
     *
     */
    public function testDeleteNotificationById()
    {
        $this->out->deleteNotificationById(1);
        $this->assertNull($this->out->getNotificationById(1));
    }

    /**
     * Tests if deleteNotificationById() doesn't delete anything if the id was wrong.
     *
     */
    public function testDeleteNotificationByIdNotExisting()
    {
        $this->out->deleteNotificationById(99);
        $this->assertTrue(count($this->out->getNotifications()) == 2);
    }

    /**
     * Tests if deleteNotificationsByModule() deletes the entry for the article module.
     *
     */
    public function testDeleteNotificationsByModule()
    {
        $this->out->deleteNotificationsByModule('article');
        $this->assertTrue(count($this->out->getNotificationsByModule('article')) == 0);
    }

    /**
     * Tests if deleteNotificationsByModule() doesn't delete anything if the module was wrong.
     *
     */
    public function testDeleteNotificationsByModuleNotExisting()
    {
        $this->out->deleteNotificationsByModule('xyzmodule');
        $this->assertTrue(count($this->out->getNotifications()) == 2);
    }

    /**
     * Tests if deleteAllNotifications() deletes all notifications.
     *
     */
    public function testDeleteAllNotifications() {
        $this->out->deleteAllNotifications();
        $this->assertTrue(count($this->out->getNotifications()) == 0);
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../_files/mysql_database.yml');
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

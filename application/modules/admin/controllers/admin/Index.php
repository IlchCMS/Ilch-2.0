<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Ilch\Date;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use Modules\Admin\Mappers\Module as ModuleMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        $moduleMapper = new ModuleMapper();
        $modules = $moduleMapper->getKeysInstalledModules();

        // Delete selected notifications
        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_notifications')) {
            $notificationsMapper = new NotificationsMapper();

            foreach ($this->getRequest()->getPost('check_notifications') as $notificationId) {
                $notificationsMapper->deleteNotificationById($notificationId);
            }
        }

        // Delete all expired authTokens of the remember-me-feature
        $authTokenMapper = new \Modules\User\Mappers\AuthToken();
        $authTokenMapper->deleteExpiredAuthTokens();

        // Check if Ilch is up to date
        $update = new \Ilch\Transfer();
        $update->setTransferUrl($this->getConfig()->get('updateserver').'updates.php');
        $update->setVersionNow($this->getConfig()->get('version'));
        $update->setCurlOpt(CURLOPT_SSL_VERIFYPEER, TRUE);
        $update->setCurlOpt(CURLOPT_SSL_VERIFYHOST, 2); 
        $update->setCurlOpt(CURLOPT_CAINFO, ROOT_PATH.'/certificate/cacert.pem');
        $update->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
        $update->setCurlOpt(CURLOPT_TIMEOUT, 20);
        $update->setCurlOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $update->setCurlOpt(CURLOPT_FAILONERROR, true);

        if ($update->getVersions() == '') {
            $this->getView()->set('curlErrorOccured', true);
            $this->addMessage(curl_error($update->getTransferUrl()), 'danger');
        } else {
            // If check for an ilch update didn't already failed then check for module updates
            $countOfUpdatesAvailable = 0;
            $modulesList = url_get_contents($this->getConfig()->get('updateserver').'modules.php');
            $modulesOnUpdateServer = json_decode($modulesList);
            $versionsOfModules = $moduleMapper->getVersionsOfModules();

            foreach ($modulesOnUpdateServer as $moduleOnUpdateServer) {
                if (in_array($moduleOnUpdateServer->key, $modules)) {
                    if (version_compare($versionsOfModules[$moduleOnUpdateServer->key]['version'], $moduleOnUpdateServer->version, '<')) {
                        $countOfUpdatesAvailable += 1;
                    }
                }
            }

            $notificationsMapper = new NotificationsMapper();
            if ($countOfUpdatesAvailable) {
                $notifications = $notificationsMapper->getNotificationsByType('adminModuleUpdatesAvailable');

                $currentTime = new Date();
                $notificationModel = new NotificationModel();
                $notificationModel->setModule('admin');
                $notificationModel->setMessage($this->getTranslator()->trans('moduleUpdatesAvailable', $countOfUpdatesAvailable));
                $notificationModel->setURL($this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'updates']));
                $notificationModel->setType('adminModuleUpdatesAvailable');

                if (!$notifications) {
                    $notificationsMapper->addNotification($notificationModel);
                } elseif ((strtotime($currentTime->toDb(true)) - strtotime($notifications[count($notifications)-1]->getTimestamp()) > 86400)) {
                    $notificationModel->setId($notifications[count($notifications)-1]->getId());
                    $notificationsMapper->updateNotificationById($notificationModel);
                }
            } else {
                // There are no module updates available. Delete notifications.
                $notificationsMapper->deleteNotificationsByType('adminModuleUpdatesAvailable');
            }
        }

        if ($update->newVersionFound() == true) {
            $newVersion = $update->getNewVersion();
            $this->getView()->set('foundNewVersions', true);
            $this->getView()->set('newVersion', $newVersion);
        }

        $moduleLocales = [];

        if (in_array('guestbook', $modules)) {
            // Check if there are guestbook entries, which need approval
            $guestbookMapper = new \Modules\Guestbook\Mappers\Guestbook();
            $moduleLocales['guestbook'] = $moduleMapper->getModulesByKey('guestbook', $this->getTranslator()->getLocale());

            $this->getView()->set('guestbookEntries', $guestbookMapper->getEntries(['setfree' => 0]));
        }

        if (in_array('partner', $modules)) {
            // Check if there are partner entries, which need approval
            $partnerMapper = new \Modules\Partner\Mappers\Partner();
            $moduleLocales['partner'] = $moduleMapper->getModulesByKey('partner', $this->getTranslator()->getLocale());

            $this->getView()->set('partnerEntries', $partnerMapper->getEntries(['setfree' => 0]));
        }

        // Check if there are users, which need approval
        $userMapper = new \Modules\User\Mappers\User();
        $moduleLocales['user'] = $moduleMapper->getModulesByKey('user', $this->getTranslator()->getLocale());

        // Check if there are notifications, which need to be shown
        $notificationsMapper = new NotificationsMapper();

        $this->getView()->set('ilchNewsList', $this->getConfig()->get('updateserver').'ilchNews.php');
        $this->getView()->set('usersNotConfirmed', $userMapper->getUserList(['confirmed' => 0]));
        $this->getView()->set('moduleLocales', $moduleLocales);
        $this->getView()->set('version', $this->getConfig()->get('version'));
        $this->getView()->set('notifications', $notificationsMapper->getNotifications());
    }

    public function deleteAction() {
        if ($this->getRequest()->isSecure()) {
            $notificationsMapper = new NotificationsMapper();
            $notificationsMapper->deleteNotificationById($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function revokePermissionAction() {
        if ($this->getRequest()->isSecure()) {
            $notificationPermissionMapper = new NotificationPermissionMapper();
            $notificationsMapper = new NotificationsMapper();

            $module = $this->getRequest()->getParam('key');

            $notificationPermissionMapper->updatePermissionGrantedOfModule($module, false);
            $notificationsMapper->deleteNotificationsByModule($module);

            $this->addMessage('revokePermissionSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

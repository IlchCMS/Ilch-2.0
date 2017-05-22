<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Ilch\Transfer as IlchTransfer;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Mappers\Updateservers as UpdateserversMapper;
use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaintenance',
                'active' => false,
                'icon' => 'fa fa-wrench',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'maintenance'])
            ],
            [
                'name' => 'menuCustomCSS',
                'active' => false,
                'icon' => 'fa fa-file-code-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'customcss'])
            ],
            [
                'name' => 'menuBackup',
                'active' => false,
                'icon' => 'fa fa-download',
                'url' => $this->getLayout()->getUrl(['controller' => 'backup', 'action' => 'index'])
            ],
            [
                'name' => 'menuUpdate',
                'active' => false,
                'icon' => 'fa fa-refresh',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'update'])
            ],
            [
                'name' => 'menuNotifications',
                'active' => false,
                'icon' => 'fa fa-envelope-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'notifications'])
            ],
            [
                'name' => 'menuEmails',
                'active' => false,
                'icon' => 'fa fa-envelope',
                'url' => $this->getLayout()->getUrl(['controller' => 'emails', 'action' => 'index'])
            ],
            [
                'name' => 'menuMail',
                'active' => false,
                'icon' => 'fa fa-newspaper-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'mail'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'maintenance') {
            $items[1]['active'] = true;  
        } elseif ($this->getRequest()->getActionName() == 'customcss') {
            $items[2]['active'] = true;
        } elseif ($this->getRequest()->getActionName() == 'update') {
            $items[4]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'notifications') {
            $items[5]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'mail') {
            $items[7]['active'] = true;
        } else {
            $items[0]['active'] = true; 
        }

        $this->getLayout()->addMenu
        (
            'menuSettings',
            $items
        );
    }

    public function indexAction()
    {
        $moduleMapper = new \Modules\Admin\Mappers\Module();
        $pageMapper = new \Modules\Admin\Mappers\Page();
        $updateserversMapper = new UpdateserversMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'multilingualAcp' => 'required|numeric|integer|min:0|max:1',
                'modRewrite' => 'required|numeric|integer|min:0|max:1',
                'standardMail' => 'required|email',
                'defaultPaginationObjects' => 'numeric|integer|min:1',
                'hmenuFixed' => 'required|numeric|integer|min:0|max:1',
                'updateserver' => 'required|url'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('multilingual_acp', $this->getRequest()->getPost('multilingualAcp'));
                $this->getConfig()->set('content_language', $this->getRequest()->getPost('contentLanguage'));
                $this->getConfig()->set('start_page', $this->getRequest()->getPost('startPage'));
                $this->getConfig()->set('mod_rewrite', (int)$this->getRequest()->getPost('modRewrite'));
                $this->getConfig()->set('standardMail', $this->getRequest()->getPost('standardMail'));
                $this->getConfig()->set('timezone', $this->getRequest()->getPost('timezone'));
                $this->getConfig()->set('locale', $this->getRequest()->getPost('locale'));
                $this->getConfig()->set('defaultPaginationObjects', $this->getRequest()->getPost('defaultPaginationObjects'));
                if ($this->getRequest()->getPost('hmenuFixed') === '1') {
                    $this->getConfig()->set('admin_layout_hmenu', 'hmenu-fixed');
                } elseif ($this->getRequest()->getPost('hmenuFixed') === '0') {
                    $this->getConfig()->set('admin_layout_hmenu', '');
                }
                $this->getConfig()->set('updateserver', $this->getRequest()->getPost('updateserver'));

                if ((int)$this->getRequest()->getPost('modRewrite')) {
                    $htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase %1$s/
    RewriteRule ^index\.php$ - [L]
    RewriteCond %%{REQUEST_FILENAME} !-f
    RewriteCond %%{REQUEST_FILENAME} !-d
    RewriteRule . %1$s/index.php [L]
</IfModule>
HTACCESS;
                    file_put_contents(ROOT_PATH.'/.htaccess', sprintf($htaccess, REWRITE_BASE));
                } elseif (file_exists(ROOT_PATH.'/.htaccess')) {
                    file_put_contents(ROOT_PATH.'/.htaccess', '');
                }

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingualAcp', $this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('startPage', $this->getConfig()->get('start_page'));
        $this->getView()->set('modRewrite', $this->getConfig()->get('mod_rewrite'));
        $this->getView()->set('standardMail', $this->getConfig()->get('standardMail'));
        $this->getView()->set('timezones', \DateTimeZone::listIdentifiers());
        $this->getView()->set('timezone', $this->getConfig()->get('timezone'));
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('pages', $pageMapper->getPageList());
        $this->getView()->set('hmenuFixed', $this->getConfig()->get('admin_layout_hmenu'));
        $this->getView()->set('defaultPaginationObjects', $this->getConfig()->get('defaultPaginationObjects'));
        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver'));
        $this->getView()->set('updateservers', $updateserversMapper->getUpdateservers());
    }

    public function maintenanceAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuMaintenance'), ['action' => 'maintenance']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('maintenance_mode', $this->getRequest()->getPost('maintenanceMode'));
            $this->getConfig()->set('maintenance_date', new \Ilch\Date(trim($this->getRequest()->getPost('maintenanceDateTime'))));
            $this->getConfig()->set('maintenance_status', $this->getRequest()->getPost('maintenanceStatus'));
            $this->getConfig()->set('maintenance_text', $this->getRequest()->getPost('maintenanceText'));

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('maintenanceMode', $this->getConfig()->get('maintenance_mode'));
        $this->getView()->set('maintenanceDate', $this->getConfig()->get('maintenance_date'));
        $this->getView()->set('maintenanceStatus', $this->getConfig()->get('maintenance_status'));
        $this->getView()->set('maintenanceText', $this->getConfig()->get('maintenance_text'));
    }

    public function customcssAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuCustomCSS'), ['action' => 'customcss']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('custom_css', strip_tags($this->getRequest()->getPost('customCSS')));

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('customCSS', $this->getConfig()->get('custom_css'));
    }

    public function updateAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuUpdate'), ['action' => 'update']);

        $this->addMessage('backupBeforeUpdate', 'danger');

        $doUpdate = $this->getRequest()->getParam('doupdate');
        $doSave = $this->getRequest()->getParam('dosave');
        $version = $this->getConfig()->get('version');
        $this->getView()->set('version', $version);

        $update = new IlchTransfer();
        $update->setTransferUrl($this->getConfig()->get('updateserver').'updates.php');
        $update->setVersionNow($version);
        $update->setCurlOpt(CURLOPT_SSL_VERIFYPEER, TRUE);
        $update->setCurlOpt(CURLOPT_SSL_VERIFYHOST, 2); 
        $update->setCurlOpt(CURLOPT_CAINFO, ROOT_PATH.'/certificate/cacert.pem');
        $update->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
        $update->setCurlOpt(CURLOPT_FAILONERROR, true);
        $update->setCurlOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $update->setZipSavePath(ROOT_PATH.'/updates/');

        $result = $update->getVersions();
        if ($result == '') {
            $this->addMessage(curl_error($update->getTransferUrl()), 'danger');
        }

        $this->getView()->set('versions', $result);

        if ($update->newVersionFound() == true) {
            $update->setDownloadUrl($this->getConfig()->get('updateserver').'updates/Master-'.$update->getNewVersion().'.zip');
            $update->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'updates/Master-'.$update->getNewVersion().'.zip-signature.sig');
            $newVersion = $update->getNewVersion();
            $this->getView()->set('foundNewVersions', true);
            $this->getView()->set('newVersion', $newVersion);

            if ($doSave == true) {
                if (!$update->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->getView()->set('certMissingOrExpired', true);
                    return false;
                }
                if (!$update->save()) {
                    $this->getView()->set('verificationFailed', true);
                    return;
                }
            }
            if ($doUpdate == true) {
                if ($update->update($version)) {
                    $this->getView()->set('content', $update->getContent());
                    $this->getConfig()->set('version', $newVersion);
                    $this->getView()->set('updateSuccessfull', true);
                }
            }
        } else {
            $this->getView()->set('versions', '');
        }
        curl_close($update->getTransferUrl());
    }

    public function notificationsAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuNotifications'), ['action' => 'notifications']);

        $notificationPermissionMapper = new NotificationPermissionMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_notificationPermissions')) {
            foreach ($this->getRequest()->getPost('check_notificationPermissions') as $notificationPermissionKey) {
                $notificationPermissionMapper->deletePermissionOfModule($notificationPermissionKey);
            }
        }

        if ($this->getRequest()->isPost()) {
            $validation;
            foreach ($this->getRequest()->getPost('data') as $data) {
                $validation = Validation::create($data, [
                    'key' => 'required',
                    'limit' => 'required|numeric|integer|min:0|max:5'
                ]);

                if ($validation->isValid()) {
                    $notificationPermissionMapper->updateLimitOfModule($data['key'], $data['limit']);
                } else {
                    $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                    $this->redirect()
                        ->withErrors($validation->getErrorBag())
                        ->to(['action' => 'notifications']);
                    break;
                }
            }

            if ($validation->isValid()) {
                $this->addMessage('saveSuccess');
            }
        }

        $this->getView()->set('notificationPermissions', $notificationPermissionMapper->getPermissions());
    }

    public function deletePermissionAction()
    {
        if ($this->getRequest()->isSecure()) {
            $notificationPermissionMapper = new NotificationPermissionMapper();

            $notificationPermissionMapper->deletePermissionOfModule($this->getRequest()->getParam('key'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'notifications']);
    }

    public function changePermissionAction()
    {
        if ($this->getRequest()->isSecure()) {
            $notificationPermissionMapper = new NotificationPermissionMapper();
            $notificationsMapper = new NotificationsMapper();

            if ($this->getRequest()->getParam('revoke') == 'true') {
                $notificationPermissionMapper->updatePermissionGrantedOfModule($this->getRequest()->getParam('key'), 0);
                $notificationsMapper->deleteNotificationsByModule($this->getRequest()->getParam('key'));

                $this->addMessage('revokePermissionSuccess');
            } else {
                $notificationPermissionMapper->updatePermissionGrantedOfModule($this->getRequest()->getParam('key'), 1);
                $this->addMessage('grantedPermissionSuccess');
            }
        }

        $this->redirect(['action' => 'notifications']);
    }

    public function mailAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuMail'), ['action' => 'mail']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('smtp_mode', $this->getRequest()->getPost('smtp_mode'));
            $this->getConfig()->set('smtp_server', $this->getRequest()->getPost('smtp_server'));
            $this->getConfig()->set('smtp_port', $this->getRequest()->getPost('smtp_port'));
            $this->getConfig()->set('smtp_secure', $this->getRequest()->getPost('smtp_secure'));
            $this->getConfig()->set('smtp_user', $this->getRequest()->getPost('smtp_user'));
            $this->getConfig()->set('smtp_pass', $this->getRequest()->getPost('smtp_pass'));
        }
        $this->getView()->set('smtp_mode', $this->getConfig()->get('smtp_mode'));
        $this->getView()->set('smtp_server', $this->getConfig()->get('smtp_server'));
        $this->getView()->set('smtp_port', $this->getConfig()->get('smtp_port'));
        $this->getView()->set('smtp_secure', $this->getConfig()->get('smtp_secure'));
        $this->getView()->set('smtp_user', $this->getConfig()->get('smtp_user'));
        $this->getView()->set('smtp_pass', $this->getConfig()->get('smtp_pass'));
    }
}

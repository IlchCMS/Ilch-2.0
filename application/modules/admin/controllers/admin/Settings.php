<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Ilch\Transfer as IlchTransfer;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Mappers\Updateservers as UpdateserversMapper;
use Modules\User\Mappers\Group as GroupMapper;
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
                'name' => 'menuHtaccess',
                'active' => false,
                'icon' => 'fa fa-file-code-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'htaccess'])
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

        if ($this->getRequest()->getActionName() === 'maintenance') {
            $items[1]['active'] = true;  
        } elseif ($this->getRequest()->getActionName() === 'customcss') {
            $items[2]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'htaccess') {
            $items[3]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'update') {
            $items[5]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'notifications') {
            $items[6]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'mail') {
            $items[8]['active'] = true;
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
        $groupMapper = new GroupMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validationRules = [
                'multilingualAcp' => 'required|numeric|integer|min:0|max:1',
                'standardMail' => 'required|email',
                'defaultPaginationObjects' => 'numeric|integer|min:1',
                'hmenuFixed' => 'required|numeric|integer|min:0|max:1',
                'htmlPurifier' => 'required|numeric|integer|min:0|max:1',
                'updateserver' => 'required|url',
                'captcha' => 'required|numeric|integer|min:0|max:3'
            ];

            if ((int)$this->getRequest()->getPost('captcha') >= 1){
                $validationRules['captcha_apikey'] = 'required';
                $validationRules['captcha_seckey'] = 'required';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $this->getConfig()->set('multilingual_acp', $this->getRequest()->getPost('multilingualAcp'));
                $this->getConfig()->set('content_language', $this->getRequest()->getPost('contentLanguage'));
                $this->getConfig()->set('start_page', $this->getRequest()->getPost('startPage'));
                $this->getConfig()->set('standardMail', $this->getRequest()->getPost('standardMail'));
                $this->getConfig()->set('timezone', $this->getRequest()->getPost('timezone'));
                $this->getConfig()->set('locale', $this->getRequest()->getPost('locale'));
                $this->getConfig()->set('defaultPaginationObjects', $this->getRequest()->getPost('defaultPaginationObjects'));
                $this->getConfig()->set('hideCaptchaFor', implode(',', ($this->getRequest()->getPost('groups')) ?: []));
                $this->getConfig()->set('captcha', $this->getRequest()->getPost('captcha'));
                $this->getConfig()->set('captcha_apikey', $this->getRequest()->getPost('captcha_apikey'));
                $this->getConfig()->set('captcha_seckey', $this->getRequest()->getPost('captcha_seckey'));
                if ($this->getRequest()->getPost('hmenuFixed') === '1') {
                    $this->getConfig()->set('admin_layout_hmenu', 'hmenu-fixed');
                } elseif ($this->getRequest()->getPost('hmenuFixed') === '0') {
                    $this->getConfig()->set('admin_layout_hmenu', '');
                }
                $this->getConfig()->set('disable_purifier', !$this->getRequest()->getPost('htmlPurifier'));
                $this->getConfig()->set('updateserver', $this->getRequest()->getPost('updateserver'));

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
        $this->getView()->set('standardMail', $this->getConfig()->get('standardMail'));
        $this->getView()->set('timezones', \DateTimeZone::listIdentifiers());
        $this->getView()->set('timezone', $this->getConfig()->get('timezone'));
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('pages', $pageMapper->getPageList());
        $this->getView()->set('hmenuFixed', $this->getConfig()->get('admin_layout_hmenu'));
        $this->getView()->set('htmlPurifier', !$this->getConfig()->get('disable_purifier'));
        $this->getView()->set('defaultPaginationObjects', $this->getConfig()->get('defaultPaginationObjects'));
        $this->getView()->set('hideCaptchaFor', explode(',', $this->getConfig()->get('hideCaptchaFor')));
        $this->getView()->set('captcha', (int)$this->getConfig()->get('captcha'));
        $this->getView()->set('captcha_apikey', $this->getConfig()->get('captcha_apikey'));
        $this->getView()->set('captcha_seckey', $this->getConfig()->get('captcha_seckey'));
        $this->getView()->set('groupList', $groupMapper->getGroupList());
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

    public function htaccessAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuHtaccess'), ['action' => 'htaccess']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'modRewrite' => 'required|numeric|integer|min:0|max:1',
            ]);

            if ($validation->isValid()) {
                $htaccess = $this->getRequest()->getPost('htaccess');
                // true if mod rewrite got toggled from on to off
                $removeModRewrite = ($this->getConfig()->get('mod_rewrite') && !(int)$this->getRequest()->getPost('modRewrite'));
                $remove = false;

                if (!$this->getConfig()->get('mod_rewrite') && (int)$this->getRequest()->getPost('modRewrite')) {
                    // Mod rewrite got toggled from off to on.
                    $temp = <<<'HTACCESS'
# Begin Mod Rewrite default lines
# These lines get deleted when disabling Modrewrite in Admincenter!
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase %1$s/
    RewriteRule ^index\.php$ - [L]
    RewriteCond %%{REQUEST_FILENAME} !-f
    RewriteCond %%{REQUEST_FILENAME} !-d
    RewriteRule . %1$s/index.php [L]
</IfModule>
# End Mod Rewrite default lines
HTACCESS;
                    $htaccess .= sprintf($temp, REWRITE_BASE);
                    $this->addMessage('modrewriteLinesAdded', 'info');
                }

                $htaccess = explode(PHP_EOL, $htaccess);
                // Writing the htaccess file and removing the mod rewrite default lines if necessary
                $filePointer = fopen(ROOT_PATH.'/.htaccess', 'wb');
                foreach($htaccess as $line) {
                    if ($removeModRewrite && (strpos($line, '# Begin Mod Rewrite default lines') !== false)) {
                        $this->addMessage('modrewriteLinesRemoved', 'info');
                        $remove = true;
                    }

                    if ($remove) {
                        continue;
                    }

                    fwrite($filePointer, $line);

                    if ($removeModRewrite && (strpos($line, '# End Mod Rewrite default lines') !== false)) {
                        $remove = false;
                    }
                }
                fclose($filePointer);

                $this->getConfig()->set('mod_rewrite', (int)$this->getRequest()->getPost('modRewrite'));
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('modRewrite', $this->getConfig()->get('mod_rewrite'));
        $this->getView()->set('htaccess', file_get_contents(ROOT_PATH.'/.htaccess'));
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
        $update->setTransferUrl($this->getConfig()->get('updateserver').'updates2.php');
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

            if (!empty($update->getMissingRequirements())) {
                // Add details of missingRequirements to the error message.
                $missingRequirementsMessages = [];
                if (!empty($update->getMissingRequirements()['phpVersion'])) {
                    $missingRequirementsMessages[] = $this->getTranslator()->trans('phpVersionError').' (<'.$update->getMissingRequirements()['phpVersion'].')';
                }
                if (!empty($update->getMissingRequirements()['mysqlVersion'])) {
                    $missingRequirementsMessages[] = $this->getTranslator()->trans('dbVersionError').' (<'.$update->getMissingRequirements()['mysqlVersion'].')';
                }
                if (!empty($update->getMissingRequirements()['mariadbVersion'])) {
                    $missingRequirementsMessages[] = $this->getTranslator()->trans('dbVersionError').' (<'.$update->getMissingRequirements()['mariadbVersion'].')';
                }
                if (!empty($update->getMissingRequirements()['phpExtensions'])) {
                    $messageString = $this->getTranslator()->trans('phpExtensionError');
                    $messageString .= ' ('.implode(', ', $update->getMissingRequirements()['phpExtensions']).')';
                    $missingRequirementsMessages[] = $messageString;
                }
                $this->getView()->set('missingRequirements', true);
                $this->getView()->set('missingRequirementsMessages', $missingRequirementsMessages);
                return false;
            }

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
                    $this->getConfig()->set('version', $newVersion);
                    $this->getView()->set('updateSuccessfull', true);
                }
                $this->getView()->set('content', $update->getContent());
            }
        } else {
            $this->getView()->set('versions', '');
        }
    }

    public function clearCacheAction()
    {
        // Delete downloaded updates
        $files = glob('updates/*');
        foreach($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        $this->redirect()
            ->to(['action' => 'update']);
    }

    public function notificationsAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuNotifications'), ['action' => 'notifications']);

        $notificationPermissionMapper = new NotificationPermissionMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_notificationPermissions')) {
            foreach ($this->getRequest()->getPost('check_notificationPermissions') as $notificationPermissionKey) {
                $notificationPermissionMapper->deletePermissionOfModule($notificationPermissionKey);
            }
        }

        if ($this->getRequest()->isPost()) {
            $validation = null;
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

            if (!empty($validation) && $validation->isValid()) {
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

            if ($this->getRequest()->getParam('revoke') === 'true') {
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
            $this->getConfig()->set('emailBlacklist', $this->getRequest()->getPost('emailBlacklist'));
        }

        $this->getView()->set('standardMail', $this->getConfig()->get('standardMail'));
        $this->getView()->set('smtp_mode', $this->getConfig()->get('smtp_mode'));
        $this->getView()->set('smtp_server', $this->getConfig()->get('smtp_server'));
        $this->getView()->set('smtp_port', $this->getConfig()->get('smtp_port'));
        $this->getView()->set('smtp_secure', $this->getConfig()->get('smtp_secure'));
        $this->getView()->set('smtp_user', $this->getConfig()->get('smtp_user'));
        $this->getView()->set('smtp_pass', $this->getConfig()->get('smtp_pass'));
        $this->getView()->set('emailBlacklist', $this->getConfig()->get('emailBlacklist'));
    }
}

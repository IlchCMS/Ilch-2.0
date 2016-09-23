<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Ilch\Transfer as IlchTransfer;

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
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'backup'])
            ],
            [
                'name' => 'menuUpdate',
                'active' => false,
                'icon' => 'fa fa-refresh',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'update'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'maintenance') {
            $items[1]['active'] = true;  
        } elseif ($this->getRequest()->getActionName() == 'customcss') {
            $items[2]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'backup') {
            $items[3]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'update') {
            $items[4]['active'] = true; 
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

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('multilingual_acp', $this->getRequest()->getPost('multilingualAcp'));
            $this->getConfig()->set('content_language', $this->getRequest()->getPost('contentLanguage'));
            $this->getConfig()->set('description', $this->getRequest()->getPost('description'));
            $this->getConfig()->set('page_title', $this->getRequest()->getPost('pageTitle'));
            $this->getConfig()->set('start_page', $this->getRequest()->getPost('startPage'));
            $this->getConfig()->set('mod_rewrite', (int)$this->getRequest()->getPost('modRewrite'));
            $this->getConfig()->set('standardMail', $this->getRequest()->getPost('standardMail'));
            $this->getConfig()->set('timezone', $this->getRequest()->getPost('timezone'));
            $this->getConfig()->set('locale', $this->getRequest()->getPost('locale'));
            $this->getConfig()->set('defaultPaginationObjects', $this->getRequest()->getPost('defaultPaginationObjects'));
            if ($this->getRequest()->getPost('navbarFixed') === '1') {
                $this->getConfig()->set('admin_layout_top_nav', 'navbar-fixed-top');
                
                if ($this->getRequest()->getPost('hmenuFixed') === '1') {
                    $this->getConfig()->set('admin_layout_hmenu', 'hmenu-fixed');
                } elseif ($this->getRequest()->getPost('hmenuFixed') === '0') {
                    $this->getConfig()->set('admin_layout_hmenu', '');
                }
            } elseif ($this->getRequest()->getPost('navbarFixed') === '0') {
                $this->getConfig()->set('admin_layout_top_nav', '');
                $this->getConfig()->set('admin_layout_hmenu', '');
            }

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
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingualAcp', $this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('description', $this->getConfig()->get('description'));
        $this->getView()->set('pageTitle', $this->getConfig()->get('page_title'));
        $this->getView()->set('startPage', $this->getConfig()->get('start_page'));
        $this->getView()->set('modRewrite', $this->getConfig()->get('mod_rewrite'));
        $this->getView()->set('standardMail', $this->getConfig()->get('standardMail'));
        $this->getView()->set('timezones', \DateTimeZone::listIdentifiers());
        $this->getView()->set('timezone', $this->getConfig()->get('timezone'));
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('pages', $pageMapper->getPageList());
        $this->getView()->set('navbarFixed', $this->getConfig()->get('admin_layout_top_nav'));
        $this->getView()->set('hmenuFixed', $this->getConfig()->get('admin_layout_hmenu'));
        $this->getView()->set('defaultPaginationObjects', $this->getConfig()->get('defaultPaginationObjects'));
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

        $doUpdate = $this->getRequest()->getParam('doupdate');
        $doSave = $this->getRequest()->getParam('dosave');
        $version = $this->getConfig()->get('version');
        $this->getView()->set('version', $version);

        $update = new IlchTransfer();
        $update->setTransferUrl($this->getConfig()->get('master_update_url'));
        $update->setVersionNow($version);
        $update->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
        $update->setCurlOpt(CURLOPT_FAILONERROR, true);
        $update->setZipSavePath(ROOT_PATH.'/updates/');

        $result = $update->getVersions();
        if ($result == '') {
            $this->addMessage(curl_error($update->getTransferUrl()), 'danger');
        }

        $this->getView()->set('versions', $result);

        if ($update->newVersionFound() == true) {
            $update->setDownloadUrl($this->getConfig()->get('master_download_url').$update->getNewVersion().'.zip');
            $update->setDownloadSignatureUrl($this->getConfig()->get('master_download_url').$update->getNewVersion().'.zip-signature.sig');
            $newVersion = $update->getNewVersion();
            $this->getView()->set('foundNewVersions', true);
            $this->getView()->set('newVersion', $newVersion);

            if ($doSave == true) {
                if (!$update->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->getView()->set('certMissingOrExpired', true);
                    return false;
                }
                $update->save();
                $signature = file_get_contents($update->getZipFile().'-signature.sig');
                $pubKeyfile = ROOT_PATH.'/certificate/Certificate.crt';
                if (!$update->verifyFile($pubKeyfile, $update->getZipFile(), $signature)) {
                    // Verification failed. Drop the potentially bad files.
                    unlink($update->getZipFile());
                    unlink($update->getZipFile().'-signature.sig');
                    $this->getView()->set('verificationFailed', true);
                }
            }
            if ($doUpdate == true) {
                $update->update();
                $this->getView()->set('content', $update->getContent());
                //$this->getConfig()->set('version', $newVersion);
                // Cleanup after the update was installed.
                unlink($update->getZipFile());
                unlink($update->getZipFile().'-signature.sig');
            }
        } else {
            $this->getView()->set('versions', '');
        }
        curl_close($update->getTransferUrl());
    }

    public function backupAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuBackup'), ['action' => 'backup']);
        
    }
}

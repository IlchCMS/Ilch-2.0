<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

class Update extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = array
        (
            array
            (
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
            ),
            array
            (
                'name' => 'menuMaintenance',
                'active' => false,
                'icon' => 'fa fa-wrench',
                'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'maintenance'))
            ),
            array
            (
                'name' => 'menuBackup',
                'active' => false,
                'icon' => 'fa fa-download',
                'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'backup'))
            ),
            array
            (
                'name' => 'menuUpdate',
                'active' => true,
                'icon' => 'fa fa-download',
                'url' => $this->getLayout()->getUrl(array('controller' => 'update', 'action' => 'index'))
            ),
        );

        $this->getLayout()->addMenu
        (
            'menuUpdate',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('systemSettings'), array('controller' => 'settings', 'action' => 'index'))
            ->add($this->getTranslator()->trans('menuUpdate'), array('action' => 'index'));

        $doUpdate = $this->getRequest()->getParam('doupdate');
        $doSave = $this->getRequest()->getParam('dosave');
        $version = $this->getConfig()->get('version');
        $this->getView()->set('version', $version);

        $update = new \Ilch\Transfer();
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
            $update->setDownloadUrl('http://www.ilch2.de/ftp/Master-'.$update->getNewVersion().'.zip');
            $update->setDownloadSignatureUrl('http://www.ilch2.de/ftp/Master-'.$update->getNewVersion().'.zip-signature.sig');
            $newVersion = $update->getNewVersion();
            $this->getView()->set('foundNewVersions', true);
            $this->getView()->set('newVersion', $newVersion);

            if ($doSave == true) {
                if(!$update->validateCert(APPLICATION_PATH.'/../certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->getView()->set('certMissingOrExpired', true);
                    return false;
                }
                $update->save();
                $signature = file_get_contents($update->getZipFile().'-signature.sig');
                $pubKeyfile = APPLICATION_PATH.'/../certificate/Certificate.crt';
                if(!$update->verifyFile($pubKeyfile, $update->getZipFile(), $signature)) {
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
    }
}

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

        $update = new \Ilch\Update();
        $update->setUpdateUrl($this->getConfig()->get('master_update_url'));
        $update->setVersionNow($version);
        $update->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
        $update->setCurlOpt(CURLOPT_FAILONERROR, true);
        $update->setZipSavePath(ROOT_PATH.'/updates/');

        if (($update->getVersions()) == false) {
            $this->addMessage(curl_error($update->getUrl()), 'danger');
            $this->getView()->set('versions', '');
        } else {
            $this->getView()->set('versions', $update->getVersions() );

            if ($update->newVersionFound() == true) {
                $update->setDownloadUrl('http://www.ilch2.de/ftp/Master-'.$update->getNewVersion().'.zip');
                $aV = $update->getNewVersion();
                $this->getView()->set('foundNewVersions', true);
                $this->getView()->set('aV', $aV);

                if ($doSave == true) {
                    $update->save();
                }
                if ($doUpdate == true) {
                    $update->update();
                    $this->getView()->set('content', $update->getContent());
                }
            } else {
                $this->getView()->set('versions', '');
            }
        }
    }
}

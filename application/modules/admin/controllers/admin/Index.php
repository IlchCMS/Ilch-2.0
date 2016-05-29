<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        // Delete all expired authTokens of the remember-me-feature
        $authTokenMapper = new \Modules\User\Mappers\AuthToken();
        $authTokenMapper->deleteExpiredAuthTokens();

        // Check if Ilch is up to date
        $version = $this->getConfig()->get('version');

        $update = new \Ilch\Transfer();
        $update->setTransferUrl($this->getConfig()->get('master_update_url'));
        $update->setVersionNow($version);
        $update->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
        $update->setCurlOpt(CURLOPT_FAILONERROR, true);

        if ($update->getVersions() == '') {
            $this->addMessage(curl_error($update->getTransferUrl()), 'danger');
        }

        if ($update->newVersionFound() == true) {
            $newVersion = $update->getNewVersion();
            $this->getView()->set('foundNewVersions', true);
            $this->getView()->set('newVersion', $newVersion);
        }
    }
}

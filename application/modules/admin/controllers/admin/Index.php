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
        $update = new \Ilch\Transfer();
        $update->setTransferUrl($this->getConfig()->get('master_update_url'));
        $update->setVersionNow($this->getConfig()->get('version'));
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

        $modulesMapper = new \Modules\Admin\Mappers\Module();
        $modules = $modulesMapper->getKeysInstalledModules();
        $moduleLocales = [];

        if (in_array('guestbook', $modules)) {
            // Check if there are guestbook entries, which need to be unlocked
            $guestbookMapper = new \Modules\Guestbook\Mappers\Guestbook();
            $moduleLocales['guestbook'] = $modulesMapper->getModulesByKey('guestbook', $this->getTranslator()->getLocale());

            $this->getView()->set('guestbookEntries', $guestbookMapper->getEntries(['setfree' => 0]));
        }

        if (in_array('partner', $modules)) {
            // Check if there are partner entries, which need to be unlocked
            $partnerMapper = new \Modules\Partner\Mappers\Partner();
            $moduleLocales['partner'] = $modulesMapper->getModulesByKey('partner', $this->getTranslator()->getLocale());

            $this->getView()->set('partnerEntries', $partnerMapper->getEntries(['setfree' => 0]));
        }

        $this->getView()->set('moduleLocales', $moduleLocales);
    }
}

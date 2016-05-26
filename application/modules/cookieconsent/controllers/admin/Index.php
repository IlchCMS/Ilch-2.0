<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers\Admin;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuCookieConsent',
            [
                [
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ]
            ]
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuCookieConsent'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('cookie_consent', $this->getRequest()->getPost('cookieConsent'));
            $this->getConfig()->set('cookie_consent_style', $this->getRequest()->getPost('cookieConsentStyle'));
            $this->getConfig()->set('cookie_consent_pos', $this->getRequest()->getPost('cookieConsentPos'));
            $this->getConfig()->set('cookie_consent_message', $this->getRequest()->getPost('cookieConsentMessage'));
            $this->getConfig()->set('cookie_consent_text', $this->getRequest()->getPost('cookieConsentText'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('cookieConsent', $this->getConfig()->get('cookie_consent'));
        $this->getView()->set('cookieConsentStyle', $this->getConfig()->get('cookie_consent_style'));
        $this->getView()->set('cookieConsentPos', $this->getConfig()->get('cookie_consent_pos'));
        $this->getView()->set('cookieConsentMessage', $this->getConfig()->get('cookie_consent_message'));
        $this->getView()->set('cookieConsentText', $this->getConfig()->get('cookie_consent_text'));

    }
}

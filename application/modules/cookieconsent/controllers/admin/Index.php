<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers\Admin;

use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuCookieConsent',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuCookieConsent'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'cookieConsent' => 'cookieConsentShow',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                cookieConsent => 'required|numeric|min:0|max:1',
                cookieConsentStyle => 'required',
                cookieConsentPos => 'required',
                cookieConsentMessage => 'required',
                cookieConsentText => 'required'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('cookie_consent', $this->getRequest()->getPost('cookieConsent'));
                $this->getConfig()->set('cookie_consent_style', $this->getRequest()->getPost('cookieConsentStyle'));
                $this->getConfig()->set('cookie_consent_pos', $this->getRequest()->getPost('cookieConsentPos'));
                $this->getConfig()->set('cookie_consent_message', $this->getRequest()->getPost('cookieConsentMessage'));
                $this->getConfig()->set('cookie_consent_text', $this->getRequest()->getPost('cookieConsentText'));
                $this->addMessage('saveSuccess');
            }

            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('cookieConsent', $this->getConfig()->get('cookie_consent'));
        $this->getView()->set('cookieConsentStyle', $this->getConfig()->get('cookie_consent_style'));
        $this->getView()->set('cookieConsentPos', $this->getConfig()->get('cookie_consent_pos'));
        $this->getView()->set('cookieConsentMessage', $this->getConfig()->get('cookie_consent_message'));
        $this->getView()->set('cookieConsentText', $this->getConfig()->get('cookie_consent_text'));
    }
}

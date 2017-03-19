<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
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
                ->add($this->getTranslator()->trans('menuCookieConsent'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'cookieConsent' => 'cookieConsentShow',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'cookieConsent' => 'required|numeric|integer|min:0|max:1',
                'cookieConsentLayout' => 'required',
                'cookieConsentPos' => 'required',
                'cookieConsentPopUpBGColor' => 'required',
                'cookieConsentPopUpTextColor' => 'required',
                'cookieConsentBtnBGColor' => 'required',
                'cookieConsentBtnTextColor' => 'required'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('cookie_consent', $this->getRequest()->getPost('cookieConsent'));
                $this->getConfig()->set('cookie_consent_layout', $this->getRequest()->getPost('cookieConsentLayout'));
                $this->getConfig()->set('cookie_consent_pos', $this->getRequest()->getPost('cookieConsentPos'));
                $this->getConfig()->set('cookie_consent_popup_bg_color', $this->getRequest()->getPost('cookieConsentPopUpBGColor'));
                $this->getConfig()->set('cookie_consent_popup_text_color', $this->getRequest()->getPost('cookieConsentPopUpTextColor'));
                $this->getConfig()->set('cookie_consent_btn_bg_color', $this->getRequest()->getPost('cookieConsentBtnBGColor'));
                $this->getConfig()->set('cookie_consent_btn_text_color', $this->getRequest()->getPost('cookieConsentBtnTextColor'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('cookieConsent', $this->getConfig()->get('cookie_consent'));
        $this->getView()->set('cookieConsentLayout', $this->getConfig()->get('cookie_consent_layout'));
        $this->getView()->set('cookieConsentPos', $this->getConfig()->get('cookie_consent_pos'));
        $this->getView()->set('cookieConsentPopUpBGColor', $this->getConfig()->get('cookie_consent_popup_bg_color'));
        $this->getView()->set('cookieConsentPopUpTextColor', $this->getConfig()->get('cookie_consent_popup_text_color'));
        $this->getView()->set('cookieConsentBtnBGColor', $this->getConfig()->get('cookie_consent_btn_bg_color'));
        $this->getView()->set('cookieConsentBtnTextColor', $this->getConfig()->get('cookie_consent_btn_text_color'));
    }
}

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
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
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
            ->add($this->getTranslator()->trans('menuCookieConsent'), ['controller' => 'index', 'action' => 'index']);

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
                $this->getConfig()->set('cookie_consent', $this->getRequest()->getPost('cookieConsent'))
                    ->set('cookie_consent_layout', $this->getRequest()->getPost('cookieConsentLayout'))
                    ->set('cookie_consent_pos', $this->getRequest()->getPost('cookieConsentPos'))
                    ->set('cookie_consent_popup_bg_color', $this->getRequest()->getPost('cookieConsentPopUpBGColor'))
                    ->set('cookie_consent_popup_text_color', $this->getRequest()->getPost('cookieConsentPopUpTextColor'))
                    ->set('cookie_consent_btn_bg_color', $this->getRequest()->getPost('cookieConsentBtnBGColor'))
                    ->set('cookie_consent_btn_text_color', $this->getRequest()->getPost('cookieConsentBtnTextColor'));

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

        $this->getView()->set('cookieConsent', $this->getConfig()->get('cookie_consent'))
            ->set('cookieConsentLayout', $this->getConfig()->get('cookie_consent_layout'))
            ->set('cookieConsentPos', $this->getConfig()->get('cookie_consent_pos'))
            ->set('cookieConsentPopUpBGColor', $this->getConfig()->get('cookie_consent_popup_bg_color'))
            ->set('cookieConsentPopUpTextColor', $this->getConfig()->get('cookie_consent_popup_text_color'))
            ->set('cookieConsentBtnBGColor', $this->getConfig()->get('cookie_consent_btn_bg_color'))
            ->set('cookieConsentBtnTextColor', $this->getConfig()->get('cookie_consent_btn_text_color'));
    }
}

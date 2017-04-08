<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers\Admin;

use Modules\Cookieconsent\Mappers\Cookieconsent as CookieConsentMapper;
use Modules\Cookieconsent\Models\Cookieconsent as CookieConsentModel;
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
            ],
            [
                'name' => 'settings',
                'active' => false,
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
        $cookieConsentMapper = new CookieConsentMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuCookieConsent'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $this->getView()->set('cookieConsentMapper', $cookieConsentMapper)
            ->set('cookieConsents', $cookieConsentMapper->getCookieConsent(['locale' => $this->getConfig()->get('locale')]))
            ->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'))
            ->set('contentLanguage', $this->getConfig()->get('content_language'));
    }

    public function treatAction()
    {
        $cookieConsentMapper = new CookieConsentMapper();

        if ($this->getRequest()->getParam('locale') == '') {
            $locale = $this->getConfig()->get('locale');
        } else {
            $locale = $this->getRequest()->getParam('locale');
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuCookieConsent'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id'), 'locale' => $locale]);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'text' => 'required',
            ]);

            if ($validation->isValid()) {
                $cookieConsentModel = new CookieConsentModel();
                $cookieConsentModel->setText($this->getRequest()->getPost('text'))
                    ->setLocale($locale);
                $cookieConsentMapper->save($cookieConsentModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'locale' => $this->getRequest()->getParam('locale')]);
        }

        $this->getView()->set('cookieConsent', $cookieConsentMapper->getCookieConsentByLocale($locale));
    }
}

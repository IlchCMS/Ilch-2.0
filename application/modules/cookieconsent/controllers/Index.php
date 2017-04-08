<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers;

use Modules\Cookieconsent\Mappers\Cookieconsent as CookieConsentMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $cookieConsentMapper = new CookieConsentMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuCookieConsent'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuCookieConsent'), ['action' => 'index']);

        $this->getView()->set('cookieConsent', $cookieConsentMapper->getCookieConsentByLocale($this->getTranslator()->getLocale()));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuCookieConsent'), ['action' => 'index']);

        $this->getView()->set('cookieConsent', $this->getConfig()->get('cookie_consent'));
        $this->getView()->set('cookieConsentText', $this->getConfig()->get('cookie_consent_text'));
    }
}



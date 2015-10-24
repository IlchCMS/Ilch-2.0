<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Privacy\Controllers;

use Modules\Privacy\Mappers\Privacy as PrivacyMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuPrivacy'), array('action' => 'index'));

        $privacyMapper = new PrivacyMapper();
        $this->getView()->set('privacy', $privacyMapper->getPrivacy());
        $this->getView()->set('privacyShow', $privacyMapper->getPrivacy(array('show' => 1)));
    }
}



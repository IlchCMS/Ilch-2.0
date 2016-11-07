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
        $privacyMapper = new PrivacyMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuPrivacy'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPrivacy'), ['action' => 'index']);

        $this->getView()->set('privacy', $privacyMapper->getPrivacy(['show' => 1]));
    }
}

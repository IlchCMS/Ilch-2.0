<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Privacy\Controllers;

use Modules\Privacy\Mappers\Privacy as PrivacyMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuPrivacy'), array('action' => 'index'));

        $privacyMapper = new PrivacyMapper();
        $privacy = $privacyMapper->getPrivacy();
        $this->getView()->set('privacy', $privacy);
    }
}



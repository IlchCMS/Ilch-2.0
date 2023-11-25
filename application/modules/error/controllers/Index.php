<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Error\Controllers;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getRequest()->getParam('error') != '' && $this->getRequest()->getParam('errorText') != '') {
            http_response_code(404);
            $this->getView()->set('error', $this->getRequest()->getParam('error'));
            $this->getView()->set('errorText', $this->getRequest()->getParam('errorText'));
        } else {
            $this->redirect();
        }
    }
}

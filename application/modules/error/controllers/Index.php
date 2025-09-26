<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Error\Controllers;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getRequest()->getParam('error') != '' && $this->getRequest()->getParam('errorText') != '') {
            http_response_code($this->getRequest()->getParam('errorCode') ?? 404);
            $this->getView()->set('errorCode', $this->getRequest()->getParam('errorCode'));
            $this->getView()->set('error', $this->getRequest()->getParam('error'));
            $this->getView()->set('errorText', $this->getRequest()->getParam('errorText'));
            $this->getView()->set('errorState', $this->getRequest()->getParam('errorState'));
        } else {
            $this->redirect();
        }
    }
}

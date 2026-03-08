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
        if ($this->getRequest()->getParam('errorCode') == 403) {
            http_response_code(403);
            $this->getView()->set('errorCode', 403);
            $this->getView()->set('errorMessage', $this->getTranslator()->trans('noAccessPage'));
        } else {
            http_response_code(404);
            $this->getView()->set('errorCode', 404);
            if ($this->getRequest()->getParam('error') != '' && $this->getRequest()->getParam('errorText') != '') {
                $this->getView()->set('errorMessage', $this->getRequest()->getParam('error') . ' "' . $this->getRequest()->getParam('errorText') . '" ' . $this->getTranslator()->trans('notFound'));
            } else {
                $this->redirect();
            }
        }
    }
}

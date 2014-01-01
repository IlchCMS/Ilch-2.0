<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Langswitch;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        if ($this->getRequest()->getParam('language')) {
            $_SESSION['language'] = $this->getRequest()->getParam('language');
            $this->redirect
            (
                array
                (
                    'modul' => $this->getRequest()->getModuleName(),
                    'controller' => $this->getRequest()->getControllerName(),
                    'action' => $this->getRequest()->getActionName()
                )
            );
        }

        $this->getView()->set('language', $this->getTranslator()->getLocale());
    }
}


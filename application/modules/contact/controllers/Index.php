<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Contact\Controllers;
defined('ACCESS') or die('no direct access');

use Contact\Mappers\Receiver as ReceiverMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuReceiver'));
        $receiverMapper = new ReceiverMapper();
        $receivers = $receiverMapper->getReceivers();

        $this->getView()->set('receivers', $receivers);

        if ($this->getRequest()->isPost()) {
            $receiver = $receiverMapper->getReceiverById($this->getRequest()->getPost('receiver'));
            $subject = 'Kontakt Website <'.$this->getRequest()->getPost('name').'>('.$this->getRequest()->getPost('email').')';

            /*
             * @todo We should create a \Ilch\Mail class.
             */
            mail
            (
                $receiver->getEmail(),
                $subject,
                $this->getRequest()->getPost('message')#
            );

            $this->addMessage('sendSuccess');
        }
    }
}

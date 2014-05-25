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
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuReceiver'), array('action' => 'index'));
        $receiverMapper = new ReceiverMapper();
        $receivers = $receiverMapper->getReceivers();

        $this->getView()->set('receivers', $receivers);

        if ($this->getRequest()->getPost('contact_name') != '') {
            $receiver = $receiverMapper->getReceiverById($this->getRequest()->getPost('contact_receiver'));
            $subject = 'Kontakt Website <'.$this->getRequest()->getPost('contact_name').'>('.$this->getRequest()->getPost('contact_email').')';

            /*
             * @todo We should create a \Ilch\Mail class.
             */
            mail
            (
                $receiver->getEmail(),
                $subject,
                $this->getRequest()->getPost('contact_message')
            );

            $this->addMessage('sendSuccess');
        }
    }
}

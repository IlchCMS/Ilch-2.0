<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Contact\Controllers;

use Modules\Contact\Mappers\Receiver as ReceiverMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuReceiver'), array('action' => 'index'));
        $receiverMapper = new ReceiverMapper();
        $receivers = $receiverMapper->getReceivers();

        $this->getView()->set('receivers', $receivers);

        if ($this->getRequest()->getPost('saveContact')) {
            $receiver = $receiverMapper->getReceiverById($this->getRequest()->getPost('contact_receiver'));
            $name = $this->getRequest()->getPost('contact_name');
            $contactEmail = $this->getRequest()->getPost('contact_email');
            $subject = $this->getTranslator()->trans('contactWebsite').$this->getConfig()->get('page_title').':<'.$name.'>('.$contactEmail.')';
            $captcha = trim(strtolower($this->getRequest()->getPost('captcha')));
            $message = $this->getRequest()->getPost('contact_message');

            if (empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha']) {
                $this->addMessage('invalidCaptcha', 'danger');
            } elseif (empty($message)) {
                $this->addMessage('missingText', 'danger');
            } elseif(empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($contactEmail)) {
                $this->addMessage('missingEmail', 'danger');
            } else {
                /*
                * @todo create a general sender.
                */
                $mail = new \Ilch\Mail();
                $mail->setTo($receiver->getEmail(),$receiver->getName())
                        ->setSubject($subject)
                        ->setFrom('address@domain.tld','automatische eMail')
                        ->setMessage($message)
                        ->addGeneralHeader('Content-type', 'text/plain; charset="utf-8"');
                $mail->send();

                $this->addMessage('sendSuccess');
            }
        }
    }
}

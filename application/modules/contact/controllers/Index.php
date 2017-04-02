<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Contact\Controllers;

use Modules\Contact\Mappers\Receiver as ReceiverMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $receiverMapper = new ReceiverMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuContact'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveContact')) {
            Validation::setCustomFieldAliases([
                'senderName' => 'name',
                'senderEmail' => 'email'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'receiver' => 'required',
                'senderName' => 'required',
                'senderEmail' => 'required|email',
                'message' => 'required',
                'captcha' => 'captcha'
            ]);

            if ($validation->isValid()) {
                $receiver = $receiverMapper->getReceiverById($this->getRequest()->getPost('receiver'));
                $subject = $this->getTranslator()->trans('contactWebsite').' | '.$this->getConfig()->get('page_title');

                $mail = new \Ilch\Mail();
                $mail->setTo($receiver->getEmail(),$receiver->getName())
                    ->setSubject($subject)
                    ->setFrom($this->getRequest()->getPost('senderEmail'), $this->getRequest()->getPost('senderName'))
                    ->setMessage($this->getRequest()->getPost('message'))
                    ->addGeneralHeader('Content-Type', 'text/plain; charset="utf-8"');
                $mail->setAdditionalParameters('-t '.'-f'.$this->getConfig()->get('standardMail'));
                $mail->send();

                $this->redirect()
                    ->withMessage('sendSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('receivers', $receiverMapper->getReceivers());
    }
}

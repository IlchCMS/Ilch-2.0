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

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuContact'), ['action' => 'index']);

        $post = [
            'receiver' => '',
            'senderName' => '',
            'senderEmail' => '',
            'message' => '',
            'captcha' => ''
        ];

        if ($this->getRequest()->getPost('saveContact')) {
            $post = [
                'receiver' => $this->getRequest()->getPost('receiver'),
                'senderName' => $this->getRequest()->getPost('senderName'),
                'senderEmail' => $this->getRequest()->getPost('senderEmail'),
                'message' => $this->getRequest()->getPost('message'),
                'captcha' => trim(strtolower($this->getRequest()->getPost('captcha')))
            ];

            Validation::setCustomFieldAliases([
                'senderName' => 'name',
                'senderEmail' => 'email'
            ]);

            $validation = Validation::create($post, [
                'receiver' => 'required',
                'senderName' => 'required',
                'senderEmail' => 'required|email',
                'message' => 'required',
                'captcha' => 'captcha'
            ]);

            if ($validation->isValid()) {
                $receiver = $receiverMapper->getReceiverById($post['receiver']);
                $subject = $this->getTranslator()->trans('contactWebsite').$this->getConfig()->get('page_title').':<'.$post['senderName'].'>('.$post['senderEmail'].')';

                /*
                * @todo create a general sender.
                */
                $mail = new \Ilch\Mail();
                $mail->setTo($receiver->getEmail(),$receiver->getName())
                        ->setSubject($subject)
                        ->setFrom($this->getConfig()->get('standardMail'), $this->getConfig()->get('page_title'))
                        ->setMessage($post['message'])
                        ->addGeneralHeader('Content-Type', 'text/plain; charset="utf-8"');
                $mail->setAdditionalParameters('-t '.'-f'.$this->getConfig()->get('standardMail'));
                $mail->send();

                $this->addMessage('sendSuccess');
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('receivers', $receiverMapper->getReceivers());
    }
}

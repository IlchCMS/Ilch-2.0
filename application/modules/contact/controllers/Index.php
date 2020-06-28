<?php
/**
 * @copyright Ilch 2
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
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuContact'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveContact')) {
            Validation::setCustomFieldAliases([
                'senderName' => 'name',
                'senderEmail' => 'email'
            ]);

            $validationRules = [
                'receiver' => 'required',
                'senderName' => 'required',
                'senderEmail' => 'required|email',
                'message' => 'required',
                'privacy' => 'required'
            ];

            if ($captchaNeeded) {
                $validationRules['captcha'] = 'captcha';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $receiver = $receiverMapper->getReceiverById($this->getRequest()->getPost('receiver'));
                $subject = $this->getLayout()->escape($this->getTranslator()->trans('contactWebsite').' | '.$this->getConfig()->get('page_title'));
                $content = $this->getLayout()->escape($this->getRequest()->getPost('message'));
                $date = new \Ilch\Date();
                $senderMail = $this->getRequest()->getPost('senderEmail');
                $senderName = $this->getLayout()->escape($this->getRequest()->getPost('senderName'));

                $layout = $_SESSION['layout'] ?? '';

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/contact/layouts/mail/contact.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/contact/layouts/mail/contact.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/contact/layouts/mail/contact.php');
                }

                // $content gets encoded with rawurlencode() for "encodedContent" to later add it to the mailto URI.
                $messageReplace = [
                    '{subject}' => $subject,
                    '{content}' => $content,
                    '{encodedContent}' => rawurlencode($content),
                    '{sitetitle}' => $this->getConfig()->get('page_title'),
                    '{date}' => $date->format('l, d. F Y', true),
                    '{senderMail}' => $senderMail,
                    '{senderName}' => $senderName,
                    '{from}' => $this->getTranslator()->trans('mailFrom'),
                    '{writes}' => $this->getTranslator()->trans('writes'),
                    '{writeBackLink}' => $this->getTranslator()->trans('directOrReplyLink'),
                    '{reply}' => $this->getTranslator()->trans('reply'),
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setFromName($this->getConfig()->get('page_title'))
                    ->setFromEmail($this->getConfig()->get('standardMail'))
                    ->setToName($receiver->getName())
                    ->setToEmail($receiver->getEmail())
                    ->setReplyTo($senderMail)
                    ->setSubject($subject)
                    ->setMessage($message)
                    ->sent();

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
        $this->getView()->set('captchaNeeded', $captchaNeeded);
    }
}

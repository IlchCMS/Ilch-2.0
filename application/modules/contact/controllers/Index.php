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
                $content = $this->getRequest()->getPost('message');
                $date = new \Ilch\Date();
                $senderMail = $this->getRequest()->getPost('senderEmail');
                $senderName = $this->getRequest()->getPost('senderName');

                $layout = '';
                if (isset($_SESSION['layout'])) {
                    $layout = $_SESSION['layout'];
                }

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/contact/layouts/mail/contact.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/contact/layouts/mail/contact.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/contact/layouts/mail/contact.php');
                }

                $messageReplace = [
                    '{subject}' => $subject,
                    '{content}' => $content,
                    '{sitetitle}' => $this->getConfig()->get('page_title'),
                    '{date}' => $date->format("l, d. F Y", true),
                    '{senderMail}' => $senderMail,
                    '{senderName}' => $senderName,
                    '{from}' => $this->getTranslator()->trans('mailFrom'),
                    '{writes}' => $this->getTranslator()->trans('writes'),
                    '{writeBackLink}' => $this->getTranslator()->trans('mailWriteBackLink'),
                    '{reply}' => $this->getTranslator()->trans('reply'),
                    '{footer}' => $this->getTranslator()->trans('noReplyMailFooter'),
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setFromName($this->getConfig()->get('page_title'))
                    ->setFromEmail($this->getConfig()->get('standardMail'))
                    ->setToName($receiver->getName())
                    ->setToEmail($receiver->getEmail())
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
    }
}

<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Boxes;

use Ilch\Box;
use Ilch\Date;
use Ilch\Mail;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Newsletter\Mappers\Subscriber as SubscriberMapper;
use Ilch\Validation;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;

class Newsletter extends Box
{
    public function render()
    {
        $subscriberMapper = new SubscriberMapper();

        $this->getView()->set('success', '');
        if ($this->getRequest()->getPost('saveNewsletterBox')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                // Delete possible old unconfirmed entries before checking if an e-mail is already registered.
                $subscriberMapper->deleteOldUnconfirmedDoubleOptIn();
                $countEmails = $subscriberMapper->countEmails($this->getRequest()->getPost('email'));
                if ($countEmails == 0) {
                    $selector = bin2hex(random_bytes(9));
                    $confirmedCode = bin2hex(random_bytes(32));
                    $date = new Date();

                    if ($this->getConfig()->get('newsletter_doubleOptIn')) {
                        // Double-Opt-In is enabled.
                        $emailsMapper = new EmailsMapper();

                        $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                        $confirmCode = '<a href="' . BASE_URL . '/index.php/newsletter/index/doubleoptin/selector/' . $selector . '/code/' . $confirmedCode . '" class="btn btn-primary btn-sm">' . $this->getTranslator()->trans('confirmMailButtonText') . '</a>';
                        $mailContent = $emailsMapper->getEmail('newsletter', 'newsletter_doubleOptIn', $this->getTranslator()->getLocale());
                        $layout = '';
                        if (!empty($_SESSION['layout'])) {
                            $layout = $_SESSION['layout'];
                        }

                        if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/newsletter/layouts/mail/doubleOptIn.php')) {
                            $messageTemplate = file_get_contents(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/newsletter/layouts/mail/doubleOptIn.php');
                        } else {
                            $messageTemplate = file_get_contents(APPLICATION_PATH . '/modules/newsletter/layouts/mail/doubleOptIn.php');
                        }

                        $messageReplace = [
                            '{content}' => $this->getLayout()->purify($mailContent->getText()),
                            '{sitetitle}' => $siteTitle,
                            '{date}' => $date->format('l, d. F Y', true),
                            '{confirm}' => $confirmCode,
                            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                        ];
                        $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                        $mail = new Mail();
                        $mail->setFromName($siteTitle)
                            ->setFromEmail($this->getConfig()->get('standardMail'))
                            ->setToEmail($this->getRequest()->getPost('email'))
                            ->setSubject($this->getTranslator()->trans('automaticEmail'))
                            ->setMessage($message)
                            ->send();
                    }

                    $subscriberModel = new SubscriberModel();
                    $subscriberModel->setSelector($selector);
                    $subscriberModel->setConfirmCode($confirmedCode);
                    $subscriberModel->setEmail($this->getRequest()->getPost('email'));
                    $subscriberModel->setDoubleOptInDate($date);
                    $subscriberModel->setDoubleOptInConfirmed(false);
                    $subscriberMapper->saveEmail($subscriberModel);
                }
                $this->getView()->set('success', 'true');
            } else {
                $this->getView()->set('success', 'false');
            }
        }
    }
}

<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Controllers;

use Modules\Guestbook\Mappers\Guestbook as GuestbookMapper;
use Modules\Guestbook\Models\Entry as GuestbookModel;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('guestbook'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('guestbook'), ['action' => 'index']);

        $pagination->setRowsPerPage(!$this->getConfig()->get('gbook_entriesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('gbook_entriesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('entries', $guestbookMapper->getEntries(['setfree' => 1], $pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('welcomeMessage', $this->getConfig()->get('gbook_welcomeMessage'));
    }

    public function newEntryAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $ilchDate = new \Ilch\Date;
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('guestbook'))
            ->add($this->getTranslator()->trans('entry'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('guestbook'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('entry'), ['action' => 'newentry']);

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('bot') === '') {
            Validation::setCustomFieldAliases([
                'homepage' => 'page',
                'grecaptcha' => 'token',
            ]);

            $validationRules =  [
                'name'      => 'required',
                'email'     => 'required|email',
                'homepage'  => 'url',
                'text'      => 'required'
            ];

            if ($captchaNeeded) {
                if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                    $validationRules['token'] = 'required|grecaptcha:saveGuestbook';
                } else {
                    $validationRules['captcha'] = 'required|captcha';
                }
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $model = new GuestbookModel();
                $model->setName($this->getRequest()->getPost('name'))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setHomepage($this->getRequest()->getPost('homepage'))
                    ->setDatetime($ilchDate->toDb())
                    ->setFree($this->getConfig()->get('gbook_autosetfree'));
                $guestbookMapper->save($model);

                if ($this->getConfig()->get('gbook_autosetfree') == 0) {
                    $notificationsMapper = new NotificationsMapper();
                    $notificationModel = new NotificationModel();

                    $notificationModel->setModule('guestbook');
                    $notificationModel->setMessage($this->getTranslator()->trans('entryAwaitingApproval'));
                    $notificationModel->setURL($this->getLayout()->getUrl(['module' => 'guestbook', 'controller' => 'index', 'action' => 'index', 'showsetfree' => 1], 'admin'));
                    $notificationModel->setType('guestbookEntryAwaitingApproval');
                    $notificationsMapper->addNotification($notificationModel);

                    $this->addMessage('check', 'info');
                } elseif ($this->getConfig()->get('gbook_notificationOnNewEntry') == 1) {
                    $notificationsMapper = new NotificationsMapper();
                    $notificationModel = new NotificationModel();

                    $notificationModel->setModule('guestbook');
                    $notificationModel->setMessage($this->getTranslator()->trans('notificationForNewEntry'));
                    $notificationModel->setURL($this->getLayout()->getUrl(['module' => 'guestbook', 'controller' => 'index', 'action' => 'index']));
                    $notificationModel->setType('guestbookNotificationOnNewEntry');
                    $notificationsMapper->addNotification($notificationModel);
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'newEntry']);
        }

        if ($captchaNeeded) {
            if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                $googlecaptcha = new \Captcha\GoogleCaptcha($this->getConfig()->get('captcha_apikey'), null, (int)$this->getConfig()->get('captcha'));
                $this->getView()->set('googlecaptcha', $googlecaptcha);
            } else {
                $defaultcaptcha = new \Captcha\DefaultCaptcha();
                $this->getView()->set('defaultcaptcha', $defaultcaptcha);
            }
        }
        $this->getView()->set('captchaNeeded', $captchaNeeded);
    }
}

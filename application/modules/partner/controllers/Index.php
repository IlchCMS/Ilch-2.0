<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Controllers;

use Modules\Partner\Mappers\Partner as PartnerMapper;
use Modules\Partner\Models\Partner as PartnerModel;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $partnerMapper = new PartnerMapper();
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuPartnerAdd'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPartnerAdd'), ['action' => 'index']);

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('bot') === '') {
            Validation::setCustomFieldAliases([
                'grecaptcha' => 'token',
            ]);

            $validationRules = [
                'name' => 'required',
                'link' => 'required|url',
                'banner' => 'required|url'
            ];

            if ($captchaNeeded) {
                if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                    $validationRules['token'] = 'required|grecaptcha:savePartner';
                } else {
                    $validationRules['captcha'] = 'required|captcha';
                }
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);
            if ($validation->isValid()) {
                $model = new PartnerModel();
                $model->setName($this->getRequest()->getPost('name'))
                    ->setLink($this->getRequest()->getPost('link'))
                    ->setBanner($this->getRequest()->getPost('banner'))
                    ->setFree(0);
                $partnerMapper->save($model);

                $notificationsMapper = new NotificationsMapper();
                $notificationModel = new NotificationModel();
                $notificationModel->setModule('partner');
                $notificationModel->setMessage($this->getTranslator()->trans('entryAwaitingApproval'));
                $notificationModel->setURL($this->getLayout()->getUrl(['module' => 'partner', 'controller' => 'index', 'action' => 'index', 'showsetfree' => 1], 'admin'));
                $notificationModel->setType('partnerEntryAwaitingApproval');
                $notificationsMapper->addNotification($notificationModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
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

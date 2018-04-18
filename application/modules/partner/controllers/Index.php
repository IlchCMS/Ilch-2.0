<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Controllers;

use Modules\Partner\Mappers\Partner as PartnerMapper;
use Modules\Partner\Models\Partner as PartnerModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $partnerMapper = new PartnerMapper();
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPartnerAdd'), ['action' => 'index']);

        $post = [
            'name' => '',
            'link' => '',
            'banner' => ''
        ];

        if ($this->getRequest()->getPost('savePartner')) {
            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'link' => trim($this->getRequest()->getPost('link')),
                'banner' => trim($this->getRequest()->getPost('banner')),
                'captcha' => trim($this->getRequest()->getPost('captcha'))
            ];

            $validationRules = [
                'name' => 'required',
                'link' => 'required|url',
                'banner' => 'required|url'
            ];

            if ($captchaNeeded) {
                $validationRules['captcha'] = 'captcha';
            }

            $validation = Validation::create($post, $validationRules);

            if ($validation->isValid()) {
                $model = new PartnerModel();
                $model->setName($post['name']);
                $model->setLink($post['link']);
                $model->setBanner($post['banner']);
                $model->setFree(0);
                $partnerMapper->save($model);

                unset($_SESSION['captcha']);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            unset($_SESSION['captcha']);
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('captchaNeeded', $captchaNeeded);
    }
}

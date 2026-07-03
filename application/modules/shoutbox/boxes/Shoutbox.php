<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Boxes;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Shoutbox extends \Ilch\Box
{
    public function render()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $userMapper = new UserMapper();
        $uniqid = $this->getUniqid();
        if ($this->getRequest()->getPost('uniqid')) {
            $uniqid = $this->getRequest()->getPost('uniqid');
        }
        $captchaNeeded = captchaNeeded();

        $userId = null;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }

        $userCache = [];
        $user = $userId ? $userMapper->getUserById($userId) : null;
        $ids = [3];
        if ($user) {
            $userCache[$user->getId()] = $user;
            $ids = [];
            foreach ($user->getGroups() as $group) {
                $ids[] = $group->getId();
            }
        }

        $validation = null;
        if (($this->getRequest()->getPost('saveshoutboxbox_' . $uniqid) || $this->getRequest()->isAjax()) && $this->getRequest()->getPost('bot') === '') {
            Validation::setCustomFieldAliases([
                'grecaptcha' => 'token',
            ]);

            $maxTextLength = (int)$this->getConfig()->get('shoutbox_maxtextlength');
            $validationRules = [
                'shoutbox_name'     => 'required|max:100,string',
                'shoutbox_textarea' => 'required' . ($maxTextLength > 0 ? '|max:' . $maxTextLength . ',string' : ''),
            ];

            if ($captchaNeeded) {
                if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                    $validationRules['token'] = 'required|grecaptcha:saveshoutbox' . $uniqid;
                } else {
                    $validationRules['captcha'] = 'required|captcha';
                }
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $date = new \Ilch\Date();
                $shoutboxModel = new ShoutboxModel();
                $shoutboxModel->setUid($userId ?? 0)
                    ->setName($userId ? $user->getName() : $this->getRequest()->getPost('shoutbox_name'))
                    ->setTextarea($this->getRequest()->getPost('shoutbox_textarea'))
                    ->setTime($date->toDb());
                $shoutboxMapper->save($shoutboxModel);
            }
        }

        $shoutbox = $shoutboxMapper->getShoutboxLimit($this->getConfig()->get('shoutbox_limit'));
        foreach ($shoutbox as $shoutboxItem) {
            if (!isset($userCache[$shoutboxItem->getUid()])) {
                $userCache[$shoutboxItem->getUid()] = $userMapper->getUserById($shoutboxItem->getUid());
            }
        }

        $this->getView()->setArray([
            'userCache'     => $userCache,
            'uniqid'        => $uniqid,
            'shoutbox'      => $shoutbox,
            'writeAccess'   => $ids,
            'captchaNeeded' => $captchaNeeded,
            'validation'    => $validation
        ]);
        if ($captchaNeeded) {
            if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                $googlecaptcha = new \Captcha\GoogleCaptcha($this->getConfig()->get('captcha_apikey'), null, (int)$this->getConfig()->get('captcha'));
                $this->getView()->set('googlecaptcha', $googlecaptcha);
            } else {
                $defaultcaptcha = new \Captcha\DefaultCaptcha();
                $this->getView()->set('defaultcaptcha', $defaultcaptcha);
            }
        }
    }
}

<?php
/**
 * @copyright Ilch 2.0
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

        $userId = null;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }

        $user = $userMapper->getUserById($userId);
        $ids = [3];
        if ($user) {
            $ids = [];
            foreach ($user->getGroups() as $group) {
                $ids[] = $group->getId();
            }
        }

        if (($this->getRequest()->getPost('form_'.$uniqid) || $this->getRequest()->isAjax()) && $this->getRequest()->getPost('bot') === '') {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'shoutbox_name'     => 'required',
                'shoutbox_textarea' => 'required'
            ]);

            if ($validation->isValid()) {
                $uid = 0;
                if ($this->getUser() !== null) {
                    $uid = $this->getUser()->getId();
                }

                $shoutboxModel = new ShoutboxModel();
                $shoutboxModel->setUid($uid)
                    ->setName($this->getRequest()->getPost('shoutbox_name'))
                    ->setTextarea($this->getRequest()->getPost('shoutbox_textarea'));
                $shoutboxMapper->save($shoutboxModel);
            }
        }

        $this->getView()->setArray([
            'uniqid'         => $uniqid,
            'shoutbox'       => $shoutboxMapper->getShoutboxLimit($this->getConfig()->get('shoutbox_limit')),
            'maxwordlength'  => $this->getConfig()->get('shoutbox_maxwordlength'),
            'writeAccess'    => $ids
        ]);
    }
}

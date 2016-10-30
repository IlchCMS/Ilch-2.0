<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Boxes;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;
use Ilch\Validation;

class Shoutbox extends \Ilch\Box
{
    public function render()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $uniqid = $this->getUniqid();

        if (($this->getRequest()->getPost('form_'.$uniqid) || $this->getRequest()->isAjax()) && $this->getRequest()->getPost('bot') === '') {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'shoutbox_name'         => 'required',
                'shoutbox_textarea'      => 'required'
            ]);

            if ($validation->isValid()) {
                $uid = 0;

                if ($this->getUser() !== null) {
                    $uid = $this->getUser()->getId();
                }

                $shoutboxModel = new ShoutboxModel();
                $shoutboxModel->setUid($uid);
                $shoutboxModel->setName($this->getRequest()->getPost('shoutbox_name'));
                $shoutboxModel->setTextarea($this->getRequest()->getPost('shoutbox_textarea'));
                $shoutboxMapper->save($shoutboxModel);
            }
        }

        $this->getView()->setArray([
            'uniqid'        => $uniqid,
            'shoutbox'      => $shoutboxMapper->getShoutboxLimit($this->getConfig()->get('shoutbox_limit')),
            'maxwordlength' => $this->getConfig()->get('shoutbox_maxwordlength')
        ]);
    }
}

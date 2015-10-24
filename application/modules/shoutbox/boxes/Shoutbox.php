<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Boxes;

class Shoutbox extends \Ilch\Box
{
    public function render()
    {
        $shoutboxMapper = new \Modules\Shoutbox\Mappers\Shoutbox();
        $uniqid = $this->getUniqid();

        if (($this->getRequest()->getPost('form_'.$uniqid) || $this->getRequest()->isAjax())
            && $this->getRequest()->getPost('bot') === ''
        ) {
            $name = $this->getRequest()->getPost('shoutbox_name');
            $textarea = $this->getRequest()->getPost('shoutbox_textarea');
            $uid = 0;

            if ($this->getUser() !== null) {
                $uid = $this->getUser()->getId();
            }

            $shoutboxModel = new \Modules\Shoutbox\Models\Shoutbox();
            $shoutboxModel->setUid($uid);
            $shoutboxModel->setName($name);
            $shoutboxModel->setTextarea($textarea);
            $shoutboxMapper->save($shoutboxModel);
        }

        $this->getView()->setArray([
            'uniqid'        => $uniqid,
            'shoutbox'      => $shoutboxMapper->getShoutboxLimit($this->getConfig()->get('shoutbox_limit')),
            'maxwordlength' => $this->getConfig()->get('shoutbox_maxwordlength')
        ]);
    }
}

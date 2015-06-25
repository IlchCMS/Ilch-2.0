<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Boxes;

defined('ACCESS') or die('no direct access');

class Shoutbox extends \Ilch\Box
{
    public function render()
    {
        $shoutboxMapper = new \Modules\Shoutbox\Mappers\Shoutbox();
        $uniqid = $this->getUniqid();

        if ($this->getRequest()->getPost('form_'.$uniqid) and $this->getRequest()->getPost('bot') === '') {
            $name = $this->getRequest()->getPost('shoutbox_name');
            $textarea = $this->getRequest()->getPost('shoutbox_textarea');
            $uid = 0;

            if($this->getUser() !== null) {
                $uid = $this->getUser()->getId();
            }

            $shoutboxModel = new \Modules\Shoutbox\Models\Shoutbox();
            $shoutboxModel->setUid($uid);
            $shoutboxModel->setName($name);
            $shoutboxModel->setTextarea($textarea);
            $shoutboxMapper->save($shoutboxModel);
        }

        $this->getView()->set('uniqid', $uniqid);
        $this->getView()->set('shoutbox', $shoutboxMapper->getShoutboxLimit(array(), $this->getConfig()->get('shoutbox_limit')));
        $this->getView()->set('maxwordlength', $this->getConfig()->get('shoutbox_maxwordlength'));
    }
}


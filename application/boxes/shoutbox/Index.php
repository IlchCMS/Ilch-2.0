<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Shoutbox;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        $shoutboxMapper = new \Shoutbox\Mappers\Shoutbox();

        if ($this->getRequest()->getPost('shoutbox_name')) {
            $name = $this->getRequest()->getPost('shoutbox_name');
            $textarea = $this->getRequest()->getPost('shoutbox_textarea');

            if($this->getUser() !== null) {
                $uid = $this->getUser()->getId();
            }else{
                $uid = 0;
            }

            $shoutboxModel = new \Shoutbox\Models\Shoutbox();
            $shoutboxModel->setUid($uid);
            $shoutboxModel->setName($name);
            $shoutboxModel->setTextarea($textarea);
            $shoutboxMapper->save($shoutboxModel);
        }

        $this->getView()->set('shoutbox', $shoutboxMapper->getShoutbox(array(), $this->getConfig()->get('shoutbox_limit')));
        $this->getView()->set('maxwordlength', $this->getConfig()->get('shoutbox_maxwordlength'));
    }
}


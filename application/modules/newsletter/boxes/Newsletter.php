<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Boxes;

defined('ACCESS') or die('no direct access');

class Newsletter extends \Ilch\Box
{
    public function render()
    {
        $newsletterMapper = new \Modules\Newsletter\Mappers\Newsletter();
        $uniqid = $this->getUniqid();

        if ($this->getRequest()->getPost('form_' . $uniqid) AND $this->getRequest()->getPost('email') != '') {
            $countEmails = $newsletterMapper->countEmails($this->getRequest()->getPost('email'));
            if ($countEmails == 0) {
                $newsletterModel = new \Modules\Newsletter\Models\Entry();
                $newsletterModel->setEmail($this->getRequest()->getPost('email'));
                $newsletterMapper->saveEmail($newsletterModel);
            } else {
                $newsletterMapper->deleteEmail($this->getRequest()->getPost('email'));
            }
        }

        $this->getView()->set('uniqid', $uniqid);
    }
}

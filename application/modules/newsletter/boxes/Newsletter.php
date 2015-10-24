<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Boxes;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;

class Newsletter extends \Ilch\Box
{
    public function render()
    {
        $newsletterMapper = new NewsletterMapper();

        if ($this->getRequest()->getPost('saveNewsletterBox')) {
            $countEmails = $newsletterMapper->countEmails($this->getRequest()->getPost('email'));
            if ($countEmails == 0) {
                $newsletterModel = new \Modules\Newsletter\Models\Newsletter();
                $newsletterModel->setEmail($this->getRequest()->getPost('email'));
                $newsletterMapper->saveEmail($newsletterModel);
            } else {
                $newsletterMapper->deleteEmail($this->getRequest()->getPost('email'));
            }
        }

        $this->getView();
    }
}

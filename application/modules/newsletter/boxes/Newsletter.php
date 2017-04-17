<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Boxes;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;
use Ilch\Validation;

class Newsletter extends \Ilch\Box
{
    public function render()
    {
        $newsletterMapper = new NewsletterMapper();

        $this->getView()->set('success', '');
        if ($this->getRequest()->getPost('saveNewsletterBox')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                $countEmails = $newsletterMapper->countEmails($this->getRequest()->getPost('email'));
                if ($countEmails == 0) {
                    $newsletterModel = new NewsletterModel();
                    $newsletterModel->setSelector(bin2hex(openssl_random_pseudo_bytes(9)));
                    $newsletterModel->setConfirmCode(bin2hex(openssl_random_pseudo_bytes(32)));
                    $newsletterModel->setEmail($this->getRequest()->getPost('email'));
                    $newsletterMapper->saveEmail($newsletterModel);
                }
                $this->getView()->set('success', 'true');
            } else {
                $this->getView()->set('success', 'false');
            }
        }
    }
}

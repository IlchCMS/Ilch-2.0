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

        $post = [
            'email' => ''
        ];

        if ($this->getRequest()->getPost('saveNewsletterBox')) {
            $post = [
                'email' => $this->getRequest()->getPost('email')
            ];

            $validation = Validation::create($post, [
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                $countEmails = $newsletterMapper->countEmails($post['email']);
                if ($countEmails == 0) {
                    $newsletterModel = new NewsletterModel();
                    $newsletterModel->setEmail($post['email']);
                    $newsletterMapper->saveEmail($newsletterModel);
                } else {
                    $newsletterMapper->deleteEmail($post['email']);
                }
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView();
    }
}

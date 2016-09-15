<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuComments',
            $items
        );
    }
    
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        $post = [
            'reply' => '',
            'nesting' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'reply' => $this->getRequest()->getPost('reply'),
                'nesting' => $this->getRequest()->getPost('nesting')
            ];

            Validation::setCustomFieldAliases([
                'reply' => 'acceptReply'
            ]);

            $validation = Validation::create($post, [
                'reply' => 'required|numeric|integer|min:0|max:1',
                'nesting' => 'required|numeric|integer|min:0'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('comment_reply', $post['reply']);
                $this->getConfig()->set('comment_nesting', $post['nesting']);
                $this->addMessage('saveSuccess');
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('comment_reply', $this->getConfig()->get('comment_reply'));
        $this->getView()->set('comment_nesting', $this->getConfig()->get('comment_nesting'));
    }
}

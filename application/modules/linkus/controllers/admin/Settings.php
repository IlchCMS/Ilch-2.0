<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Linkus\Controllers\Admin;
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
            'menuLinkus',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        $post = [
            'showHtml' => '',
            'showBBCode' => '',
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'showHtml' => $this->getRequest()->getPost('showHtml'),
                'showBBCode' => $this->getRequest()->getPost('showBBCode'),
            ];

            Validation::setCustomFieldAliases([
                'showHtml' => 'htmlForWebsite',
                'showBBCode' => 'bbcodeForForum',
            ]);

            $validation = Validation::create($post, [
                'showHtml' => 'required|numeric|integer|min:0|max:1',
                'showBBCode' => 'required|numeric|integer|min:0|max:1',
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('linkus_html', $post['showHtml']);
                $this->getConfig()->set('linkus_bbcode', $post['showBBCode']);
                $this->addMessage('saveSuccess');
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('linkus_html', $this->getConfig()->get('linkus_html'));
        $this->getView()->set('linkus_bbcode', $this->getConfig()->get('linkus_bbcode'));
    }
}

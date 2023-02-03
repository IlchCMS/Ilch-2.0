<?php
/**
 * @copyright Ilch 2
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
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuLinkus',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'showHtml' => 'htmlForWebsite',
                'showBBCode' => 'bbcodeForForum',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'showHtml' => 'required|numeric|integer|min:0|max:1',
                'showBBCode' => 'required|numeric|integer|min:0|max:1',
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('linkus_html', $this->getRequest()->getPost('showHtml'));
                $this->getConfig()->set('linkus_bbcode', $this->getRequest()->getPost('showBBCode'));
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                  ->withInput()
                  ->withErrors($validation->getErrorBag())
                  ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('linkus_html', $this->getConfig()->get('linkus_html'));
        $this->getView()->set('linkus_bbcode', $this->getConfig()->get('linkus_bbcode'));
    }
}

<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Comment\Controllers\Admin;

use Ilch\Validation;
use Modules\User\Mappers\Group as GroupMapper;

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

        $this->getLayout()->addMenu
        (
            'menuComments',
            $items
        );
    }
    
    public function indexAction() 
    {
        $groupMapper = new GroupMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $post = [
                'reply' => $this->getRequest()->getPost('reply'),
                'nesting' => $this->getRequest()->getPost('nesting'),
                'boxCommentsLimit' => $this->getRequest()->getPost('boxCommentsLimit'),
                'floodInterval' => $this->getRequest()->getPost('floodInterval'),
                'groups' => ($this->getRequest()->getPost('groups')) ?: [],
            ];

            Validation::setCustomFieldAliases([
                'reply' => 'acceptReply'
            ]);

            $validation = Validation::create($post, [
                'reply' => 'required|numeric|integer|min:0|max:1',
                'nesting' => 'required|numeric|integer|min:0',
                'boxCommentsLimit' => 'required|numeric|integer|min:1',
                'floodInterval' => 'required|numeric|integer|min:0'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('comment_reply', $post['reply']);
                $this->getConfig()->set('comment_nesting', $post['nesting']);
                $this->getConfig()->set('comment_box_comments_limit', $post['boxCommentsLimit']);
                $this->getConfig()->set('comment_floodInterval', $post['floodInterval']);
                $this->getConfig()->set('comment_excludeFloodProtection', implode(',', $post['groups']));
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('comment_reply', $this->getConfig()->get('comment_reply'));
        $this->getView()->set('comment_nesting', $this->getConfig()->get('comment_nesting'));
        $this->getView()->set('boxCommentsLimit', $this->getConfig()->get('comment_box_comments_limit'));
        $this->getView()->set('floodInterval', $this->getConfig()->get('comment_floodInterval'));
        $this->getView()->set('excludeFloodProtection', explode(',', $this->getConfig()->get('comment_excludeFloodProtection')));
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Controllers\Admin;

use Modules\Comment\Mappers\Comment as CommentMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'add',
                'active' => false,
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
        $commentMapper = new CommentMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_comments')) {
            foreach ($this->getRequest()->getPost('check_comments') as $commentId) {
                $commentMapper->delete($commentId);
            }
        }

        $this->getView()->set('comments', $commentMapper->getComments());
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $commentMapper = new CommentMapper();
            $commentMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect(['action' => 'index']);
    }
}

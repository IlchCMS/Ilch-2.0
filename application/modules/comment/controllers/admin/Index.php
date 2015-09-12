<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Controllers\Admin;

use Modules\Comment\Mappers\Comment as CommentMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuComments',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $commentMapper = new CommentMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), array('action' => 'index'));

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_comments')) {
            foreach($this->getRequest()->getPost('check_comments') as $commentId) {
                $commentMapper->delete($commentId);
            }
        }

        $this->getView()->set('comments', $commentMapper->getComments());
    }

    public function deleteAction()
    {
        if($this->getRequest()->isSecure()) {
            $commentMapper = new CommentMapper();
            $commentMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect(array('action' => 'index'));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Controllers\Admin;

use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Admin\Mappers\Module as ModuleMapper;

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
                'name' => 'settings',
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
        $modulesMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $modules = [];
        foreach ($commentMapper->getComments() as $comment) {
            $commentKey = preg_replace("#[/].*#", "", $comment->getKey());
            $modules[] = $commentKey;
        }
        $modulesUnique = array_unique($modules);
        sort($modulesUnique);

        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('modulesMapper', $modulesMapper);
        $this->getView()->set('modules', $modulesUnique);
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
    }

    public function showAction()
    {
        $commentMapper = new CommentMapper();
        $modulesMapper = new ModuleMapper();
        $userMapper = new UserMapper();

        $module = $this->getRequest()->getParam('key');
        $modules = $modulesMapper->getModulesByKey($module, $this->getConfig()->get('locale'));

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuComments'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index'])
            ->add($modules->getName(), ['action' => 'show', 'module' => $module]);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_comments')) {
            foreach ($this->getRequest()->getPost('check_comments') as $commentId) {
                $commentMapper->delete($commentId);
            }

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'show', 'key' => $this->getRequest()->getParam('key')]);
        }

        $this->getView()->set('modulesMapper', $modulesMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('comments', $commentMapper->getCommentsLikeKey($module));
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $commentMapper = new CommentMapper();
            $commentMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect()
            ->withMessage('deleteSuccess')
            ->to(['action' => 'show', 'key' => $this->getRequest()->getParam('key')]);
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\Forum\Controllers\Admin\Base as BaseController;
use Modules\Forum\Mappers\Rank as RankMapper;
use Modules\Forum\Models\Rank as RankModel;
use Ilch\Validation;

class Ranks extends BaseController
{
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('ranks'), ['action' => 'index']);

        $rankMapper = new RankMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_forumRanks')) {
            foreach ($this->getRequest()->getPost('check_forumRanks') as $rankId) {
                $rankMapper->delete($rankId);
            }
        }

        $this->getView()->set('ranks', $rankMapper->getRanks());
    }

    public function treatAction()
    {
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($this->getTranslator()->trans('ranks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($this->getTranslator()->trans('ranks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $rankModel = new RankModel();
        $rankMapper = new RankMapper();

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required',
                'posts' => 'required|numeric|integer|min:0'
            ]);

            if ($validation->isValid()) {
                $rankModel->setId($this->getRequest()->getParam('id'));
                $rankModel->setTitle($this->getRequest()->getPost('title'));
                $rankModel->setPosts($this->getRequest()->getPost('posts'));
                $rankMapper->save($rankModel);
                
                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        $this->getView()->set('rank', $rankMapper->getRankById($this->getRequest()->getParam('id')));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $rankMapper = new RankMapper();
            $rankMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}

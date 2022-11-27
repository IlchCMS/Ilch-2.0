<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Validation;
use Modules\Forum\Mappers\Remember as RememberMapper;
use Modules\Forum\Models\Remember as RememberModel;

class Rememberedposts extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $rememberMapper = new RememberMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('rememberedPosts'), ['action' => 'index']);

        if ($this->getUser() && $this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_rememberedPosts')) {
            foreach ($this->getRequest()->getPost('check_rememberedPosts') as $postId) {
                $rememberMapper->delete($postId, $this->getUser()->getId());
            }
        }

        if ($this->getUser()) {
            $this->getView()->set('rememberedPosts', $rememberMapper->getRememberedPostsByUserId($this->getUser()->getId()));
        } else {
            // Return nothing if it is a guest or a not logged in user.
            $this->getView()->set('rememberedPosts', []);
        }
    }

    public function rememberAction()
    {
        if ($this->getUser() && $this->getRequest()->getPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'postId' => 'required|numeric|min:0|integer',
                'note' => 'max:255,string'
            ]);

            if ($validation->isValid()) {
                $rememberMapper = new RememberMapper();
                $rememberModel = new RememberModel();

                if (!$rememberMapper->hasRememberedPostWithPostId($this->getRequest()->getPost('postId'))) {
                    $rememberModel->setPostId($this->getRequest()->getPost('postId'));
                    $rememberModel->setNote($this->getRequest()->getPost('note'));
                    $rememberModel->setUserId($this->getUser()->getId());
                    $rememberMapper->save($rememberModel);
                }
            }
        }
    }

    public function treatAction()
    {
        $rememberMapper = new RememberMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('rememberedPosts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('editRememberedPost'), ['action' => 'index']);

        if ($this->getUser() && $this->getRequest()->getPost()) {
            Validation::setCustomFieldAliases([
                'note' => 'rememberedPostNote',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'note' => 'max:255,string'
            ]);

            if ($validation->isValid()) {
                $rememberModel = new RememberModel();
                $rememberModel->setId($this->getRequest()->getParam('id'));
                $rememberModel->setNote($this->getRequest()->getPost('note'));
                $rememberMapper->save($rememberModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }

        $this->getView()->set('rememberedPost', $rememberMapper->getRememberById($this->getRequest()->getParam('id')));
    }

    public function deleteAction()
    {
        if ($this->getUser() && $this->getRequest()->isSecure()) {
            $rememberMapper = new RememberMapper();
            $rememberMapper->delete($this->getRequest()->getParam('id'), $this->getUser()->getId());

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}

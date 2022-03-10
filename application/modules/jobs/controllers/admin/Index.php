<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Jobs\Controllers\Admin;

use Modules\Jobs\Mappers\Jobs as JobsMapper;
use Modules\Jobs\Models\Jobs as JobsModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuJobs',
            $items
        );
    }

    public function indexAction()
    {
        $jobsMapper = new JobsMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $jobsId) {
                    $jobsMapper->delete($jobsId);
                }
            }
        }

        $this->getView()->set('jobs', $jobsMapper->getJobs());
    }

    public function treatAction()
    {
        $jobsMapper = new JobsMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('jobs', $jobsMapper->getJobsById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'title' => '',
            'text' => '',
            'email' => '',
            'show' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'title' => trim($this->getRequest()->getPost('title')),
                'text' => trim($this->getRequest()->getPost('text')),
                'email' => trim($this->getRequest()->getPost('email')),
                'show' => $this->getRequest()->getPost('show')
            ];

            $validation = Validation::create($post, [
                'title' => 'required',
                'text' => 'required',
                'email' => 'required|email',
                'show' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $model = new JobsModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setTitle($post['title']);
                $model->setText($post['text']);
                $model->setEmail($post['email']);
                $model->setShow($post['show']);
                $jobsMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('post', $post);
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $jobsMapper = new JobsMapper();
            $jobsMapper->update($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $jobsMapper = new JobsMapper();
            $jobsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

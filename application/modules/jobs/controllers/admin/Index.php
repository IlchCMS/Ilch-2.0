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
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
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
                $this->redirect()
                    ->withMessage('deleteSuccess')
                    ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('jobs', $jobsMapper->getJobs());
    }

    public function treatAction()
    {
        $jobsMapper = new JobsMapper();

        $jobsModel = new JobsModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $jobsModel = $jobsMapper->getJobsById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('job', $jobsModel);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required',
                'text' => 'required',
                'email' => 'required|email',
                'show' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $jobsModel->setTitle($this->getRequest()->getPost('title'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setShow($this->getRequest()->getPost('show'));
                $jobsMapper->save($jobsModel);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treat'], ($jobsModel->getId() ? ['id' => $jobsModel->getId()] : [])));
            }
        }
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $jobsMapper = new JobsMapper();
            $jobsMapper->update($this->getRequest()->getParam('id') ?? 0);

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $jobsMapper = new JobsMapper();
            $jobsMapper->delete($this->getRequest()->getParam('id') ?? 0);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

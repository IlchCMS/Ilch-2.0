<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Controllers\Admin;

use Modules\Jobs\Mappers\Jobs as JobsMapper;
use Modules\Jobs\Models\Jobs as JobsModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuJobs',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'add',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuJobs'), array('action' => 'index'));

        $jobsMapper = new JobsMapper();

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_entries') as $jobsId) {
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
                    ->add($this->getTranslator()->trans('menuJobs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('jobs', $jobsMapper->getJobsById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuJobs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));        
        }

        if ($this->getRequest()->isPost()) {
            $model = new JobsModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));
            $text = trim($this->getRequest()->getPost('text'));
            $email = trim($this->getRequest()->getPost('email'));

            if (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } elseif(empty($email)) {
                $this->addMessage('missingEmail', 'danger');
            } else {
                $model->setTitle($title);
                $model->setText($text);
                $model->setEmail($email);
                $model->setShow($this->getRequest()->getPost('show'));
                $jobsMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $jobsMapper = new JobsMapper();
            $jobsMapper->update($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $jobsMapper = new JobsMapper();
            $jobsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}

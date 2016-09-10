<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Controllers;

use Modules\Away\Mappers\Away as AwayMapper;
use Modules\Away\Models\Away as AwayModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $awayMapper = new AwayMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuAway'), ['action' => 'index']);

        $post = [
            'reason' => '',
            'start' => '',
            'end' => '',
            'text' => '',
            'calendarShow' => ''
        ];

        if ($this->getRequest()->getPost('saveAway')) {
            $post = [
                'reason' => trim($this->getRequest()->getPost('reason')),
                'start' => new \Ilch\Date(trim($this->getRequest()->getPost('start'))),
                'end' => new \Ilch\Date(trim($this->getRequest()->getPost('end'))),
                'text' => trim($this->getRequest()->getPost('text')),
                'calendarShow' => trim($this->getRequest()->getPost('calendarShow'))
            ];

            Validation::setCustomFieldAliases([
                'start' => 'when',
                'end' => 'when',
                'text' => 'description'
            ]);

            $validation = Validation::create($post, [
                'reason' => 'required',
                'start' => 'required',
                'end' => 'required',
                'text' => 'required',
                'calendarShow' => 'numeric|integer|min:1|max:1'
            ]);

            if ($validation->isValid()) {
                $awayModel = new AwayModel();
                $awayModel->setUserId($this->getUser()->getId());
                $awayModel->setReason($post['reason']);
                $awayModel->setStart($post['start']);
                $awayModel->setEnd($post['end']);
                $awayModel->setText($post['text']);
                $awayModel->setShow($post['calendarShow']);
                $awayMapper->save($awayModel);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        if ($awayMapper->existsTable('calendar') == true) {
            $this->getView()->set('calendarShow', 1);
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('aways', $awayMapper->getAway());
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->update($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

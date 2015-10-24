<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Controllers;

use Modules\Away\Mappers\Away as AwayMapper;
use Modules\Away\Models\Away as AwayModel;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {        
        $awayModel = new AwayModel();            
        $awayMapper = new AwayMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuAway'), array('action' => 'index'));

        if ($this->getRequest()->getPost('saveAway')) {
            $reason = trim($this->getRequest()->getPost('reason'));
            $start = new \Ilch\Date(trim($this->getRequest()->getPost('start')));
            $end = new \Ilch\Date(trim($this->getRequest()->getPost('end')));
            $text = trim($this->getRequest()->getPost('text'));

            if (empty($reason)) {
                $this->addMessage('missingReason', 'danger');
            } elseif(empty($start)) {
                $this->addMessage('missingStart', 'danger');
            } elseif(empty($end)) {
                $this->addMessage('missingEnd', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $awayModel->setUserId($this->getUser()->getId());
                $awayModel->setReason($reason);
                $awayModel->setStart($start);
                $awayModel->setEnd($end);
                $awayModel->setText($text);
                $awayMapper->save($awayModel);

                $this->addMessage('saveSuccess');
            }
        }

        $this->getView()->set('aways', $awayMapper->getAway());
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->update($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}



<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\History\Controllers\Admin;

use Modules\History\Mappers\History as HistoryMapper;
use Modules\History\Models\Entry as HistoryModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuHistorys',
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
                ->add($this->getTranslator()->trans('menuHistorys'), array('action' => 'index'));

        $historyMapper = new HistoryMapper();
        
        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_entries') as $historyId) {
                    $historyMapper->delete($historyId);
                }
            }
        }
        
        $entries = $historyMapper->getEntries();

        $this->getView()->set('entries', $entries);
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $historyMapper = new HistoryMapper();
            $historyMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        $historyMapper = new HistoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuHistorys'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));
            
            $this->getView()->set('history', $historyMapper->getHistoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuHistorys'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));            
        }

        if ($this->getRequest()->isPost()) {
            $model = new HistoryModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $date = new \Ilch\Date(trim($this->getRequest()->getPost('date')));
            $title = trim($this->getRequest()->getPost('title'));
            $text = trim($this->getRequest()->getPost('text'));
            
            if (empty($date)) {
                $this->addMessage('missingDate', 'danger');
            } elseif(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $model->setDate(new \Ilch\Date(trim($this->getRequest()->getPost('date'))));
                $model->setTitle($this->getRequest()->getPost('title'));
                $model->setText($this->getRequest()->getPost('text'));
                $historyMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}

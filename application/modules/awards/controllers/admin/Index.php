<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Controllers\Admin;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\Awards\Models\Awards as AwardsModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuAwards',
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
                'name' => 'menuActionNewAward',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), array('action' => 'index'));

        $awardsMapper = new AwardsMapper();

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_entries') as $awardsId) {
                    $awardsMapper->delete($awardsId);
                }
            }
        }

        $this->getView()->set('awards', $awardsMapper->getAwards());
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuActionNewAward'), array('action' => 'treat'));

        $awardsMapper = new AwardsMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('awards', $awardsMapper->getAwardsById($this->getRequest()->getParam('id')));
        }

        if ($this->getRequest()->isPost()) {
            $model = new AwardsModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $date = new \Ilch\Date(trim($this->getRequest()->getPost('date')));
            $rank = trim($this->getRequest()->getPost('rank'));
            $squad = trim($this->getRequest()->getPost('squad'));
            
            if (empty($date)) {
                $this->addMessage('missingDate', 'danger');
            } elseif(empty($rank)) {
                $this->addMessage('missingRank', 'danger');
            } elseif(empty($squad)) {
                $this->addMessage('missingSquad', 'danger');
            } else {
                $model->setDate($date);
                $model->setRank($rank);
                $model->setSquad($squad);
                $model->setEvent($this->getRequest()->getPost('event'));
                $model->setPage($this->getRequest()->getPost('page'));
                $awardsMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $awardsMapper = new AwardsMapper();
            $awardsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}

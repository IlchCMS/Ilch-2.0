<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Controllers\Admin\Base as BaseController;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Models\Enemy as EnemyModel;

class Enemy extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewEnemy',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'enemy', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $enemyMapper = new EnemyMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), array('action' => 'index'));

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_enemy')) {
            foreach($this->getRequest()->getPost('check_enemy') as $enemyId) {
                $enemyMapper->delete($enemyId);
            }
        }

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('enemy', $enemyMapper->getEnemyList($pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $enemyMapper = new EnemyMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('treatEnemy'), array('action' => 'treat'));
            $enemy = $enemyMapper->getEnemyById($this->getRequest()->getParam('id'));
            $this->getView()->set('enemy', $enemy);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('manageNewEnemy'), array('action' => 'treat'));
        }

        if ($this->getRequest()->isPost()) {
            $enemyModel = new EnemyModel();

            if ($this->getRequest()->getParam('id')) {
                $enemyModel->setId($this->getRequest()->getParam('id'));
            }

            $enemyName = trim($this->getRequest()->getPost('enemyName'));
            $enemyTag = trim($this->getRequest()->getPost('enemyTag'));
            $enemyImage = trim($this->getRequest()->getPost('enemyImage'));
            $enemyHomepage = $this->getRequest()->getPost('enemyHomepage');
            $enemyContactName = $this->getRequest()->getPost('enemyContactName');
            $enemyContactEmail = $this->getRequest()->getPost('enemyContactEmail');

            if (empty($enemyName)) {
                $this->addMessage('missingEnemyName', 'danger');
            } elseif(empty($enemyTag)) {
                $this->addMessage('missingEnemyTag', 'danger');
            } elseif(empty($enemyImage)) {
                $this->addMessage('missingEnemyImage', 'danger');
            } elseif(empty($enemyHomepage)) {
                $this->addMessage('missingEnemyHomepage', 'danger');
            } elseif(empty($enemyContactName)) {
                $this->addMessage('missingContactName', 'danger');
            } elseif(empty($enemyContactEmail)) {
                $this->addMessage('missingContactEmail', 'danger');
            } else {
                $enemyModel->setEnemyName($enemyName);
                $enemyModel->setEnemyTag($enemyTag);
                $enemyModel->setEnemyImage($enemyImage);
                $enemyModel->setEnemyHomepage($enemyHomepage);
                $enemyModel->setEnemyContactName($enemyContactName);
                $enemyModel->setEnemyContactEmail($enemyContactEmail);
                $enemyMapper->save($enemyModel);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $id = (int)$this->getRequest()->getParam('id');
            $enemyMapper = new EnemyMapper();
            $enemyMapper->delete($id);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}

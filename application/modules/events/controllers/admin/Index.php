<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Models\Events as EventModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuEvents',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                ),
                array
                (
                    'name' => 'menuSettings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $eventMapper = new EventMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'));

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $eventId) {
                    $eventMapper->delete($eventId);
                }
            }
        }

        $event = $eventMapper->getEntries();

        $this->getView()->set('event', $event);
    }

    public function treatAction()
    {
        $eventMapper = new EventMapper();
        $eventModel = new EventModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));            
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('id')) {
                $eventModel->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));
            $dateCreated = new \Ilch\Date(trim($this->getRequest()->getPost('dateCreated')));
            $place = trim($this->getRequest()->getPost('place'));
            $text = trim($this->getRequest()->getPost('text'));

            if (empty($dateCreated)) {
                $this->addMessage('missingDate', 'danger');
            } elseif(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($place)) {
                $this->addMessage('missingPlace', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $eventModel->setUserId($this->getUser()->getId());
                $eventModel->setTitle($title);
                $eventModel->setDateCreated($dateCreated);
                $eventModel->setPlace($place);
                $eventModel->setText($text);
                $eventMapper->save($eventModel);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function showAction()
    {
        if ($this->getRequest()->isPost('delete')) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(array('action' => 'index'));
        }
        $eventMapper = new EventMapper();
        $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Linkus\Controllers\Admin;

use Modules\Linkus\Mappers\Linkus as LinkusMapper;
use Modules\Linkus\Models\Linkus as LinkusModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuLinkus',
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
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), array('action' => 'index'));

        $linkusMapper = new LinkusMapper();

        if ($this->getRequest()->getPost('check_linkus')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_linkus') as $linkusId) {
                    $linkusMapper->delete($linkusId);
                }
            }
        }

        $this->getView()->set('linkus', $linkusMapper->getLinkus());
    }

    public function treatAction()
    {
        $linkusMapper = new LinkusMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinkus'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('linkus', $linkusMapper->getLinkusById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinkus'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));        
        }

        if ($this->getRequest()->isPost()) {
            $model = new LinkusModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));
            $banner = trim($this->getRequest()->getPost('banner'));

            if (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($banner)) {
                $this->addMessage('missingBanner', 'danger');
            } else {
                $model->setTitle($title);
                $model->setBanner($banner);
                $linkusMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $linkusMapper = new LinkusMapper();
            $linkusMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}

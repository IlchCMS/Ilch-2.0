<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Controllers\Admin;

use Modules\Away\Mappers\Away as AwayMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuAway',
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
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAway'), array('action' => 'index'));

        $awayMapper = new AwayMapper();

        if ($this->getRequest()->getPost('check_aways')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_aways') as $awayId) {
                    $awayMapper->delete($awayId);
                }
            }
        }

        $aways = $awayMapper->getAway();

        $this->getView()->set('aways', $aways);
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

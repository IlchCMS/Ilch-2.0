<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Controllers\Admin;

use Modules\Away\Mappers\Away as AwayMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuAway',
            $items
        );
    }

    public function indexAction()
    {
        $awayMapper = new AwayMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAway'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_aways')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_aways') as $id) {
                    $awayMapper->delete($id);
                }
            }
        }

        $userCache = [];
        $aways = $awayMapper->getAway();

        foreach ($aways as $away) {
            if (!array_key_exists($away->getUserId(), $userCache)) {
                $userCache[$away->getUserId()] = $userMapper->getUserById($away->getUserId());
            }
        }

        $this->getView()->set('userCache', $userCache);
        $this->getView()->set('aways', $aways);
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

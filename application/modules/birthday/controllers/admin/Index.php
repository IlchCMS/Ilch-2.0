<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Birthday\Controllers\Admin;

use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuBirthday',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuBirthday'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'numberOfBirthdaysShow' => 'required|numeric|integer|min:1',
                'visibleForGuest' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('bday_boxShow', $this->getRequest()->getPost('numberOfBirthdaysShow'));
                $this->getConfig()->set('bday_visibleForGuest', $this->getRequest()->getPost('visibleForGuest'));
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('numberOfBirthdaysShow', $this->getConfig()->get('bday_boxShow'));
        $this->getView()->set('visibleForGuest', $this->getConfig()->get('bday_visibleForGuest'));
    }
}

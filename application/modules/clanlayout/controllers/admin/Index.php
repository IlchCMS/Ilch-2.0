<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Clanlayout\Controllers\Admin;

use Modules\Clanlayout\Mappers\Layout as LayoutMapper;
use Modules\Clanlayout\Models\Layout as LayoutModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'index']),
                [
                'name' => 'menuSettings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(['module' => 'clanlayout', 'controller' => 'index', 'action' => 'index'])
                ],
            ],
            [
                'name' => 'menuSearch',
                'active' => false,
                'icon' => 'fa fa-search',
                'url' => $this->getLayout()->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'search'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'settings'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuLayouts',
            $items
        );
    }

    public function indexAction()
    {
        $layoutMapper = new LayoutMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['module' => 'admin', 'controller' => 'layouts', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuLayoutName'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'headername' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new LayoutModel();
                $model->setKey('headername')
                    ->setValue($this->getRequest()->getPost('headername'));
                $layoutMapper->update($model);

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('headername', $layoutMapper->getValueByKey('headername'));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;

class Cats extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'cats',
                'active' => false,
                'icon' => 'fa fa-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'new'])
                ]
            ],
            [
                'name' => 'import',
                'active' => false,
                'icon' => 'fa fa-download',
                'url'  => $this->getLayout()->getUrl(['controller' => 'import', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'cats' AND ($this->getRequest()->getActionName() == 'new' OR $this->getRequest()->getActionName() == 'treat')) {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuMedia',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('cats'), ['action' => 'index']);

        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                $mediaMapper->delCatById($catId);
            }
            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('cats', $mediaMapper->getCatList());
    }

    public function newAction() 
    {
        $mediaMapper = new MediaMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('cats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'new']);

        if ($this->getRequest()->getPost('save')) {
            foreach ($this->getRequest()->getPost('title_option') as $catTitle) {
                if (!empty($catTitle)) {
                    $model = new \Modules\Media\Models\Media();
                    $model->setCatName($catTitle);
                    $mediaMapper->saveCat($model);
                }
            }
            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }
    }

    public function delCatAction()
    {
        if ($this->getRequest()->isSecure()) {
            $mediaMapper = new MediaMapper();
            $mediaMapper->delCatById((int)$this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }

    public function setCatAction() 
    {
        $mediaMapper = new MediaMapper();

        $catId = (int)$this->getRequest()->getParam('catid');
        $mediaId = (int)$this->getRequest()->getParam('mediaid');

        $model = new \Modules\Media\Models\Media();
        $model->setCatId($catId);
        $model->setId($mediaId);
        $mediaMapper->setCat($model);

        $this->redirect(['controller' => 'index', 'action' => 'index']);
    }

    public function treatAction() 
    {
        $mediaMapper = new MediaMapper();
        $catId = (int)$this->getRequest()->getParam('id');

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('cats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $catId]);

        if ($this->getRequest()->getPost('save') && $this->getRequest()->getPost('title_treat')) {
            $model = new \Modules\Media\Models\Media();
            $model->setCatId($catId);
            $model->setCatName($this->getRequest()->getPost('title_treat'));
            $mediaMapper->treatCat($model);

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

       $this->getView()->set('cat', $mediaMapper->getCatById($catId));
    }
}

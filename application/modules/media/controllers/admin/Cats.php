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
        $this->getLayout()->addMenu
        (
            'menuMedia',
            [
                [
                    'name' => 'media',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ],
                [
                    'name' => 'cats',
                    'active' => true,
                    'icon' => 'fa fa-list',
                    'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
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
            ]
        );

        $this->getLayout()->addMenuAction
        (
            [
                'name' => 'menuActionAddNewCat',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'new'])
            ]
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
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('cats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('catNew'), ['action' => 'new']);

        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->getPost('save')) {
            foreach ($this->getRequest()->getPost('title_option') as $catTitle) {
                if (!empty($catTitle)) {
                    $model = new \Modules\Media\Models\Media();
                    $model->setCatName($catTitle);
                    $mediaMapper->saveCat($model);
                }
            }
            $this->addMessage('Success');
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

        if ($this->getRequest()->getPost('save') && $this->getRequest()->getPost('title_treat')) {
            $model = new \Modules\Media\Models\Media();
            $model->setCatId($catId);
            $model->setCatName($this->getRequest()->getPost('title_treat'));
            $mediaMapper->treatCat($model);

            $this->addMessage('Success');
            $this->redirect(['action' => 'index']);
        }

       $this->getView()->set('cat', $mediaMapper->getCatById($catId));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;
use Ilch\Date as IlchDate;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuMedia',
            array
            (
                array
                (
                    'name' => 'media',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'cats',
                    'active' => false,
                    'icon' => 'fa fa-list',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'cats', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'import',
                    'active' => false,
                    'icon' => 'fa fa-download',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'import', 'action' => 'index'))
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

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionAddNew',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'upload'))
            )
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), array('action' => 'index'));

        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_medias') > 0) {
            foreach($this->getRequest()->getPost('check_medias') as $mediaId) {
                $mediaMapper->delMediaById($mediaId);
            }
            $this->addMessage('deleteSuccess');
            $this->redirect(array('action' => 'index'));
        }

        $pagination = new \Ilch\Pagination();
        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getParam('rows')) {
            $pagination->setRowsPerPage($this->getRequest()->getParam('rows'));
            $rows = array('rows' => $this->getRequest()->getParam('rows'));
            $this->getView()->set('rows', $rows);
        } else {
            $rows = array();
            $this->getView()->set('rows', $rows);
        }

        if ($this->getRequest()->getPost('search') === 'search') {
            $pagination->setRowsPerPage($this->getRequest()->getPost('rows'));
            $rows = array('rows' => $this->getRequest()->getPost('rows'));
            $this->getView()->set('rows', $rows);
        }

        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('medias', $mediaMapper->getMediaList($pagination));
        $this->getView()->set('catnames', $mediaMapper->getCatList());
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function uploadAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('mediaUpload'), array('action' => 'upload'));

        if (!is_writable(APPLICATION_PATH.'/../'.$this->getConfig()->get('media_uploadpath'))) {
            $this->addMessage('writableMedia', 'danger');
        }

        $ilchdate = new IlchDate;
        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->isPost()) {

            $upload = new \Ilch\Upload();
            $upload->setFile($_FILES['upl']['name']);
            $upload->setTypes($this->getConfig()->get('media_ext_img'));
            $upload->setPath($this->getConfig()->get('media_uploadpath'));
            $upload->upload();

            $model = new \Modules\Media\Models\Media();
            $model->setUrl($upload->getUrl());
            $model->setUrlThumb($upload->getUrlThumb());
            $model->setEnding($upload->getEnding());
            $model->setName($upload->getName());
            $model->setDatetime($ilchdate->toDb());
            $mediaMapper->save($model);
        }
    }

    public function delAction()
    {
        $mediaMapper = new MediaMapper();
        $mediaMapper->delMediaById($this->getRequest()->getParam('id'));
        $this->addMessage('deleteSuccess');
        $this->redirect(array('action' => 'index'));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;
use Ilch\Date as IlchDate;

class Import extends \Ilch\Controller\Admin
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
                    'active' => false,
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
                    'active' => true,
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
    }

    public function indexAction() 
    {
        $ilchdate = new IlchDate;
        $mediaMapper = new MediaMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('import'), array('action' => 'index'));

        $directory = $this->getConfig()->get('media_uploadpath');
        $filetypes = $this->getConfig()->get('media_ext_img');
        $globMediaArray = glob($directory . "*.*");

        if ($this->getRequest()->getPost('save') === 'save') {
            foreach ($this->getRequest()->getPost('check_medias') as $media) {
                $upload = new \Ilch\Upload();
                $upload->setFile($media);
                $upload->setTypes($filetypes);
                $upload->setPath($directory);
                $upload->save();

                $model = new \Modules\Media\Models\Media();
                $model->setUrl($upload->getUrl());
                $model->setUrlThumb($upload->getUrlThumb());
                $model->setEnding($upload->getEnding());
                $model->setName($upload->getName());
                $model->setDatetime($ilchdate->toDb());
                $mediaMapper->save($model);
            }
            $this->addMessage('Success');
            $this->redirect(array('action' => 'index'));
        }

        $mediaListAll = $mediaMapper->getMediaListAll();
        $existsMediaArray = array();
        if (!empty($mediaListAll)) {
            foreach ($mediaListAll as $existsMedia) {
                $existsMediaArray[] = $directory.$existsMedia->getName().'.'.$existsMedia->getEnding();
            }
        }

        $newMediaArray = array();
        foreach ($globMediaArray as $globMedia) {
            $upload = new \Ilch\Upload();
            $upload->setFile($globMedia);

            $existsUrl = $mediaMapper->getByWhere(array('url' => $directory.$upload->getName().'.'.$upload->getEnding()));
            $existsUrlThumb = $mediaMapper->getByWhere(array('url_thumb' => $directory.$upload->getName().'.'.$upload->getEnding()));

            if (!$existsUrl && !$existsUrlThumb) {
                $newMediaArray[] = $directory.$upload->getName().'.'.$upload->getEnding();
            }
        }

        $this->getView()->set('media', array_diff($newMediaArray, $existsMediaArray));
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('path', $directory);
    }
}

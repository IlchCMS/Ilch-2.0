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
                'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'import',
                'active' => true,
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

        $this->getLayout()->addMenu
        (
            'menuMedia',
            $items
        );
    }

    public function indexAction() 
    {
        $ilchdate = new IlchDate;
        $mediaMapper = new MediaMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('import'), ['action' => 'index']);

        $directory = $this->getConfig()->get('media_uploadpath');
        $filetypes = $this->getConfig()->get('media_ext_img');
        $globMediaArray = glob($directory . "*.*");

        if ($this->getRequest()->getPost('save') && $this->getRequest()->getPost('check_medias')) {
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
            $this->addMessage('success');
            $this->redirect(['action' => 'index']);
        }

        $mediaListAll = $mediaMapper->getMediaListAll();
        $existsMediaArray = [];
        if (!empty($mediaListAll)) {
            foreach ($mediaListAll as $existsMedia) {
                $existsMediaArray[] = $directory.$existsMedia->getName().'.'.$existsMedia->getEnding();
            }
        }

        $newMediaArray = [];
        foreach ($globMediaArray as $globMedia) {
            $upload = new \Ilch\Upload();
            $upload->setFile($globMedia);

            $existsUrl = $mediaMapper->getByWhere(['url' => $directory.$upload->getName().'.'.$upload->getEnding()]);
            $existsUrlThumb = $mediaMapper->getByWhere(['url_thumb' => $directory.$upload->getName().'.'.$upload->getEnding()]);

            if (!$existsUrl && !$existsUrlThumb) {
                $newMediaArray[] = $directory.$upload->getName().'.'.$upload->getEnding();
            }
        }

        $this->getView()->set('media', array_diff($newMediaArray, $existsMediaArray));
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('path', $directory);
    }
}

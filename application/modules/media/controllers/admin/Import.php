<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Ilch\Upload;
use Modules\Media\Mappers\Media as MediaMapper;
use Modules\Media\Models\Media as MediaModel;
use Ilch\Date as IlchDate;

class Import extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'cats',
                'active' => false,
                'icon' => 'fa-solid fa-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'import',
                'active' => true,
                'icon' => 'fa-solid fa-download',
                'url'  => $this->getLayout()->getUrl(['controller' => 'import', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuMedia',
            $items
        );
    }

    public function indexAction()
    {
        $ilchdate = new IlchDate();
        $mediaMapper = new MediaMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('import'), ['action' => 'index']);

        $directory = $this->getConfig()->get('media_uploadpath');
        $directoriesAsCategories = $this->getConfig()->get('media_directoriesAsCategories');
        $globMediaArray = glob_recursive($directory . '*');

        if ($this->getRequest()->getPost('save') && $this->getRequest()->getPost('check_medias')) {
            $createdCategories = [];
            foreach ($mediaMapper->getCatList() as $cat) {
                $createdCategories[] = $cat->getCatName();
            }

            foreach ($this->getRequest()->getPost('check_medias') as $media) {
                $id = 0;
                $dirname = \dirname($media);
                $filename = basename($dirname);
                $dirname .= '/';

                if ($directoriesAsCategories) {
                    if ($dirname != $directory && !\in_array($filename, $createdCategories)) {
                        // Create category
                        $category = new MediaModel();
                        $category->setCatName($filename);
                        $id = $mediaMapper->saveCat($category);
                        $createdCategories[] = $filename;
                    } else {
                        $category = $mediaMapper->getCatByName($filename);
                        $id = ($category !== null) ? $category->getId() : 0;
                    }
                }

                $upload = new Upload();
                $upload->setFile($media);
                $upload->setAllowedExtensions($this->getConfig()->get('media_ext_img'));
                $upload->setPath(\dirname($media) . '/');
                $upload->save();

                $model = new MediaModel();
                $model->setUrl($upload->getUrl());
                $model->setUrlThumb($upload->getUrlThumb());
                $model->setEnding($upload->getEnding());
                $model->setName($upload->getName());
                $model->setDatetime($ilchdate->toDb());
                if ($id) {
                    $model->setCatId($id);
                }
                $mediaMapper->save($model);
            }

            $this->addMessage('success');
            $this->redirect(['action' => 'index']);
        }

        $mediaListAll = $mediaMapper->getMediaListAll();
        $existsMediaArray = [];
        if (!empty($mediaListAll)) {
            foreach ($mediaListAll as $existsMedia) {
                $existsMediaArray[] = $directory . $existsMedia->getName() . '.' . $existsMedia->getEnding();
            }
        }

        $newMediaArray = [];
        foreach ($globMediaArray as $globMedia) {
            if (is_file($globMedia)) {
                $upload = new Upload();
                $upload->setFile($globMedia);

                $existsUrl = $mediaMapper->getByWhere(['url' => $globMedia]);
                $existsUrlThumb = $mediaMapper->getByWhere(['url_thumb' => $globMedia]);

                if (!$existsUrl && !$existsUrlThumb) {
                    $newMediaArray[] = $globMedia;
                }
            }
        }

        $this->getView()->set('media', array_diff($newMediaArray, $existsMediaArray));
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
    }
}

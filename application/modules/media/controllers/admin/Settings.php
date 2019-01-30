<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
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
                'active' => false,
                'icon' => 'fa fa-download',
                'url'  => $this->getLayout()->getUrl(['controller' => 'import', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
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
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost('save')) {
            $this->getConfig()->set('media_ext_img', strtolower($this->getRequest()->getPost('allowedImages')));
            $this->getConfig()->set('media_ext_file', strtolower($this->getRequest()->getPost('allowedFiles')));
            $this->getConfig()->set('media_ext_video', strtolower($this->getRequest()->getPost('allowedVideos')));
            $this->getConfig()->set('media_mediaPerPage', $this->getRequest()->getPost('mediaPerPage'));
            $this->getConfig()->set('media_directoriesAsCategories', $this->getRequest()->getPost('directoriesAsCategories'));

            $this->addMessage('success');
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
        $this->getView()->set('mediaPerPage', $this->getConfig()->get('media_mediaPerPage'));
        $this->getView()->set('directoriesAsCategories', $this->getConfig()->get('media_directoriesAsCategories'));
    }
}

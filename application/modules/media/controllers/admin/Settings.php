<?php

/**
 * @copyright Ilch 2
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
                'active' => false,
                'icon' => 'fa-solid fa-download',
                'url'  => $this->getLayout()->getUrl(['controller' => 'import', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
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
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('media'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            // Don't allow adding forbidden file extensions.
            $forbiddenExtensionFound = false;
            $extensionLists = ['allowedImages', 'allowedFiles', 'allowedVideos'];
            $extensionBlacklist = explode(' ', $this->getConfig()->get('media_extensionBlacklist'));

            foreach ($extensionLists as $targetList) {
                $list = explode(' ', strtolower($this->getRequest()->getPost($targetList)));
                if (is_in_array($extensionBlacklist, $list)) {
                    $forbiddenExtensionFound = true;
                    break;
                }
            }

            if (!$forbiddenExtensionFound) {
                $this->getConfig()->set('media_ext_img', strtolower($this->getRequest()->getPost('allowedImages')));
                $this->getConfig()->set('media_ext_file', strtolower($this->getRequest()->getPost('allowedFiles')));
                $this->getConfig()->set('media_ext_video', strtolower($this->getRequest()->getPost('allowedVideos')));

                $this->addMessage('success');
            } else {
                $this->addMessage('forbiddenExtension', 'danger');
            }

            $this->getConfig()->set('media_mediaPerPage', $this->getRequest()->getPost('mediaPerPage'));
            $this->getConfig()->set('media_directoriesAsCategories', $this->getRequest()->getPost('directoriesAsCategories'));
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
        $this->getView()->set('mediaPerPage', $this->getConfig()->get('media_mediaPerPage'));
        $this->getView()->set('directoriesAsCategories', $this->getConfig()->get('media_directoriesAsCategories'));
    }
}

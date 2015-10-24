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
                    'active' => false,
                    'icon' => 'fa fa-download',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'import', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('settings'), array('action' => 'index'));

        if ($this->getRequest()->isPost('save')) {
            $this->getConfig()->set('media_ext_img', $this->getRequest()->getPost('allowedImages'));
            $this->getConfig()->set('media_ext_file', $this->getRequest()->getPost('allowedFiles'));
            $this->getConfig()->set('media_ext_video', $this->getRequest()->getPost('allowedVideos'));

            $this->addMessage('success');
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }
}

<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;

class Boxes extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuBox',
            array
            (
                array
                (
                    'name' => 'menuBoxes',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'boxes', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewBox',
                'icon' => 'fa fa-plus-circle',
                'url' => $this->getLayout()->getUrl(array('controller' => 'boxes', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('boxes'), array('action' => 'index'));

        $boxMapper = new BoxMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_boxes')) {
            foreach($this->getRequest()->getPost('check_boxes') as $boxId) {
                $boxMapper->delete($boxId);
            }
        }

        $boxes = $boxMapper->getBoxList('');

        /*
         * Filtering boxes out which are not allowed for the user.
         */
        $user = \Ilch\Registry::get('user');

        foreach($boxes as $key => $box) {
            if(!$user->hasAccess('box_'.$box->getId())) {
                unset($boxes[$key]);
            }
        }

        $this->getView()->set('boxMapper', $boxMapper);
        $this->getView()->set('boxes', $boxes);
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
    }

    /**
     * Deleting a box.
     *
     * If the user has no rights to do so, just redirect to index.
     */
    public function deleteAction()
    {
        $user = \Ilch\Registry::get('user');

        if($user->hasAccess('box_'.$this->getRequest()->getParam('id'))
                && $this->getRequest()->isSecure()) {
            $boxMapper = new BoxMapper();
            $boxMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        if($this->getRequest()->getParam('id') !== null) {
            $user = \Ilch\Registry::get('user');

            if(!$user->hasAccess('box_'.$this->getRequest()->getParam('id'))) {
                $this->redirect(array('action' => 'index'));
            }
        }

        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $boxMapper = new BoxMapper();

        if ($this->getRequest()->getParam('id')) {
            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('box', $boxMapper->getBoxByIdLocale($this->getRequest()->getParam('id'), $locale));
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));

        if ($this->getRequest()->isPost()) {
            $model = new BoxModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setTitle($this->getRequest()->getPost('boxTitle'));
            $model->setContent($this->getRequest()->getPost('boxContent'));

            if ($this->getRequest()->getPost('boxLanguage') != '') {
                $model->setLocale($this->getRequest()->getPost('boxLanguage'));
            } else {
                $model->setLocale('');
            }

            $boxMapper->save($model);

            $this->redirect(array('action' => 'index'));
        }
    }
}

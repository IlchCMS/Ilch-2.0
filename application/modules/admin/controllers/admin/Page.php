<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\Page as PageModel;
use Modules\Admin\Mappers\Menu as MenuMapper;
use Ilch\Validation;
use Ilch\Sorter;
use Modules\User\Mappers\Group as GroupMapper;

class Page extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'page', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'page', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuSites',
            $items
        );
    }

    public function indexAction()
    {
        $pageMapper = new PageMapper();
        $menuMapper = New MenuMapper();
        $sorter = New Sorter($this->getRequest(), ['id', 'title', 'date_created']);

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSites'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_pages')) {
            foreach ($this->getRequest()->getPost('check_pages') as $pageId) {
                $pageMapper->delete($pageId);
                $menuMapper->deleteItemByPageId($pageId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $pages = $pageMapper->getPageList('', $sorter->getOrderByArray());

        /*
         * Filtering boxes out which are not allowed for the user.
         */
        $user = \Ilch\Registry::get('user');

        foreach ($pages as $key => $page) {
            if (!$user->hasAccess('page_'.$page->getId())) {
                unset($user[$key]);
            }
        }

        $this->getView()->set('pageMapper', $pageMapper)
            ->set('pages', $pages)
            ->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'))
            ->set('contentLanguage', $this->getConfig()->get('content_language'))
            ->set('sorter', $sorter);
    }

    public function treatAction()
    {
        $pageMapper = new PageMapper();
        $groupMapper = new GroupMapper();
        $model = new PageModel();

        $groups = $groupMapper->getGroupList();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuSites'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

            $user = \Ilch\Registry::get('user');

            if (!$user->hasAccess('page_'.$this->getRequest()->getParam('id'))) {
                $this->redirect(['action' => 'index']);
            }

            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }
            $model = $pageMapper->getPageByIdLocale($this->getRequest()->getParam('id'), $locale);
            if (!$model) {
                $model = new PageModel();
            }
            if (!$model->getId()) {
                $model->setId($this->getRequest()->getParam('id'));
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSites'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            // Create full-url of permaLink.
            $entityMap = [
                '&' => '',
                '<' => '',
                '>' => '',
                '"' => '',
                "'" => '',
                '/' => '',
                '(' => '',
                ')' => '',
                ';' => ''
            ];

            $permaLink = strtr($this->getRequest()->getPost('permaLink'), $entityMap);

            if ($permaLink != '') {
                $permaLinkUrl = BASE_URL.'/index.php/'.$permaLink;
            } else {
                $permaLinkUrl = '';
            }

            $_POST['permaLink'] = $permaLinkUrl;

            $validation = Validation::create(
                $this->getRequest()->getPost(),
                [
                    'pageTitle' => 'required',
                    'pageContent' => 'required',
                    'permaLink' => 'url|required'
                ]
            );

            // Restore original values
            $_POST['permaLink'] = $permaLink;

            if ($validation->isValid()) {
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setTitle($this->getRequest()->getPost('pageTitle'));
                $model->setContent($this->getRequest()->getPost('pageContent'));
                $model->setKeywords($this->getRequest()->getPost('keywords'));
                $model->setDescription($this->getRequest()->getPost('description'));
                if ($this->getRequest()->getPost('pageLanguage') != '') {
                    $model->setLocale($this->getRequest()->getPost('pageLanguage'));
                }
                $model->setPerma($this->getRequest()->getPost('permaLink'));
                $pageId = $pageMapper->save($model);

                if (!$model->getId()) {
                    foreach ($groups as $key => $group) {
                        if ($group->getId() !== 1) {
                            $groupMapper->saveAccessData($group->getId(), $pageId, 1, 'page');
                        }
                    }
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($model->getId()?['id' => $model->getId()]:[])));
        }

        $this->getView()->set('page', $model)
            ->set('contentLanguage', $this->getConfig()->get('content_language'))
            ->set('languages', $this->getTranslator()->getLocaleList())
            ->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        $user = \Ilch\Registry::get('user');

        if ($user->hasAccess('box_'.$this->getRequest()->getParam('id')) && $this->getRequest()->isSecure()) {
            $pageMapper = new PageMapper();
            $menuMapper = New MenuMapper();

            $pageMapper->delete($this->getRequest()->getParam('id'));
            $menuMapper->deleteItemByPageId($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

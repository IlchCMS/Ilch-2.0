<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 1.15.0
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Validation;

class Icons extends \Ilch\Controller\Admin
{
    protected $icons = [];

    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaps',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuGameIcon',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index']),
                [
                    'name' => 'menuActionNewGameIcon',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[5][0]['active'] = true;
        } else {
            $items[5]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );

        $this->icons = [];
        foreach (glob(ROOT_PATH.'/application/modules/war/static/img/*') as $iconfile) {
            $icon = basename($iconfile);
            if (strtolower(substr($icon, -4)) == '.png') {
                $this->icons[] = substr($icon, 0, -4);
            }
        }
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manageGameIcon'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_icons')) {
            foreach ($this->getRequest()->getPost('check_icons') as $game) {
                if (in_array($game, $this->icons)) {
                    unlink(ROOT_PATH.'/application/modules/war/static/img/'.$game.'.png');
                }
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('icons', $this->icons);
    }

    public function treatAction()
    {
        $icon = '';

        if ($this->getRequest()->getParam('key')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGameIcon'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatGameIcon'), ['action' => 'treat']);
            if (in_array($this->getRequest()->getParam('key'), $this->icons)) {
                $icon = $this->getRequest()->getParam('key');
            } else {
                $this->redirect()
                    ->to(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGameIcon'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageNewGameIcon'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'gameName' => 'required'
            ]);

            if (!empty($_FILES['icon']['name']) && file_exists($_FILES['icon']['tmp_name'])) {
                $imageInfo = getimagesize($_FILES['icon']['tmp_name']);
                if ($imageInfo[0] > 16 || $imageInfo[1] > 16) {
                    $validation->getErrorBag()->addError('icon', 'failedFilesize');
                }
                if (strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION)) != 'png') {
                    $validation->getErrorBag()->addError('icon', 'forbiddenExtension');
                }
            }

            if ($validation->isValid()) {
                $_POST['gameName'] = strtolower($this->getRequest()->getPost('gameName'));
                if (!empty($_FILES['icon']['name']) && file_exists($_FILES['icon']['tmp_name'])) {
                    move_uploaded_file($_FILES['icon']['tmp_name'], ROOT_PATH.'/application/modules/war/static/img/'.$this->getRequest()->getPost('gameName').'.png');
                } elseif ($this->getRequest()->getParam('key')) {
                    rename(ROOT_PATH.'/application/modules/war/static/img/'.$this->getRequest()->getParam('key').'.png', ROOT_PATH.'/application/modules/war/static/img/'.$this->getRequest()->getPost('gameName').'.png');
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($this->getRequest()->getParam('key') ?['key' => $this->getRequest()->getParam('key')]:[])));
        }

        $this->getView()->set('icon', $icon);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            if (in_array($this->getRequest()->getParam('key'), $this->icons)) {
                unlink(ROOT_PATH.'/application/modules/war/static/img/'.$this->getRequest()->getParam('key').'.png');
            }
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

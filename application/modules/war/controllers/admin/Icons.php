<?php

/**
 * @copyright Ilch 2
 * @package ilch
 * @since 1.15.0
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Validation;
use Modules\War\Mappers\GameIcon as GameIconMapper;
use Modules\War\Models\GameIcon as GameIconModel;

class Icons extends Admin
{
    /** @var GameIconModel[] */
    protected array $icons = [];

    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa-solid fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaps',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index'])
            ],
            [
                'name' => 'menuGameIcons',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index']),
                [
                    'name' => 'menuActionNewGameIcon',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[4][0]['active'] = true;
        } else {
            $items[4]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );

        $gameIconMapper = new GameIconMapper();
        $this->icons = [];
        foreach ($gameIconMapper->getGameIcons() as $gameIcon) {
            $this->icons[$gameIcon->getId()] = $gameIcon;
        }
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuGameIcons'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_icons')) {
            $gameIconMapper = new GameIconMapper();
            foreach ($this->getRequest()->getPost('check_icons') as $id) {
                $id = (int) $id;
                if (isset($this->icons[$id])) {
                    $iconFile = ROOT_PATH . '/application/modules/war/static/img/' . $this->icons[$id]->getIcon() . '.png';
                    if (file_exists($iconFile)) {
                        unlink($iconFile);
                    }
                    $gameIconMapper->delete($id);
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
        $gameIconMapper = new GameIconMapper();
        $gameIconModel = null;

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuGameIcons'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatGameIcon'), ['action' => 'treat']);
            $id = (int) $this->getRequest()->getParam('id');
            if (isset($this->icons[$id])) {
                $gameIconModel = $this->icons[$id];
            } else {
                $this->redirect()
                    ->to(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuGameIcons'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('createNewGameIcon'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'gameName' => 'required',
                'gameIcon' => 'required',
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
                if ($gameIconModel === null) {
                    $gameIconModel = new GameIconModel();
                }

                $gameIconModel->setTitle($this->getRequest()->getPost('gameName'));

                if (!empty($_FILES['icon']['name']) && file_exists($_FILES['icon']['tmp_name'])) {
                    // Delete old icon file if replacing
                    if ($gameIconModel->getId() > 0 && $gameIconModel->getIcon() !== '') {
                        $oldFile = ROOT_PATH . '/application/modules/war/static/img/' . $gameIconModel->getIcon() . '.png';
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }

                    $iconFilename = 'icon_' . str_replace('.', '', uniqid('', true));
                    move_uploaded_file($_FILES['icon']['tmp_name'], ROOT_PATH . '/application/modules/war/static/img/' . $iconFilename . '.png');
                    $gameIconModel->setIcon($iconFilename);
                }

                $gameIconMapper->save($gameIconModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($this->getRequest()->getParam('id') ? ['id' => (int) $this->getRequest()->getParam('id')] : [])));
        }

        $this->getView()->set('gameIconModel', $gameIconModel);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $id = (int) $this->getRequest()->getParam('id');
            if (isset($this->icons[$id])) {
                $iconFile = ROOT_PATH . '/application/modules/war/static/img/' . $this->icons[$id]->getIcon() . '.png';
                if (file_exists($iconFile)) {
                    unlink($iconFile);
                }
                $gameIconMapper = new GameIconMapper();
                $gameIconMapper->delete($id);
            }
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

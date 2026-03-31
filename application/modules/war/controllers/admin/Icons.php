<?php

/**
 * @copyright Ilch 2
 * @package ilch
 * @since 1.15.0
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Validation;
use Modules\War\Mappers\GameIcons;
use Modules\War\Models\GameIcon;

class Icons extends Admin
{
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
    }

    public function indexAction()
    {
        $gameIconsMapper = new GameIcons();
        $gameIconsMap = $gameIconsMapper->getGameIconMap();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuGameIcons'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_icons')) {
            foreach ($this->getRequest()->getPost('check_icons') as $game) {
                $gameIconsMapper->delete($game['id']);
                $iconFile = ROOT_PATH . '/application/modules/war/static/img/' . $gameIconsMap[$game['id']] . '.png';
                if (is_file($iconFile)) {
                    unlink($iconFile);
                }
            }

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $icons = $gameIconsMapper->getGameIcons();

        $this->getView()->set('icons', $icons);
    }

    public function treatAction()
    {
        $gameIconsMapper = new GameIcons();
        $gameIconModel = ($this->getRequest()->getParam('id')) ? $gameIconsMapper->getGameIconById($this->getRequest()->getParam('id')) : null;

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuGameIcons'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatGameIcon'), ['action' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuGameIcons'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('createNewGameIcon'), ['action' => 'treat']);
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
                $gameIconModel = $gameIconModel ?? new GameIcon();

                if (!empty($_FILES['icon']['name']) && file_exists($_FILES['icon']['tmp_name'])) {
                    if ($gameIconModel->getId() && $gameIconModel->getIcon() !== '') {
                        $oldFile = ROOT_PATH . '/application/modules/war/static/img/' . $gameIconModel->getIcon() . '.png';
                        if (is_file($oldFile)) {
                            unlink($oldFile);
                        }
                    }

                    $iconFilename = 'icon_' . str_replace('.', '', uniqid('', true));
                    move_uploaded_file($_FILES['icon']['tmp_name'], ROOT_PATH . '/application/modules/war/static/img/' . $iconFilename . '.png');

                    $gameIconModel->setId($this->getRequest()->getParam('id') ?? null)
                        ->setTitle($this->getRequest()->getPost('gameName'))
                        ->setIcon($iconFilename);
                    $gameIconsMapper->save($gameIconModel);
                }

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

        $this->getView()->set('icon', ($this->getRequest()->getParam('id')) ? $gameIconsMapper->getGameIconById($this->getRequest()->getParam('id')) : null);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $gameIconsMapper = new GameIcons();
            $gameIcon = $gameIconsMapper->getGameIconById($this->getRequest()->getParam('id'));
            $gameIconsMapper->delete($this->getRequest()->getParam('id'));
            $iconFile = ROOT_PATH . '/application/modules/war/static/img/' . $gameIcon->getIcon() . '.png';
            if (is_file($iconFile)) {
                unlink($iconFile);
            }

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}

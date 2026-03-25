<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Validation;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\Maps as MapsMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\War\Mappers\GameIcon as GameIconMapper;
use Modules\War\Models\Enemy as EnemyModel;
use Modules\War\Models\Group as GroupModel;
use Modules\War\Models\Maps as MapsModel;
use Modules\War\Models\War as WarModel;
use Modules\War\Models\Games as GamesModel;
use Modules\War\Models\GameIcon as GameIconModel;

class Settings extends Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'warsPerPage' => 'required|numeric|integer|min:1',
                'enemiesPerPage' => 'required|numeric|integer|min:1',
                'groupsPerPage' => 'required|numeric|integer|min:1',
                'boxNextWarLimit' => 'required|numeric|integer|min:1',
                'boxLastWarLimit' => 'required|numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('war_warsPerPage', $this->getRequest()->getPost('warsPerPage'))
                    ->set('war_enemiesPerPage', $this->getRequest()->getPost('enemiesPerPage'))
                    ->set('war_groupsPerPage', $this->getRequest()->getPost('groupsPerPage'))
                    ->set('war_boxNextWarLimit', $this->getRequest()->getPost('boxNextWarLimit'))
                    ->set('war_boxLastWarLimit', $this->getRequest()->getPost('boxLastWarLimit'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('warsPerPage', $this->getConfig()->get('war_warsPerPage') ?? $this->getConfig()->get('defaultPaginationObjects'))
            ->set('enemiesPerPage', $this->getConfig()->get('war_enemiesPerPage') ?? $this->getConfig()->get('defaultPaginationObjects'))
            ->set('groupsPerPage', $this->getConfig()->get('war_groupsPerPage') ?? $this->getConfig()->get('defaultPaginationObjects'))
            ->set('boxNextWarLimit', $this->getConfig()->get('war_boxNextWarLimit'))
            ->set('boxLastWarLimit', $this->getConfig()->get('war_boxLastWarLimit'));
    }

    public function dummyAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->redirect(['action' => 'index']);
            return;
        }

        // 1. Dummy-Gruppe
        $groupModel = (new GroupModel())
            ->setGroupName('Dummy Clan')
            ->setGroupTag('[DUM]')
            ->setGroupImage('')
            ->setGroupMember('')
            ->setGroupDesc('Diese Gruppe wurde automatisch als Demo-Eintrag erstellt.');
        $groupId = (new GroupMapper())->save($groupModel);

        // 2. Dummy-Gegner
        $enemyModel = (new EnemyModel())
            ->setEnemyName('Demo Enemy')
            ->setEnemyTag('[ENE]')
            ->setEnemyImage('')
            ->setEnemyHomepage('')
            ->setEnemyContactName('')
            ->setEnemyContactEmail('');
        $enemyId = (new EnemyMapper())->save($enemyModel);

        // 3. Dummy-Map
        $mapsModel = (new MapsModel())->setName('Dust 2');
        $mapId = (new MapsMapper())->save($mapsModel);

        // 4. Dummy-Game-Icon (16x16 PNG)
        $iconFilename = 'icon_' . str_replace('.', '', uniqid('', true));
        $iconPath = ROOT_PATH . '/application/modules/war/static/img/' . $iconFilename . '.png';
        if (function_exists('imagecreatetruecolor')) {
            $img = imagecreatetruecolor(16, 16);
            $color = imagecolorallocate($img, 50, 120, 200);
            imagefill($img, 0, 0, $color);
            imagepng($img, $iconPath);
            imagedestroy($img);
        } else {
            // Minimales 16x16 blaues PNG als Fallback
            file_put_contents(
                $iconPath,
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAADklEQVQoz2NgGAWkAgABNgABNjfm6QAAAABJRU5ErkJggg==')
            );
        }
        $gameIconModel = (new GameIconModel())
            ->setTitle('Counter-Strike')
            ->setIcon($iconFilename);
        (new GameIconMapper())->save($gameIconModel);

        // 5. Dummy-War (abgeschlossen)
        $warModel = (new WarModel())
            ->setWarEnemy($enemyId)
            ->setWarGroup($groupId)
            ->setWarTime(date('Y-m-d H:i:s', strtotime('-1 day')))
            ->setWarMaps((string) $mapId)
            ->setWarServer('demo.server.example')
            ->setWarPassword('')
            ->setWarXonx('5on5')
            ->setWarGame('Counter-Strike')
            ->setWarMatchtype('Bo1')
            ->setWarReport('Dies ist ein automatisch erstellter Demo-War-Bericht.')
            ->setWarStatus(2)
            ->setShow(0)
            ->setReadAccess('all')
            ->setLastAcceptTime(0);
        $warId = (new WarMapper())->save($warModel);

        // 6. Dummy-Spielergebnis
        $gamesModel = (new GamesModel())
            ->setWarId($warId)
            ->setMap($mapId)
            ->setGroupPoints(13)
            ->setEnemyPoints(7);
        (new GamesMapper())->save($gamesModel);

        $this->redirect()
            ->withMessage('dummyCreateSuccess')
            ->to(['action' => 'index']);
    }
}

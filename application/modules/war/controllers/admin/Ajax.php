<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\War\Models\Games as GamesModel;
use Modules\War\Mappers\Maps as MapsMapper;

class Ajax extends Admin
{
    public function gameAction()
    {
        $gameMapper = new GamesMapper();
        $mapsMapper = new MapsMapper();

        $this->getLayout()->setFile('modules/admin/layouts/ajax');

        $gameArray = null;
        if ($this->getRequest()->getParam('id')) {
            $gameArray = $gameMapper->getGamesByWarId($this->getRequest()->getParam('id'));
        }

        if (!$gameArray) {
            $gameArray = [new GamesModel()];
        }
        $this->getView()->set('games', $gameArray);
        $this->getView()->set('gamesmaps', $mapsMapper->getList());
    }

    public function delAction()
    {
        $gameMapper = new GamesMapper();
        $gameMapper->deleteById((int)$this->getRequest()->getParam('mapid'));

        $this->redirect(['action' => 'game', 'id' => $this->getRequest()->getParam('id')]);
    }
}

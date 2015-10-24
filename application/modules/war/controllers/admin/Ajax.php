<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Controllers\Admin\Base as BaseController;
use Modules\War\Mappers\Games as GamesMapper;

class Ajax extends BaseController
{
    public function gameAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/ajax');

        $gameMapper = new GamesMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('games', $gameMapper->getGamesByWarId($this->getRequest()->getParam('id')));
        }
    }

    public function delAction()
    {
        $mapId = (int)$this->getRequest()->getParam('mapid');
        $gameMapper = new GamesMapper();
        $gameMapper->deleteById($mapId);

        $this->redirect(array('action' => 'game', 'id' => $this->getRequest()->getParam('id')));
    }
}

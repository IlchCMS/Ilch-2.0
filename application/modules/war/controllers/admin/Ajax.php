<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Games as GamesMapper;

class Ajax extends \Ilch\Controller\Admin
{
    public function gameAction()
    {
        $gameMapper = new GamesMapper();

        $this->getLayout()->setFile('modules/admin/layouts/ajax');

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('games', $gameMapper->getGamesByWarId($this->getRequest()->getParam('id')));
        }
    }

    public function delAction()
    {
        $gameMapper = new GamesMapper();
        $gameMapper->deleteById((int)$this->getRequest()->getParam('mapid'));

        $this->redirect(['action' => 'game', 'id' => $this->getRequest()->getParam('id')]);
    }
}

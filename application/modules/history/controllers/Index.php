<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\History\Controllers;

use Modules\History\Mappers\History as HistoryMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $historyMapper = new HistoryMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuHistorys'), array('action' => 'index'));

        $this->getView()->set('historys', $historyMapper->getEntries());
    }
}
